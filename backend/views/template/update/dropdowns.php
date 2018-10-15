<?php
/**
 * @var $this yii\web\View
 * @var $model backend\models\Template
 * @var $form yii\widgets\ActiveForm
 * @var $isMainPageUpperBlock bool
 */

use backend\models\Section;
use backend\models\Tag;
use backend\models\Template;
use common\models\Advert;
use kartik\select2\Select2;
use yii\helpers\Html;

?>

<?php if ($isMainPageUpperBlock) : ?>

    <div class="col-md-6 col-md-offset-3 col-xs-12">
        <?= Html::activeDropDownList($model, 'mainSectionIds[]', [
            Template::LOCATION_MAIN_UPPERBLOCK_ID => 'Mainpage Upper Block'
        ], ['class' => 'form-control']) ?>
    </div>

<?php elseif ($model->scenario == Template::SCENARIO_MAINSECTION) : ?>

    <div class="col-md-6 col-md-offset-3 col-xs-12">
        <?= $form->field($model, 'mainSectionIds')->widget(Select2::class, [
            'data' => Template::getMainsectionListData(),
            'options' => ['placeholder' => 'Select block(s)', 'multiple' => true],
            'pluginOptions' => [
                'tokenSeparators' => [','],
                'allowClear' => true,
            ],
        ])->label('Mainpage Sections') ?>
    </div>

<?php else: ?>

    <div class="col-md-6 col-md-offset-3 col-xs-12">
        <?= $form->field($model, 'sectionIds')->widget(Select2::class, [
            'data' => Section::getListData(['status' => Section::STATUS_VISIBLE], ['ordering' => SORT_ASC]),
            'options' => ['placeholder' => 'Search', 'multiple' => true],
            'pluginOptions' => [
                'tokenSeparators' => [','],
                'allowClear' => true,
            ],
        ])->label('Sections') ?>
    </div>

    <div class="col-md-6 col-md-offset-3 col-xs-12">
        <?= $form->field($model, 'tagIds')->widget(Select2::class, [
            'data' => Tag::getListData(['status' => Tag::STATUS_VISIBLE], ['ordering' => SORT_ASC]),
            'options' => ['placeholder' => 'Search', 'multiple' => true],
            'pluginOptions' => [
                'tokenSeparators' => [','],
                'allowClear' => true,
            ],
            'readonly' => true,
        ])->label('Tags') ?>
    </div>
<?php endif; ?>

<?php if ($model->scenario == Template::SCENARIO_ADVERT) : ?>
    <div class="col-md-6 col-md-offset-3 col-xs-12">

        <?= $form->field($model, 'advertKeys')->widget(Select2::class, [
            'data' => Advert::getListData(),
            'options' => ['placeholder' => 'Search', 'multiple' => true],
            'pluginOptions' => [
                'tokenSeparators' => [','],
                'allowClear' => true,
            ],
        ])->label('Advertisements') ?>

    </div>
<?php endif; ?>
