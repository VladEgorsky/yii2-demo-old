<?php
namespace common\models;

use common\modules\seo\behaviors\SeoBehavior;
use yii\db\Query;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string $title
 *
 * @property News[] $news
 * @property Section[] $section
 */
class Tag extends BaseModel
{
    const STATUS_HIDDEN = 0;
    const STATUS_VISIBLE = 1;

    protected $isSeoRequired = true;
    protected static $nameField = 'title';
    protected static $statuses = [
        self::STATUS_HIDDEN => 'Hidden',
        self::STATUS_VISIBLE => 'Visible',
    ];

    public $sectionIds = [];

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function behaviors()
    {
        return [
            'seo' => [
                'class'         => SeoBehavior::class,
                'model'         => $this->getModelName(),
                'view_category' => 'tag',
                'view_action'   => 'tag/view',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'unique'],
            [['title'], 'string', 'max' => 255],
            [['sectionIds'], 'each', 'rule' => ['integer', 'min' => 1]],
            [['status'], 'boolean'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::class, ['id' => 'news_id'])->viaTable('news_tag', ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(Section::class, ['id' => 'section_id'])->viaTable('section_tag', ['tag_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function fillSectionIds()
    {
        if ($this->isNewRecord) {
            $this->sectionIds = [];
            return true;
        }

        $this->sectionIds = (new Query())->select(['section_id'])->from('{{%section_tag}}')
            ->where(['tag_id' => $this->id])->column();

        return true;
    }
}
