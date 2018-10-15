<?php

use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var $searchModel \common\modules\seo\models\SeoSearch
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'SEO';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box">
    <div class="box-body">
        <p>
            <?= Html::a('Create', ['create'], ['class' => 'btn btn-xs btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'external_link',
                'title',
                'description',
                'keywords',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}'
                ],
            ],
        ]); ?>
    </div>
</div>