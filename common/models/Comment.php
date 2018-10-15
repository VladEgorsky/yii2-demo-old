<?php

namespace common\models;

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
class Comment extends BaseModel
{
    const STATUS_HIDDEN = 0;
    const STATUS_VISIBLE = 1;

    protected static $nameField = 'comment';
    protected static $statuses = [
        self::STATUS_HIDDEN  => 'Hidden',
        self::STATUS_VISIBLE => 'Visible',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id', 'user_name', 'user_address', 'comment'], 'required'],
            [['news_id', 'rate', 'status', 'created_at'], 'integer'],
            [['comment'], 'string'],
            ['status', 'default', 'value' => static::STATUS_HIDDEN],
            [['user_name', 'user_address'], 'string', 'max' => 255],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'news_id'      => 'News ID',
            'user_name'    => 'User Name',
            'user_address' => 'User Address',
            'comment'      => 'Comment',
            'rate'         => 'Rate',
            'status'       => 'Status',
            'created_at'   => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }
}
