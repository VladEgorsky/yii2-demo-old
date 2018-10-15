<?php
namespace backend\controllers;

use common\components\controllers\BaseSiteController;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SiteController extends BaseSiteController
{
    public $layout = 'blank';

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    public function actions()
    {
        return ArrayHelper::merge(
            parent::actions(),
            [
                'page' => [
                    'class' => 'yii\web\ViewAction',
                    'layout' => 'blank',
                    'defaultView' => '404.php',
                    'viewPrefix' => 'pages',
                ],
            ]
        );
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'main';
        return $this->render('index');
    }

}
