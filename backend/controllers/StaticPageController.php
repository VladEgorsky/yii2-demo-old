<?php
namespace backend\controllers;

use backend\components\BaseController;
use backend\models\search\StaticPageSearch;
use backend\models\StaticPage;

class StaticPageController extends BaseController
{
    /**
     * @var $modelClass \yii\db\ActiveRecord
     * @var $searchModelClass \yii\db\ActiveRecord
     */
    public $modelClass = StaticPage::class;
    public $searchModelClass = StaticPageSearch::class;
    public $newModelDefaultAttributes = ['status' => StaticPage::STATUS_VISIBLE];

}
