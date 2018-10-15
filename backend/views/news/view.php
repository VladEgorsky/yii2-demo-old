<?php
/* @var $this yii\web\View */
/* @var $model backend\models\News */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->blocks['content_header'] = 'News Content';
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">
    <h3><?= Html::encode($this->title) ?></h3>

    <?= Html::a('Update', ['update', 'id' => $model->id], [
        'class' => 'btn btn-primary', 'style' => 'margin-left: 10px;']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger', 'style' => 'margin-left: 10px;',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]) ?>
    <?= Html::a('Cancel', Url::previous(), ['class' => 'btn btn-default', 'style' => 'margin-left: 10px;']) ?>

    <br/><br/>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'title',
                'format' => 'raw',
            ],
            [
                'attribute' => 'content',
                'format' => 'raw',
            ],
            'cover_image',
            'cover_video',
            'author',
            'comment_count',
            'ordering',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatus();
                }
            ],
            'created_at:date',
        ],
    ]) ?>

</div>
