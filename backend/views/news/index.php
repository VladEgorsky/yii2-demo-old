<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\components\Y;
use backend\models\News;
use kartik\date\DatePicker;
use richardfan\sortable\SortableGridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'News';
$this->blocks['content_header'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="news-index">
    <?= Html::a('Create News', ['create'], ['class' => 'btn btn-primary', 'style' => 'margin-left: 10px;']) ?>
    <?= Html::button('Sort', ['type' => 'button', 'class' => 'btn btn-success',
        'style' => 'margin-left: 10px;', 'onclick' => 'alert("Drag and drop rows to sort the grid")'])
    ?>

    <?php Pjax::begin(); ?>
    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'sortUrl' => Url::to(['sortItem']),
        'columns' => [
            ['attribute' => 'id', 'filter' => false],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.y'],
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'pickerButton' => false,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy',
                    ]
                ]),
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
            ],
            [
                'attribute' => 'short_content',
                'format' => 'raw',
            ],
            [
                'label' => 'Locations',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var $model backend\models\News */
                    return $model->getLocationNames();
                }
            ],
            [
                'attribute' => 'cover_image',
                'format' => 'raw',
                'value' => function ($model) {
                    return Y::getBoolValueAsCheckboxIcon($model->cover_image);
                },
                'filter' => false
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => News::getStatusListData(),
                'label' => 'Visibility',
                'value' => function ($model) {
                    return Y::getBoolValueAsCheckboxIcon($model->status);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
