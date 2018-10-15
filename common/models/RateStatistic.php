<?php

namespace common\models;

/**
 * This is the model class for table "rate_statistic".
 *
 * @property int $id
 * @property int $comment_id
 * @property int $rate
 * @property string $user_data
 * @property int $created_at
 */
class RateStatistic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rate_statistic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment_id', 'rate'], 'required'],
            [['comment_id', 'rate', 'created_at'], 'integer'],
            [['user_data'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'comment_id' => 'Comment ID',
            'rate'       => 'Rate',
            'user_data'  => 'User Data',
            'created_at' => 'Created At',
        ];
    }
}
