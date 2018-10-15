<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\models\Comment;
use kartik\date\DatePicker;
use lo\widgets\modal\ModalAjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Comments';
$this->blocks['content_header'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;

$commentStatusListData = Comment::getStatusListData();
?>

    <div class="comment-index">
        <h3><?= Html::encode($this->title) ?></h3>

        <!-- For using in comment.js -->
        <input type="hidden" id="ajax_update_model_url" name="hi1" value="<?= Url::to(['/auxx/ajax-update-model']) ?>"/>
        <input type="hidden" id="comment_model_class" name="hi2" value="<?= addcslashes(Comment::class, '\\') ?>"/>

        <?php Pjax::begin(['id' => 'grid_comment_pjax']); ?>
        <?= GridView::widget([
            'id' => 'grid_comment',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                    'data-comment' => $model->getAttributes(['id', 'status', 'user_name', 'user_address']),
                    'data-news' => $model->news->getAttributes(['id', 'title', 'author', 'created_at']),
                ];
            },
            'columns' => [
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:d.m.Y H:i'],
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'created_at',
                        'pickerButton' => false,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]
                    ]),
                    'label' => 'Date',
                    'options' => ['style' => 'width: 150px;'],
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) use ($commentStatusListData) {
                        return Html::activeDropDownList($model, 'status', $commentStatusListData, [
                            'class' => 'form-control'
                        ]);
                    },
                    'filter' => $commentStatusListData,
                    'options' => ['style' => 'width: 140px;'],

                ],
                [
                    'attribute' => 'comment',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function ($model) {
                        return StringHelper::truncate($model->comment, Yii::$app->params['ingridStringMaxLength']);
                    }
                ],
                [
                    'format' => 'raw',
                    'label' => 'Info',
                    'value' => function ($model) {
                        $buttons = Html::button('User',
                                ['type' => 'button', 'class' => 'btn btn-default btn-xs btn_user_info']) . ' &nbsp; ';
                        $buttons .= Html::button('News',
                            ['type' => 'button', 'class' => 'btn btn-default btn-xs btn_news_info']);
                        return $buttons;
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} &nbsp; {delete}',
                ],
            ],
        ]); ?>
        <?php Pjax::end() ?>
    </div>

<?php
// See js/controller/comment.js & css/controller/comment.css files
