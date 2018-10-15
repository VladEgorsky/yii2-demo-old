<?php

namespace common\modules\seo\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "seo".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $head_block
 * @property string $external_link
 * @property string $internal_link
 * @property integer $noindex
 * @property integer $nofollow
 * @property integer $in_sitemap
 * @property integer $is_canonical
 * @property string $model_name
 * @property string $h1
 * @property string $full_model_class
 * @property integer $model_id
 * @property integer $status
 * @property int $parent_id [int(11)]
 * @property int $updated_at [timestamp]
 * @property int $created_at [timestamp]
 * @property string $text_data
 */
class Seo extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo';
    }

    /**
     * @param      $url
     *
     * @return mixed|Seo
     */
    public static function getSEOData($url)
    {
        if (YII_ENV == 'prod') {
            $key = md5($url );
            return Yii::$app->cache->getOrSet($key, function () use ($url) {
                return Seo::find()->where([
                    'external_link' => $url,
                ])->one();
            });
        } else {
            return Seo::find()->where([
                'external_link' => $url,
            ])->one();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['head_block', 'full_model_class', 'text_data'], 'string'],
            [['external_link'], 'required'],
            [['noindex', 'nofollow', 'in_sitemap', 'is_canonical', 'model_id', 'status'], 'integer'],
            [['updated_at', 'lang', 'parent_id'], 'safe'],
            [
                ['title', 'description', 'keywords', 'external_link', 'internal_link', 'model_name', 'h1'],
                'string',
                'max' => 255
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'head_block' => 'Head Block',
            'external_link' => 'External link',
            'internal_link' => 'Internal link',
            'text_data' => 'SEO text',
            'noindex' => 'Noindex',
            'nofollow' => 'Nofollow',
            'in_sitemap' => 'In Sitemap',
            'is_canonical' => 'Is Canonical',
            'model_name' => 'Model Name',
            'model_id' => 'Model ID',
            'status' => 'Status',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $key = md5($this->external_link);
        Yii::$app->cache->delete($key);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $key = md5($this->external_link);
        Yii::$app->cache->delete($key);
        return parent::beforeDelete();
    }
}
