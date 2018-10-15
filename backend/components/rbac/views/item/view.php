<?php
/**
 * @var $this yii\web\View
 * @var $model \yii2mod\rbac\models\AuthItemModel
 */

use yii\helpers\Html;
use yii\helpers\Json;
use yii2mod\rbac\RbacAsset;

$labels = $this->context->getLabels();
$this->title = Yii::t('yii2mod.rbac', $labels['Item'] . ' : {0}', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
$this->render('/layouts/_sidebar');

RbacAsset::register($this);
?>


<div class="auth-item-view">
    <h1><?php echo Html::encode($this->title); ?></h1>

    <?= $this->render('../_dualListBox', [
        'opts' => Json::htmlEncode([
            'items' => $model->getItems(),
        ]),
        'assignUrl' => ['assign', 'id' => $model->name],
        'removeUrl' => ['remove', 'id' => $model->name],
    ]); ?>
</div>
