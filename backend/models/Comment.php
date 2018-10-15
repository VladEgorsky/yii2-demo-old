<?php

namespace backend\models;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $news_id
 * @property string $user_name
 * @property string $user_address
 * @property string $comment
 * @property int $rate
 * @property int $status
 * @property int $created_at
 *
 * @property News $news
 */
class Comment extends \common\models\Comment
{

}
