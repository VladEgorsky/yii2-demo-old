<?php
/**
 * @var $this yii\web\View
 * @var $model backend\models\Tag
 */

use backend\models\Section;
use backend\models\Tag;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord
    ? 'Create New Tag'
    : 'Update Tag: ' . $model->title;
?>


<div class="tag-update">
    <h3><?= Html::encode($this->title) ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <?= \common\modules\seo\widgets\SeoWidget::widget(['model' => $model]) ?>

    <?php // Hidden field to display other error messages except validation errors  ?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList(Tag::getStatusListData()) ?>
        </div>

        <div class="col-xs-12">
            <?= $form->field($model, 'sectionIds')->widget(Select2::class, [
                'data' => Section::getListData(['status' => Section::STATUS_VISIBLE], ['ordering' => SORT_ASC]),
                'options' => ['placeholder' => 'Search', 'multiple' => true],
                'pluginOptions' => [
                    'tokenSeparators' => [','],
                    'allowClear' => true,
                ],
            ])->label('Sections') ?>
        </div>
    </div>

    <div class="form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Cancel', Url::previous(), ['class' => 'btn btn-default', 'style' => 'margin-left: 10px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
