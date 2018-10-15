<?php
/**
 * @var $this yii\web\View
 * @var $searchModel \yii2mod\rbac\models\search\AuthItemSearch
 */

use lo\widgets\modal\ModalAjax;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\rbac\Item;
use yii\widgets\Pjax;

$labels = $this->context->getLabels();
$this->title = Yii::t('yii2mod.rbac', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;
$this->render('/layouts/_sidebar');

$updateUrl = ($searchModel->type === Item::TYPE_ROLE)
    ? Url::to(['/rbac/role/ajax-update'])
    : Url::to(['/rbac/permission/ajax-update']);
$deleteUrl = ($searchModel->type === Item::TYPE_ROLE)
    ? Url::to(['/rbac/role/ajax-delete'])
    : Url::to(['/rbac/permission/ajax-delete']);

$createButtonHeader = 'New Item';
if (Yii::$app->controller->id == 'role') {
    $createButtonHeader = 'Create Role';
} elseif (Yii::$app->controller->id == 'permission') {
    $createButtonHeader = 'Create Permission';
}
?>


    <div class="item-index">
        <h1><?php echo Html::encode($this->title); ?></h1>

        <div>
            <?= ModalAjax::widget([
                'id' => 'createItem',
                'header' => $createButtonHeader,
                'toggleButton' => [
                    'label' => $createButtonHeader,
                    'class' => 'btn btn-primary pull-right',
                    'style' => 'width: 140px;',
                ],
                'url' => $updateUrl, // Ajax view with form to load
                'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
                //'size' => ModalAjax::SIZE_LARGE,
                'options' => ['class' => 'header-primary'],
                'autoClose' => true,
                'pjaxContainer' => '#grid_item_pjax',
            ]) ?>
        </div>


        <?= ModalAjax::widget([
            'id' => 'updateItem',
            'selector' => 'a.btn_update', // all buttons in grid view with href attribute
            'options' => ['class' => 'header-primary'],
            'pjaxContainer' => '#grid_item_pjax',
        ]) ?>

        <?php Pjax::begin(['id' => 'grid_item_pjax', 'timeout' => 5000, 'enablePushState' => false]); ?>
        <?= GridView::widget([
            'id' => 'grid_item',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function ($model, $key, $index, $grid) {
                return ['data-name' => $model->name];
            },
            'tableOptions' => [
                'class' => 'table table-striped table-bordered',
                'data-delete_url' => $deleteUrl,
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'name',
                    'label' => Yii::t('yii2mod.rbac', 'Name'),
                ],
                [
                    'attribute' => 'ruleName',
                    'label' => Yii::t('yii2mod.rbac', 'Rule Name'),
                    'filter' => ArrayHelper::map(Yii::$app->getAuthManager()->getRules(), 'name', 'name'),
                    'filterInputOptions' => ['class' => 'form-control', 'prompt' => Yii::t('yii2mod.rbac', 'Select Rule')],
                ],
                [
                    'attribute' => 'description',
                    'format' => 'ntext',
                    'label' => Yii::t('yii2mod.rbac', 'Description'),
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} &nbsp; {update} &nbsp; {delete}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-transfer"> </span>', $url,
                                ['class' => 'btn btn-default btn-xs', 'data-pjax' => 0, 'title' => 'Assignments']);
                        },
                        'update' => function ($url, $model, $key) {
                            $url = str_replace('update', 'ajax-update', $url);
                            return Html::a('<span class="glyphicon glyphicon-pencil"> </span>', $url,
                                ['class' => 'btn btn-default btn-xs btn_update', 'data-pjax' => 0, 'title' => 'Update']);
                        },
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
$userUpdateJs = <<< JS
    $("#updateItem, #createItem").on("kbModalSubmit", function(event, response, status, xhr, selector) {
        if (response === "ok") {
            alert("Successfully saved");
            $(this).modal("hide");
            $.pjax.reload({container: "#grid_item_pjax", timeout: 10000});
        }
    });

    // Delete button inside the grid
    $(document).on("click", "#grid_item .btn_delete", function () {
        var name = $(this).closest("tr").data("name");
        if (!confirm("Do you want to delete item \\r\\n" + name + "?")) {
            return false;
        }

        var url = $(this).closest("table").data("delete_url");
        var data = {id: name};
        main.makeAjaxRequest(url, data, {pjaxSelector: "#grid_item_pjax"});
    });
JS;

$this->registerJs($userUpdateJs);
