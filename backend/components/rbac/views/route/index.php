<?php
/**
 * @var $this yii\web\View
 * @var $routes array
 */

use yii\helpers\Html;
use yii\helpers\Json;
use yii2mod\rbac\RbacRouteAsset;

$this->title = Yii::t('yii2mod.rbac', 'Routes');
$this->params['breadcrumbs'][] = $this->title;
$this->render('/layouts/_sidebar');

RbacRouteAsset::register($this);
?>

<h1><?= Html::encode($this->title); ?></h1>

<div class="row">
    <div class="col-lg-5">
        <?= Html::a(Yii::t('yii2mod.rbac', 'Refresh'), ['refresh'], [
            'class' => 'btn btn-primary', 'id' => 'btn-refresh',
        ]); ?>
    </div>

    <div class="col-lg-5 col-lg-offset-2">
        <?php if (Yii::$app->controller->route == 'rbac/route/index') {
            echo 'Selected Routes can be assigned to Roles or to Permissions later';
        } ?>
    </div>
</div>

<?= $this->render('../_dualListBox', [
    'opts' => Json::htmlEncode([
        'items' => $routes,
    ]),
    'assignUrl' => ['assign'],
    'removeUrl' => ['remove'],
]); ?>
