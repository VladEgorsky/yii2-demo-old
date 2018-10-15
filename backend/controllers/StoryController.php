<?php
namespace backend\controllers;

use backend\components\BaseController;
use backend\models\Story;
use backend\models\search\StorySearch;

class StoryController extends BaseController
{
    /**
     * @var $modelClass \yii\db\ActiveRecord
     * @var $searchModelClass \yii\db\ActiveRecord
     */
    public $modelClass = Story::class;
    public $searchModelClass = StorySearch::class;
    public $newModelDefaultAttributes = ['status' => Story::STATUS_PROCESSED];

}
