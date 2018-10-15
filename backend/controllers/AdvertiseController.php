<?php
namespace backend\controllers;

use backend\components\BaseController;
use backend\models\Advertise;
use backend\models\search\AdvertiseSearch;

class AdvertiseController extends BaseController
{
    /**
     * @var $modelClass \yii\db\ActiveRecord
     * @var $searchModelClass \yii\db\ActiveRecord
     */
    public $modelClass = Advertise::class;
    public $searchModelClass = AdvertiseSearch::class;
    public $newModelDefaultAttributes = ['status' => Advertise::STATUS_PROCESSED];

}
