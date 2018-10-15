<?php

namespace backend\controllers;

use backend\models\search\LogSearch;
use Yii;

class LogController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}