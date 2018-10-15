<?php
namespace backend\controllers;

use backend\models\search\UserSearch;
use backend\models\User;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['ajax-delete'] = [
            'class' => 'backend\components\actions\AjaxDeleteAction',
            'modelClass' => User::class,
        ];

        return $actions;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $userRoles = User::getRolesGroupedByUserId();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'userRoles' => $userRoles,
        ]);
    }

    /**
     * @param null $id
     * @return string
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     *
     * Action is using for Creating Users also (if empty $id)
     */
    public function actionAjaxUpdate($id = null)
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $model = $id ? User::findOne($id) : new User();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->isNewRecord) {
                $model->generateAuthKey();
                $model->setPassword("1");
            }

            try {
                $model->save();
            } catch (\Exception $e) {
                $model->addError('id', $e->getMessage());
            }

            // Action must return JSON if all is ok, HTML otherwise
            if (!$model->errors) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return 'ok';
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }
}