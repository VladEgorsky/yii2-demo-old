<?php
/* @var $this yii\web\View */
/* @var $model backend\models\Advertise */

use backend\components\Y;
use backend\models\Advertise;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$title = $model->isNewRecord ? 'Create New Advertise' : 'Update Advertise'
?>

<div class="advertise-update">
    <h3><?= $title ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <?php // Hidden field to display other error messages except validation errors  ?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <div class="row">
        <?= $form->field($model, 'name', ['options' => ['class' => 'col-md-4']])
            ->label('Author')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'email', ['options' => ['class' => 'col-md-5']])
            ->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'status', ['options' => ['class' => 'col-md-3']])
            ->dropDownList(Advertise::getStatusListData()) ?>
    </div>


    <?= Y::getTinyMceWidget($form, $model, 'content'); ?>

    <div class="form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', Url::previous(), ['class' => 'btn btn-default', 'style' => 'margin-left: 10px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
