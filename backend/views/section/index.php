<?php
/**
 * @var $this yii\web\View
 * @var $searchModel backend\models\search\SectionSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use backend\components\Y;
use backend\models\Section;
use richardfan\sortable\SortableGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Sections';
$this->blocks['content_header'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="section-index">
    <?= Html::a('New Section', Url::to(['create']), ['class' => 'btn btn-primary', 'style' => 'margin-left: 10px;']) ?>
    <?= Html::button('Sort', ['type' => 'button', 'class' => 'btn btn-success',
        'style' => 'margin-left: 10px;', 'onclick' => 'alert("Drag and drop rows to sort the grid")'])
    ?>

    <?php Pjax::begin(['id' => 'grid_section_pjax']); ?>
    <?= SortableGridView::widget([
        'id' => 'grid_section',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'sortUrl' => Url::to(['sortItem']),
        'columns' => [
            'title',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => Section::getStatusListData(),
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
    <?php Pjax::end(); ?>
</div>
