<?php
/*
 * @var $this yii\web\View
 * @var $searchModel backend\models\search\TemplateSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use backend\components\Y;
use backend\models\Template;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Templates';
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="template-index">
        <h1><?= Html::encode($this->title) ?></h1>

        <div style="border: 1px solid #AAA; padding: 5px 15px">
            <label>Create new Template</label>
            <?= Html::a('For Main page upper block', [
                'create', 'scenario' => Template::SCENARIO_MAINSECTION, 'isMainPageUpperBlock' => true], [
                'class' => 'btn btn-success', 'style' => 'width: 180px; margin-left: 15px;']) ?>
            <?= Html::a('For Main page Section blocks', ['create', 'scenario' => Template::SCENARIO_MAINSECTION], [
                'class' => 'btn btn-success', 'style' => 'width: 180px; margin-left: 15px;']) ?>
            <?= Html::a('For Sections or Tags pages', ['create'], [
                'class' => 'btn btn-success', 'style' => 'width: 180px; margin-left: 15px;']) ?>
        </div>

        <?php Pjax::begin(['id' => 'grid_template_pjax']); ?>
        <?= GridView::widget([
            'id' => 'grid_template',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => [
                'class' => 'table table-striped table-bordered',
                'data-delete_url' => Url::to(['/template/ajax-delete']),
            ],
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                [
                    'attribute' => 'id',
                    'label' => 'ID',
                    'filter' => false,
                    'options' => [
                        'style' => 'width: 70px;'
                    ],
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'filter' => Template::getStatusListData(),
                    'label' => 'Status',
                    'options' => [
                        'style' => 'width: 100px;'
                    ],
                    'value' => function ($model) {
                        return Y::getBoolValueAsCheckboxIcon($model->status);
                    }
                ],
                [
                    'label' => 'Locations',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /** @var $model backend\models\Template */
                        return $model->getLocationNames();
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} &nbsp; {copy} &nbsp; {delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"> </span>', $url,
                                ['class' => 'btn btn-default btn-xs btn_update', 'data-pjax' => 0, 'title' => 'Update']);
                        },
//                        'copy' => function ($url, $model, $key) {
//                            $url = Url::to(['/template/update', 'id' => $model->id, 'copy' => true]);
//                            return Html::a('<span class="glyphicon glyphicon-duplicate"> </span>', $url,
//                                ['class' => 'btn btn-default btn-xs btn_update', 'data-pjax' => 0, 'title' => 'Copy']);
//                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::button('<span class="glyphicon glyphicon-trash"> </span>', [
                                'type' => 'button', 'class' => 'btn btn-default btn-xs btn_delete', 'title' => 'Delete'
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>

<?php
// See js/controller/template.js & css/controller/template.css files
