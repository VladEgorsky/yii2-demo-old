<?php
namespace backend\models;

use backend\components\LoggingBehavior;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string $title
 *
 * @property News[] $news
 */
class Tag extends \common\models\Tag
{
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
                    'relatedModels' => ['Section'],
                ]
            ]
        );
    }

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
                [['sectionList'], 'safe'],
                ['ordering', 'default', 'value' => static::find()->max('ordering') + 1],
            ]
        );
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->syncSections();
        parent::afterSave($insert, $changedAttributes);
    }


    /**
     * @param $tag
     * @return bool|int|mixed
     */
    public static function getTagId($tag)
    {
        $tag = trim($tag);
        if (!$tag)
            return false;

        $model = static::find()->where(['title' => $tag])->one();
        if (!$model) {
            $model = new static([
                'title' => $tag,
            ]);
            $model->save();
        }

        return $model->id;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function syncSections()
    {
        $ex = (new Query())
            ->select('section_id')
            ->from('section_tag')
            ->where(['tag_id' => $this->id])
            ->column();

        if (!$this->sectionIds)
            $this->sectionIds = [];

        $to_delete = array_diff($ex, $this->sectionIds);
        $to_add = array_diff($this->sectionIds, $ex);

        if (is_array($to_delete))
            Yii::$app->db->createCommand()->delete('section_tag', ['tag_id' => $this->id, 'section_id' => $to_delete])->execute();

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
                \Yii::$app->db->createCommand()->batchInsert('section_tag', ['tag_id', 'section_id'], $toInsert)->execute();
        }
    }


    /**
     * @param $section
     * @param bool $asList
     * @return array|Section[]|Tag[]|\yii\db\ActiveRecord[]
     */
    public static function getListBySections($section, $asList = false)
    {

        if (is_array($section)) {
            if (isset($section[0]) && is_object($section[0])) {
                $list = [];
                foreach ($section as $item)
                    $list[] = $item['id'];
                $section = $list;
            }
        }

        $data = Tag::find()
            ->joinWith('sections')
            ->andWhere(['or', ['section_tag.section_id' => $section], ['is', 'section_tag.section_id', null]])
            ->all();

        if ($asList)
            return ArrayHelper::map($data, 'id', 'title');

        return $data;
    }
}
