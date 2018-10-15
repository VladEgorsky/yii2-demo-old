<?php

namespace common\components;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;

class LastVisitBehavior extends Behavior
{
    public function events()
    {
        return [
            Controller::EVENT_AFTER_ACTION => 'afterAction'
        ];
    }

    public function afterAction()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->identity->updateAttributes(['lastvisit_at' => time()]);
        }

        return true;
    }
}