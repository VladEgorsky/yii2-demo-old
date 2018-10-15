<?php

namespace backend\models;

use backend\components\LoggingBehavior;
use common\components\elastic\ElasticNewsBehavior;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

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
 * @property Tag[] $tags
 */
class News extends \common\models\News
{
    public $tagList;
    public $sectionList;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'log' => [
                    'class' => LoggingBehavior::class,
                    // 'relatedModels' => ['Section', 'Tag'],
                ],
                'elastic' => [
                    'class' => ElasticNewsBehavior::class,
                ],
            ]
        );
    }

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
                [['tagList', 'sectionList'], 'safe'],
                ['ordering', 'default', 'value' => static::find()->max('ordering') + 1],
            ]
        );
    }

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
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->syncTags();
        $this->syncSections();

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @throws \yii\db\Exception
     */
    public function syncTags()
    {
        $ex = (new Query())
            ->select('tag_id')
            ->from('news_tag')
            ->where(['news_id' => $this->id])
            ->column();

        if (!$this->tagList)
            $this->tagList = [];

        $to_delete = array_diff($ex, $this->tagList);
        $to_add = array_diff($this->tagList, $ex);

        if (is_array($to_delete))
            Yii::$app->db->createCommand()->delete('news_tag', ['news_id' => $this->id, 'tag_id' => $to_delete])->execute();

        if (is_array($to_add)) {
            $toInsert = null;

            foreach ($to_add as $tagId) {
                if ($tagId)
                    $toInsert[] = [
                        $this->id,
                        $tagId
                    ];
            }

            //insert new sections
            if ($toInsert)
                \Yii::$app->db->createCommand()->batchInsert('news_tag', ['news_id', 'tag_id'], $toInsert)->execute();
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    public function syncSections()
    {
        $ex = (new Query())
            ->select('section_id')
            ->from('news_section')
            ->where(['news_id' => $this->id])
            ->column();

        if (!$this->sectionList)
            $this->sectionList = [];

        $to_delete = array_diff($ex, $this->sectionList);
        $to_add = array_diff($this->sectionList, $ex);

        if (is_array($to_delete))
            Yii::$app->db->createCommand()->delete('news_section', ['news_id' => $this->id, 'section_id' => $to_delete])->execute();

        if (is_array($to_add)) {
            $toInsert = null;

            foreach ($to_add as $sectionId) {
                if ($sectionId)
                    $toInsert[] = [
                        $this->id,
                        $sectionId
                    ];
            }

            //insert new sections
            if ($toInsert)
                \Yii::$app->db->createCommand()->batchInsert('news_section', ['news_id', 'section_id'], $toInsert)->execute();
        }
    }

    /**
     * Output :
     * SECTIONS: Section1, Section2, Section3 ...
     * TAGS: Tag1, Tag2, Tag3 ...
     *
     * @return string
     */
    public function getLocationNames()
    {
        $val = '';

        $sections = array_map(function ($m) {
            return $m->title;
        }, $this->sections);
        if (!empty($sections)) {
            $val .= 'SECTIONS: ' . implode(', ', $sections) . '<br />';
        }

        $tags = array_map(function ($m) {
            return $m->title;
        }, $this->tags);
        if (!empty($tags)) {
            $val .= 'TAGS: ' . implode(', ', $tags) . '<br />';
        }

        return empty($val) ? $val : substr($val, 0, -6);
    }
}
