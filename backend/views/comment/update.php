<?php
/* @var $this yii\web\View */
/* @var $model backend\models\Comment */

use backend\components\Y;
use backend\models\Comment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$title = $model->isNewRecord ? 'Create New Comment' : 'Update Comment'
?>

<div class="comment-update">
    <h3><?= $title ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <?php // Hidden field to display other error messages except validation errors  ?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'status')->dropDownList(Comment::getStatusListData()) ?>
    <?= Y::getTinyMceWidget($form, $model, 'comment'); ?>

    <div class="form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', Url::previous(), ['class' => 'btn btn-default', 'style' => 'margin-left: 10px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
