<?php
/**
 * @var $this yii\web\View
 * @var $model backend\models\Section
 */

use backend\components\Y;
use backend\models\StaticPage;
use dominus77\tinymce\components\MihaildevElFinder;
use dominus77\tinymce\TinyMce;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord
    ? 'Create New Page'
    : 'Update Page: ' . $model->title;
?>


<div class="staticpage-update">
    <h3><?= Html::encode($this->title) ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <?= \common\modules\seo\widgets\SeoWidget::widget(['model' => $model]) ?>

    <?php // Hidden field to display other error messages except validation errors  ?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <div class="row" style="padding:10px 0;">
        <div class="col-md-8">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList(StaticPage::getStatusListData()) ?>
        </div>

        <div class="col-xs-12">
            <?= Y::getTinyMceWidget($form, $model, 'content'); ?>
        </div>
    </div>

    <div class="form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', Url::previous(), ['class' => 'btn btn-default', 'style' => 'margin-left: 10px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
