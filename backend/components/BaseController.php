<?php

namespace backend\components;

use Throwable;
use Yii;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class BaseController
 * @package backend\components
 *
 * Actions :
 * Index, View, Create, Update, Delete
 * Ajax-create, Ajax-update, Ajax-delete
 */
class BaseController extends Controller
{
    /**
     * @var $modelClass \yii\db\ActiveRecord
     * @var $searchModelClass \yii\db\ActiveRecord
     */
    public $modelClass;
    public $searchModelClass;
    public $newModelDefaultAttributes = [];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'ajax-delete' => ['POST'],
                ],
            ],
            'isAjax' => [
                'class' => AjaxFilter::class,
                'only' => ['ajax-create', 'ajax-update', 'ajax-delete'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterAction($action, $result)
    {
        if ($action->id == 'index') {
            Url::remember($_SERVER['REQUEST_URI']);
        }

        return parent::afterAction($action, $result);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new $this->searchModelClass();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        /** @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass($this->newModelDefaultAttributes);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Url::previous());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Url::previous());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }


    ////////////////////////////////////////////////////////////////////////
    ///                     AJAX - ACTIONS
    /**
     * @return string
     */
    public function actionAjaxCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            /** @var $model \yii\db\ActiveRecord */
            $model = new $this->modelClass($this->newModelDefaultAttributes);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return 'ok';
            }

        } catch (Throwable $t) {
            $model->addError('id', $t->getMessage());
        }

        return Yii::$app->controller->renderAjax('update', ['model' => $model]);
    }

    /**
     * Action must return JSON "ok" if all is ok, HTML otherwise
     *
     * @return string
     */
    public function actionAjaxUpdate($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            /** @var $model \yii\db\ActiveRecord */
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return 'ok';
            }

        } catch (Throwable $t) {
            $model->addError('id', $t->getMessage());
        }

        return Yii::$app->controller->renderAjax('update', ['model' => $model]);
    }

    /**
     * Action must return JSON "ok" if all is ok OR any other
     * JSON string (or array of strings) otherwise
     *
     * @return string
     */
    public function actionAjaxDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $this->findModel($id)->delete();
            return 'ok';

        } catch (Throwable $t) {
            return $t->getMessage();
        }
    }


    ////////////////////////////////////////////////////////////////////////
    ///                     PROTECTED  FUNCTIONS
    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = $this->modelClass::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}