<?php
namespace backend\controllers;

use backend\components\BaseController;
use backend\models\News;
use backend\models\search\NewsSearch;
use richardfan\sortable\SortableAction;
use Yii;
use yii\helpers\Url;

class NewsController extends BaseController
{
    /**
     * @var $modelClass \yii\db\ActiveRecord
     * @var $searchModelClass \yii\db\ActiveRecord
     */
    public $modelClass = News::class;
    public $searchModelClass = NewsSearch::class;
    public $newModelDefaultAttributes = ['status' => News::STATUS_VISIBLE];

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'sortItem' => [
                'class' => SortableAction::class,
                'activeRecordClassName' => News::class,
                'orderColumn' => 'ordering',
            ],
        ];
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->tagList = $model->tags;
        $model->sectionList = $model->sections;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Url::previous());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
}
