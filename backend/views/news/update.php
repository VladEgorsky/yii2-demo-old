<?php
/* @var $this yii\web\View */
/* @var $model backend\models\News */

use backend\components\Y;
use backend\models\Section;
use kartik\select2\Select2;
use mihaildev\elfinder\InputFile;
use sadovojav\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

if ($model->isNewRecord) {
    $this->title = 'Create News';
    $this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
} else {
    $this->blocks['content_header'] = 'Update News';
    $this->title = 'Update News: ' . $model->title;
    $this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = 'Update';
}
?>

<div class="news-update">
    <h3><?= Html::encode($this->title) ?></h3>


    <?php $form = ActiveForm::begin(); ?>

    <?= \common\modules\seo\widgets\SeoWidget::widget(['model' => $model]) ?>

    <?php // Hidden field to display other error messages except validation errors  ?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= Y::getTinyMceWidget($form, $model, 'short_content', ['rows' => 7]) ?>

    <?= Html::a('How to Embed Videos - CKEditor',
        'https://harcs-it.ucdavis.edu/how-embed-videos-ckeditor',
        ['target' => '_blank'])
    ?>

    <?= Y::getTinyMceWidget($form, $model, 'content') ?>

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

    <?= $form->field($model, 'sectionList')->widget(Select2::class, [
        'data' => Section::getListData(['status' => Section::STATUS_VISIBLE], ['ordering' => SORT_ASC]),
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

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'ordering')->textInput() ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList($model->getStatusListData()) ?>
        </div>
    </div>

    <div class="form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', Url::previous(), ['class' => 'btn btn-default', 'style' => 'margin-left: 10px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
