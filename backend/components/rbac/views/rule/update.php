<?php
/**
 * @var $this yii\web\View
 * @var $model \yii2mod\rbac\models\BizRuleModel
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Create New Rule'
    : Yii::t('yii2mod.rbac', 'Update Rule : {0}', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('yii2mod.rbac', 'Update');
$this->render('/layouts/_sidebar');
?>

<div class="rule-item-update">
    <h1><?php echo Html::encode($this->title); ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div>
        <?php // Hidden field to store user.id + display error messages  ?>
        <?= $form->field($model, 'createdAt')->textInput(['style' => 'display: none'])->label(false) ?>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 64]); ?>
        </div>

        <div class="col-xs-6">
            <?= $form->field($model, 'className')->textInput(); ?>
        </div>
    </div>

    <div class="text-center">
        <?= Html::submitButton('Save', [
            $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'width:100px'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>