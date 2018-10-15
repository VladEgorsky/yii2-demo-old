<?php
namespace backend\controllers;

use backend\components\BaseController;
use backend\models\search\SubscribeSearch;
use backend\models\Subscribe;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class SubscribeController extends BaseController
{
    /**
     * @var $modelClass \yii\db\ActiveRecord
     * @var $searchModelClass \yii\db\ActiveRecord
     */
    public $modelClass = Subscribe::class;
    public $searchModelClass = SubscribeSearch::class;
    public $newModelDefaultAttributes = ['status' => Subscribe::STATUS_ACTIVE];

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
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
