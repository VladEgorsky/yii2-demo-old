<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\SubscribeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\models\Subscribe;
use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Subscribes';
$this->params['breadcrumbs'][] = $this->title;

$statusListData = Subscribe::getStatusListData();
$periodListData = Subscribe::getPeriodListData();
?>

<div class="subscribe-index">
    <h3><?= Html::encode($this->title) ?></h3>

    <?= Html::a('New Subscribe', Url::to(['create']), ['class' => 'btn btn-primary', 'style' => 'margin-left: 10px;']) ?>

    <!-- For using in subscribe.js -->
    <input type="hidden" id="ajax_update_model_url" name="hi1" value="<?= Url::to(['/auxx/ajax-update-model']) ?>"/>
    <input type="hidden" id="subscribe_model_class" name="hi2" value="<?= addcslashes(Subscribe::class, '\\') ?>"/>

    <?php Pjax::begin(['id' => 'grid_subscribe_pjax']); ?>
    <?= GridView::widget([
        'id' => 'grid_subscribe',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'data-subscribe' => $model->getAttributes(['id', 'status', 'period']),
                'data-sections' => ArrayHelper::getColumn($model->sections, 'title'),
                'data-tags' => ArrayHelper::getColumn($model->tags, 'title'),
            ];
        },
        'columns'      => [
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
            'name',
            'email:email',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) use ($statusListData) {
                    return Html::activeDropDownList($model, 'status', $statusListData, [
                        'class' => 'form-control'
                    ]);
                },
                'filter' => $statusListData
            ],
            [
                'attribute' => 'period',
                'format' => 'raw',
                'value' => function ($model) use ($periodListData) {
                    return Html::activeDropDownList($model, 'status', $periodListData, [
                        'class' => 'form-control'
                    ]);
                },
                'filter' => $periodListData
            ],
            [
                'format' => 'raw',
                'label' => 'Location',
                'value' => function ($model) {
                    return Html::button('Info',
                        ['type' => 'button', 'class' => 'btn btn-default btn-xs btn_location_info']);
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
