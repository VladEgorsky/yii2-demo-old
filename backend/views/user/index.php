<?php
/**
 * @var $this yii\web\View
 * @var $searchModel backend\models\search\UserSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $userRoles array    User::getRolesGroupedByUserId();
 */

use backend\components\Y;
use backend\models\User;
use lo\widgets\modal\ModalAjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\rbac\Item;
use yii\widgets\Pjax;

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>


    <div class="user-index">
        <h1><?= Html::encode($this->title) ?></h1>

        <div>
            <?= ModalAjax::widget([
                'id' => 'createUser',
                'header' => 'Create User',
                'toggleButton' => [
                    'label' => 'New User',
                    'class' => 'btn btn-primary pull-right',
                    'style' => 'width: 120px;',
                ],
                'url' => Url::to(['/user/ajax-update']), // Ajax view with form to load
                'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
                //'size' => ModalAjax::SIZE_LARGE,
                'options' => ['class' => 'header-primary'],
                'autoClose' => true,
                'pjaxContainer' => '#grid_user_pjax',
            ]) ?>
        </div>


        <?= ModalAjax::widget([
            'id' => 'updateUser',
            'selector' => 'a.btn_update', // all buttons in grid view with href attribute
            'options' => ['class' => 'header-primary'],
            'pjaxContainer' => '#grid_user_pjax',
        ]) ?>

        <?php Pjax::begin(['id' => 'grid_user_pjax']); ?>
        <?= GridView::widget([
            'id' => 'grid_user',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function ($model, $key, $index, $grid) {
                return ['data-name' => $model->fullName];
            },
            'tableOptions' => [
                'class' => 'table table-striped table-bordered',
                'data-delete_url' => Url::to(['/user/ajax-delete']),
            ],
            'columns' => [
                [
                    'attribute' => 'id',
                    'label' => 'ID',
                ],
                [
                    'attribute' => 'username',
                    'label' => 'Name',
                ],
                [
                    'attribute' => 'role',
                    'format' => 'raw',
                    'filter' => User::getRolesListData(Item::TYPE_ROLE),
                    'label' => 'Roles',
                    'value' => function ($model) use ($userRoles) {
                        return isset($userRoles[$model->id])
                            ? implode('<br />', $userRoles[$model->id]) : '#N/A';
                    }
                ],
                'email',
                [
                    'attribute' => 'email_verified',
                    'format' => 'raw',
                    'filter' => [0 => 'No', 1 => 'Yes'],
                    'label' => 'Verified',
                    'value' => function ($model) {
                        return Y::getBoolValueAsCheckboxIcon($model->email_verified);
                    }
                ],
                [
                    'attribute' => 'status',
                    'filter' => $searchModel->getStatusListData(),
                    'value' => function ($model) {
                        return $model->getStatus();
                    }
                ],
                [
                    'attribute' => 'phone',
                    'filter' => false,
                ],
                [
                    'attribute' => 'lastVisit',
                    'filter' => false,
                    'value' => function ($model) {
                        return Y::getIntegerAsDate($model->lastvisit_at, '---', 'php:d.m.y');
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{assign} &nbsp; {update} &nbsp; {delete}',
                    'buttons' => [
                        'assign' => function ($url, $model, $key) {
                            $url = Url::to(['/rbac/assignment/view', 'id' => $model->id]);
                            return Html::a('<span class="glyphicon glyphicon-transfer"> </span>', $url,
                                ['class' => 'btn btn-default btn-xs', 'data-pjax' => 0, 'title' => 'Assignments']);
                        },
                        'update' => function ($url, $model, $key) {
                            $url = Url::to(['/user/ajax-update', 'id' => $model->id]);
                            return Html::a('<span class="glyphicon glyphicon-pencil"> </span>', $url,
                                ['class' => 'btn btn-default btn-xs btn_update', 'data-pjax' => 0, 'title' => 'Update']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::button('<span class="glyphicon glyphicon-trash"> </span>', [
                                'type' => 'button', 'class' => 'btn btn-default btn-xs btn_delete', 'title' => 'Delete',
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
    $("#updateUser, #createUser").on("kbModalSubmit", function(event, response, status, xhr, selector) {
        if (response === "ok") {
            alert("Successfully saved");
            $(this).modal("hide");
            $.pjax.reload({container: "#grid_user_pjax", timeout: 10000});
        }
    });

    // Delete button inside the grid
    $(document).on("click", "#grid_user .btn_delete", function () {
        var tr = $(this).closest("tr");
        if (!confirm("Do you want to delete user \\r\\n" + tr.data("name") + "?")) {
            return false;
        }

        var url = $(this).closest("table").data("delete_url");
        var data = {id: tr.data("key")};
        main.makeAjaxRequest(url, data, {pjaxSelector: "#grid_user_pjax"});
    });
JS;

$this->registerJs($userUpdateJs);
