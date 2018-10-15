<?php
/**
 * @var $this yii\web\View
 * @var $model backend\models\User
 */

use backend\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<div class="user-update">
    <?php $form = ActiveForm::begin(); ?>

    <div>
        <?php // Hidden field to store user.id + display error messages  ?>
        <?= $form->field($model, 'id')->textInput(['style' => 'display: none'])->label(false) ?>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-6">
            <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'email')->textInput(['type' => 'email', 'maxlength' => true]) ?>
        </div>

        <div class="col-xs-6" style="padding-top: 20px;">
            <?= $form->field($model, 'email_verified')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-6">
            <?= $form->field($model, 'status')->dropDownList(User::getStatusListData()) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6 text-danger text-center">
            <?= $model->isNewRecord ? 'Password will be set as "1"' : ''; ?>
        </div>

        <div class="col-xs-6 text-center">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100px']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
