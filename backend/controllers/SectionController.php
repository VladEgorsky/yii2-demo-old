<?php
namespace backend\controllers;

use backend\components\BaseController;
use backend\models\search\SectionSearch;
use backend\models\Section;
use richardfan\sortable\SortableAction;

class SectionController extends BaseController
{
    public $modelClass = Section::class;
    public $searchModelClass = SectionSearch::class;
    public $newModelDefaultAttributes = ['status' => Section::STATUS_VISIBLE];

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'sortItem' => [
                'class' => SortableAction::class,
                'activeRecordClassName' => Section::class,
                'orderColumn' => 'ordering',
            ],
        ];
    }

}
