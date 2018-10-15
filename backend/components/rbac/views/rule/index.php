<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider \yii\data\ArrayDataProvider
 * @var $searchModel yii2mod\rbac\models\search\BizRuleSearch
 */

use lo\widgets\modal\ModalAjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = Yii::t('yii2mod.rbac', 'Rules');
$this->params['breadcrumbs'][] = $this->title;
$this->render('/layouts/_sidebar');
?>

    <div class="rule-index">
        <h1><?php echo Html::encode($this->title); ?></h1>

        <div>
            <?= ModalAjax::widget([
                'id' => 'createRule',
                'header' => 'Create Rule',
                'toggleButton' => [
                    'label' => 'New Rule',
                    'class' => 'btn btn-primary pull-right',
                    'style' => 'width: 120px;',
                ],
                'url' => Url::to(['/rbac/rule/ajax-update']), // Ajax view with form to load
                'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
                //'size' => ModalAjax::SIZE_LARGE,
                'options' => ['class' => 'header-primary'],
                'autoClose' => true,
                'pjaxContainer' => '#grid_rule_pjax',
            ]) ?>
        </div>


        <?= ModalAjax::widget([
            'id' => 'updateRule',
            'selector' => 'a.btn', // all buttons in grid view with href attribute
            'options' => ['class' => 'header-primary'],
            'pjaxContainer' => '#grid_rule_pjax',
        ]) ?>


        <?php Pjax::begin(['id' => 'grid_rule_pjax', 'timeout' => 5000]); ?>
        <?= GridView::widget([
            'id' => 'grid_rule',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function ($model, $key, $index, $grid) {
                return ['data-name' => $model->name];
            },
            'tableOptions' => [
                'class' => 'table table-striped table-bordered',
                'data-delete_url' => Url::to(['/rbac/rule/ajax-delete']),
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'name',
                    'label' => Yii::t('yii2mod.rbac', 'Name'),
                    'filterInputOptions' => empty($dataProvider->models)
                        ? ['class' => 'form-control', 'id' => null, 'disabled' => 'disabled']
                        : ['class' => 'form-control', 'id' => null]
                ],
                [
                    'header' => Yii::t('yii2mod.rbac', 'Action'),
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {update} &nbsp; {delete} &nbsp; ',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            $url = str_replace('update', 'ajax-update', $url);
                            return Html::a('<span class="glyphicon glyphicon-pencil"> </span>', $url,
                                ['class' => 'btn btn-default btn-xs', 'data-pjax' => 0, 'title' => 'Update']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::button('<span class="glyphicon glyphicon-trash"> </span>', [
                                'type' => 'button', 'class' => 'btn btn-default btn-xs btn_delete', 'title' => 'Delete'
                            ]);
                        },
                    ],
                ],
            ],
        ]) ?>
        <?php Pjax::end(); ?>
    </div>

<?php
$ruleUpdateJs = <<< JS
    $("#updateRule, #createRule").on("kbModalSubmit", function(event, response, status, xhr, selector) {
        if (response === "ok") {
            alert("Successfully saved");
            $(this).modal("hide");
            $.pjax.reload({container: "#grid_rule_pjax", timeout: 10000});
        }
    });

    // Delete button inside the grid
    $(document).on("click", "#grid_rule .btn_delete", function () {
        var tr = $(this).closest("tr");
        if (!confirm("Do you want to delete rule \\r\\n" + tr.data("name") + "?")) {
            return false;
        }

        var url = $(this).closest("table").data("delete_url");
        var data = {id: tr.data("name")};
        main.makeAjaxRequest(url, data, {pjaxSelector: "#grid_rule_pjax"});
    });
JS;

$this->registerJs($ruleUpdateJs);
