<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Subscribe */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscribe-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\Subscribe::getStatusListData()) ?>

    <?= $form->field($model, 'period')->dropDownList(\common\models\Subscribe::getPeriodListData()) ?>

    <?= $form->field($model, 'sectionList')->widget(Select2::class, [
        'data'          => \yii\helpers\ArrayHelper::map(\backend\models\Section::find()->all(), 'id', 'title'),
        'options'       => ['placeholder' => 'Search', 'multiple' => true],
        'pluginOptions' => [
            'tokenSeparators' => [','],
            'allowClear'      => true,
        ],
    ])->label('Sections') ?>

    <?= $form->field($model, 'tagList')->widget(Select2::class, [
        'data'          => \yii\helpers\ArrayHelper::map(\backend\models\Tag::find()->all(), 'id', 'title'),
        'options'       => ['placeholder' => 'Search', 'multiple' => true],
        'pluginOptions' => [
            'tokenSeparators' => [','],
            'allowClear'      => true,
        ],
        'readonly'      => true,
    ])->label('Tags') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
