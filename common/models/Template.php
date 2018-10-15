<?php

namespace common\models;

use yii\helpers\Json;

/**
 * This is the model class for table "template".
 *
 * @property int $id
 * @property bool $status
 * @property int $items_amount
 * @property string $data
 */
class Template extends BaseModel
{
    const STATUS_HIDDEN = 0;
    const STATUS_VISIBLE = 1;
    protected static $statuses = [
        self::STATUS_HIDDEN => 'Hidden',
        self::STATUS_VISIBLE => 'Visible',
    ];

    // Values for template_location.location_key
    const LOCATION_SECTION = 'SECTION';
    const LOCATION_TAG = 'TAG';
    const LOCATION_MAINSECTION = 'MAINSECTION';
    const LOCATION_MAIN_UPPERBLOCK_ID = 0;

    public static function getMainsectionListData()
    {
//        return [(string)Template::LOCATION_MAIN_UPPERBLOCK_ID => 'MAIN PAGE UPPER BLOCK']
//            + Section::getListData(['status' => Section::STATUS_VISIBLE], ['ordering' => SORT_ASC]);
        return Section::getListData(['status' => Section::STATUS_VISIBLE], ['ordering' => SORT_ASC]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'boolean'],
            [['items_amount'], 'integer', 'min' => 0],
            [['data'], 'required', 'message' => 'Template Zone can not be blank'],
            [['data', 'items_classes'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'items_amount' => 'Items amount',
            'items_classes' => 'Items classes',
            'data' => 'Data',
        ];
    }


    public function getHtmlFromData()
    {
        if (empty($this->data)) {
            return '';
        }

        $html = '';
        $items = Json::decode($this->data);
        foreach ($items as $item) {
            $html .= $item;
        }

        return $html;
    }
}
