<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\StaticPageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\components\Y;
use backend\models\StaticPage;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Static Pages';
$this->blocks['content_header'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="static-page-index">
    <?= Html::a('New Static Page', Url::to(['create']), ['class' => 'btn btn-primary', 'style' => 'margin-left: 10px;']) ?>

    <?php Pjax::begin(['id' => 'grid_page_pjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [
                'attribute' => 'id',
                'filter' => false,
            ],
            'title',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => StaticPage::getStatusListData(),
                'label' => 'Visibility',
                'value' => function ($model) {
                    return Y::getBoolValueAsCheckboxIcon($model->status);
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
