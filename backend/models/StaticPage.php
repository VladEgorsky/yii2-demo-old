<?php

namespace backend\models;

use backend\components\LoggingBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "static_page".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $status
 */
class StaticPage extends \common\models\StaticPage
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'log' => [
                    'class' => LoggingBehavior::class,
                ]
            ]
        );
    }

}
