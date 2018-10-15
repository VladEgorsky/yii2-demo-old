<?php

namespace backend\components\rbac\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii2mod\rbac\models\BizRuleModel;
use yii2mod\rbac\models\search\BizRuleSearch;

/**
 * Class RuleController
 *
 * @package yii2mod\rbac\controllers
 */
class RuleController extends Controller
{
    /**
     * @var string search class name for rules search
     */
    public $searchClass = [
        'class' => BizRuleSearch::class,
    ];

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject($this->searchClass);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @param null $id
     * @return string
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     *
     * Action is using for Creating Rule also (if empty $id)
     */
    public function actionAjaxUpdate($id = null)
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }
        $item = $id ? Yii::$app->authManager->getRule($id) : null;
        $model = new BizRuleModel($item);

        // Action must return JSON if all is ok, HTML otherwise
        if (!$model) {
            $model->addError('createdAt', "Data with id=$id not found");
        }

        if ($model && $model->load(Yii::$app->request->post())) {
            try {
                $model->save();
            } catch (\Exception $e) {
                $model->addError('createdAt', $e->getMessage());
            }

            if (!$model->errors) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return 'ok';
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     * @throws BadRequestHttpException
     * @throws \Throwable
     */
    public function actionAjaxDelete()
    {
        if (!Yii::$app->request->isAjax || !Yii::$app->request->isPost) {
            throw new BadRequestHttpException();
        }

        // Action must return "ok" if all is ok, any other string otherwise
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        if (empty($id)) {
            return 'Incorrect incoming parameters';
        }

        $item = Yii::$app->authManager->getRule($id);
        if (!$item) {
            return "Rule with name=$id not found";
        }

        try {
            Yii::$app->authManager->remove($item);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return 'ok';
    }
}
