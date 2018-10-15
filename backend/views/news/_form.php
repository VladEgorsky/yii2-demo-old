<?php

use backend\components\Y;
use kartik\select2\Select2;
use mihaildev\elfinder\InputFile;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\News */
/* @var $form yii\widgets\ActiveForm */


?>

    <div class="news-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= \common\modules\seo\widgets\SeoWidget::widget(['model' => $model]) ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= Y::getTinyMceWidget($form, $model, 'short_content', ['rows' => 7]); ?>


        <?= Html::a('How to Embed Videos - CKEditor',
            'https://harcs-it.ucdavis.edu/how-embed-videos-ckeditor',
            ['target' => '_blank'])
        ?>
        <?= Y::getTinyMceWidget($form, $model, 'content'); ?>

        <div class="image-input-container form-group">
            <?= $form->field($model, 'cover_image',
                ['template' => '{label}{input}', 'options' => ['class' => '']])->hiddenInput() ?>

            <img src="<?php if ($model->isNewRecord || !$model->cover_image) { ?>/no-image<?php } else {
                echo $model->cover_image;
            } ?>" alt="" width="200" style="margin-bottom: 15px" class="selected-image">
            <?php
            $buttonsTemplate = '<div class="flex-row">{button}<span style="display: none">{input}</span>';

            if ($model->cover_image) {
                $buttonsTemplate .= ' &nbsp; <a href="#" class="btn btn-default remove-image">Delete</a>';
            }
            $buttonsTemplate .= '</div>';
            ?>
            <?= InputFile::widget([
                'buttonTag' => 'a',
                'buttonName' => 'Select photo',
                'controller' => 'elfinder',
                'filter' => 'image',
                'name' => 'cover_image',
                'value' => '',
                'template' => $buttonsTemplate,
                'options' => ['class' => 'form-control image-select'],
                'buttonOptions' => ['class' => 'btn btn-default'],
                'multiple' => false
            ]);
            ?>
        </div>
        <?= $form->field($model, 'cover_video')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sectionList')->widget(Select2::class, [
            'data' => \yii\helpers\ArrayHelper::map(\backend\models\Section::find()->all(), 'id', 'title'),
            'options' => ['placeholder' => 'Search', 'multiple' => true],
            'pluginOptions' => [
                'tokenSeparators' => [','],
                'allowClear' => true,
            ],
        ])->label('Sections') ?>

        <?= $form->field($model, 'tagList')->widget(Select2::class, [
            'data' => $model->isNewRecord ? [] : \backend\models\Tag::getListBySections($model->sections, true),
            'options' => ['placeholder' => 'Search', 'multiple' => true],
            'pluginOptions' => [
                'tokenSeparators' => [','],
                'allowClear' => true,
            ],
            'readonly' => true,
        ])->label('Tags') ?>

        <?= $form->field($model, 'ordering')->textInput() ?>

        <?= $form->field($model, 'status')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$this->registerJs(
    <<<JS
    
$(document).on('change', '#news-sectionlist', function(e) {
    let val = $(this).val();
    let _selectContainer = $('#news-taglist');
    let selectedVals = _selectContainer.val();
    let selected = '';
    _selectContainer.html('');
    
    $.post('/tag/get-list', {'section': val}, function(result) {
        if(result)
        {
            $.each(result, function(index, value) {
                $.each(selectedVals, function(i,v) {
                    if(v == value.id){
                        selected = 'selected';
                        return false;
                    } else {
                        selected = '';
                    }
                });
                    
              _selectContainer.append('<option '+selected+' value="'+value.id+'">'+value.title+'</option>');
            });
            $('#news-taglist').trigger('change.select2');
        }
    });
});

$(document).on('change', '.image-input-container .image-select', function () {
    let val = $(this).val();
    let _parent = $(this).parents('.image-input-container');
    $('.selected-image', _parent).attr('src', val);
    $('input[type=hidden]', _parent).val(val);
    $('.remove-image', _parent).show();
});

$(document).on('click', '.image-input-container a.remove-image', function () {
    let _parent = $(this).parents('.image-input-container');
    $('.selected-image', _parent).attr('src', 'no-image.jpg');
    $('input[type=hidden]', _parent).val('');
    $(this).hide();
    return false;
});
JS

)
?>