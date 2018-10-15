<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "template_location".
 *
 * @property int $id
 * @property string $advert_key         RIGHT, BOTTOM, COMMENT ...
 * @property string $location_key       SECTION, TAG ...
 * @property int $location_id           section_id || tag_id
 * @property int $template_id
 */
class TemplateLocation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template_location';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::class, ['id' => 'template_id']);
    }
}
