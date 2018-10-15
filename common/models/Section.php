<?php
namespace common\models;

use common\modules\seo\behaviors\SeoBehavior;

/**
 * This is the model class for table "section".
 *
 * @property int $id
 * @property string $title
 * @property int $ordering
 * @property int $status
 *
 * @property News[] $news
 */
class Section extends BaseModel
{
    const STATUS_HIDDEN = 0;
    const STATUS_VISIBLE = 1;

    protected $isSeoRequired = true;
    protected static $nameField = 'title';
    protected static $statuses = [
        self::STATUS_HIDDEN => 'Hidden',
        self::STATUS_VISIBLE => 'Visible',
    ];

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function behaviors()
    {
        return [
            'seo' => [
                'class' => SeoBehavior::class,
                'model' => $this->getModelName(),
                'view_category' => '',
                'view_action' => 'section/view',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['ordering', 'status'], 'integer'],
            //[['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            ['ordering', 'default', 'value' => static::find()->max('ordering') + 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'ordering' => 'Ordering',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::class, ['id' => 'news_id'])->viaTable('news_section', ['section_id' => 'id']);
    }

}
