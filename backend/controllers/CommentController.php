<?php
namespace backend\controllers;

use backend\components\BaseController;
use backend\models\Comment;
use backend\models\search\CommentSearch;

class CommentController extends BaseController
{
    /**
     * @var $modelClass \yii\db\ActiveRecord
     * @var $searchModelClass \yii\db\ActiveRecord
     */
    public $modelClass = Comment::class;
    public $searchModelClass = CommentSearch::class;
    public $newModelDefaultAttributes = ['status' => Comment::STATUS_HIDDEN];

    public function actionCreate()
    {
        return $this->redirect(['index']);
    }
}
