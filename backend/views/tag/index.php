<?php
/**
 * @var $this yii\web\View
 * @var $searchModel backend\models\search\TagsSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use backend\components\Y;
use backend\models\Tag;
use lo\widgets\modal\ModalAjax;
use richardfan\sortable\SortableGridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Tags';
$this->blocks['content_header'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="tag-index">
    <?= Html::a('New Tag', Url::to(['create']), ['class' => 'btn btn-primary', 'style' => 'margin-left: 10px;']) ?>
    <?= Html::button('Sort', ['type' => 'button', 'class' => 'btn btn-success',
        'style' => 'margin-left: 10px;', 'onclick' => 'alert("Drag and drop rows to sort the grid")'])
    ?>

    <?php Pjax::begin(['id' => 'grid_tag_pjax']); ?>
    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'sortUrl' => Url::to(['sortItem']),
        'columns' => [
            [
                'attribute' => 'id',
                'filter' => false,
            ],
            'title',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => Tag::getStatusListData(),
                'label' => 'Visibility',
                'value' => function ($model) {
                    return Y::getBoolValueAsCheckboxIcon($model->status);
                }
            ],
            [
                'attribute' => 'sections',
                'value' => function ($model) {
                    if (empty($model->sections)) {
                        return '';
                    }

                    $titles = ArrayHelper::getColumn($model->sections, 'title');
                    return implode(', ', $titles);
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
