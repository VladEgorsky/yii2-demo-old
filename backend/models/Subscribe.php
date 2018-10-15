<?php

namespace backend\models;

/**
 * This is the model class for table "subscribe".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $status
 * @property int $period
 * @property int $created_at
 *
 * @property SubscribeSection[] $subscribeSections
 * @property SubscribeTag[] $subscribeTags
 */
class Subscribe extends \common\models\Subscribe
{

}
