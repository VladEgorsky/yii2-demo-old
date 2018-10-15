<?php

use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var $model \common\modules\seo\models\Seo
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="seo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'keywords')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'external_link')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'internal_link')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'model_id')->textInput() ?>
    <?= $form->field($model, 'model_name')->textInput(['maxlength' => 255]) ?>

    <?= \yii\helpers\Html::submitButton('Save') ?>

    <?php ActiveForm::end(); ?>

</div>
