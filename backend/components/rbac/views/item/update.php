<?php
/**
 * @var $this yii\web\View
 * @var $model \yii2mod\rbac\models\AuthItemModel
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$context = $this->context;
$labels = $this->context->getLabels();
$this->title = $model->isNewRecord ? 'Create ' . $labels['Item'] : 'Update ' . $labels['Item'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('yii2mod.rbac', 'Update');
$this->render('/layouts/_sidebar');

$ruleListData = ArrayHelper::map(Yii::$app->authManager->rules, 'name', 'name');
?>


<div class="auth-item-update">
    <h1><?php echo Html::encode($this->title); ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div>
        <?php // Hidden field to store user.id + display error messages  ?>
        <?= $form->field($model, 'type')->textInput(['style' => 'display: none'])->label(false) ?>
    </div>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]); ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 2]); ?>
    <?= $form->field($model, 'ruleName')->dropDownList($ruleListData, ['prompt' => 'Select Rule']); ?>

    <div class="form-group text-center" style="margin-top:25px;">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'style' => 'width: 120px;']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
