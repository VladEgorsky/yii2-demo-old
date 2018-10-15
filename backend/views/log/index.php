<?php
/**
 * @var $this yii\web\View
 * @var $searchModel backend\models\search\LogSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $model backend\models\Log
 */

use backend\components\Y;
use backend\models\Log;
use backend\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\Pjax;

$this->title = 'Logs';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="log-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['id' => 'grid_log_pjax']); ?>
    <?= GridView::widget([
        'id' => 'grid_log',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'id', 'label' => 'ID', 'filter' => false],
            [
                'attribute' => 'created_at',
                'filter' => false,
                'options' => [
                    'style' => 'width: 100px;',
                ],
                'value' => function ($model) {
                    return Y::getIntegerAsDate($model->created_at, '', 'php:d.m.y H:i');
                }
            ],
            [
                'attribute' => 'model_class',
                'filter' => Log::getModelNamesForLogging(),
                'format' => 'raw',
                'options' => [
                    'style' => 'width: 150px;',
                ],
                'value' => function ($model) {
                    $arr = explode('\\', $model->model_class);
                    return $arr[count($arr) - 1] . '<br />' . $model->action;
                }
            ],
            [
                'attribute' => 'action',
                'filter' => Log::getActions(),
                'format' => 'raw',
                'label' => 'Values',
                'value' => function ($model) {
                    $values = JSON::decode($model->values);
                    return implode('<br />', $values);
                }
            ],
            [
                'attribute' => 'user_id',
                'filter' => User::getListData(),
                'format' => 'raw',
                'options' => [
                    'style' => 'width: 200px;',
                ],
                'value' => function ($model) {
                    return $model->user->getFullName() . '<br />' . long2ip($model->ip);
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
