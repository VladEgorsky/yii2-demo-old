<?php
namespace common\models;

use common\modules\seo\behaviors\SeoBehavior;
use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $short_content
 * @property string $content
 * @property string $cover_image
 * @property string $cover_video
 * @property string $author
 * @property int $comment_count
 * @property int $ordering
 * @property int $status
 * @property string $created_at
 *
 * @property Section[] $sections
 * @property \yii\db\ActiveQuery $seo
 * @property array $tagsList
 * @property null|string $seoUrl
 * @property Tag[] $tags
 */
class News extends BaseModel
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
            'seo'   => [
                'class' => SeoBehavior::class,
                'model' => $this->getModelName(),
                'view_category' => 'news',
                'view_action' => 'news/view',
            ],
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['short_content', 'content'], 'string'],
            [['comment_count', 'ordering', 'status'], 'integer'],
            [['created_at'], 'safe'],
            [['title', 'cover_image', 'cover_video', 'author'], 'string', 'max' => 255],
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
            'short_content' => 'Short Content',
            'content' => 'Content',
            'cover_image' => 'Cover Image',
            'cover_video' => 'Cover Video',
            'author' => 'Author',
            'comment_count' => 'Comment Count',
            'ordering' => 'Ordering',
            'status' => 'Status',
            'created_at' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(Section::class, ['id' => 'section_id'])->viaTable('news_section', ['news_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('news_tag', ['news_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getTagsList()
    {
        $list = [];

        if ($this->tags)
            foreach ($this->tags as $tag) {
                $list[] = $tag->title;
            }

        return $list;
    }

    /**
     * @return array
     */
    public function getSectionsList()
    {
        $list = [];

        if ($this->sections)
            foreach ($this->sections as $section) {
                $list[] = $section->title;
            }

        return $list;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = time();
        }

        return parent::beforeSave($insert);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->cover_image) {
            if ($insert || isset($changedAttributes['cover_image'])) {
                if (isset($changedAttributes['cover_image']))
                    $this->removeImages();

                $this->attachImage(Yii::getAlias('@frontend') . '/web' . $this->cover_image, true);
            }

        }

        parent::afterSave($insert, $changedAttributes);
    }


}
