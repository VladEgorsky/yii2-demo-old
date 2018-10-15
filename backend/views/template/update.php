<?php
/**
 * @var $this yii\web\View
 * @var $model backend\models\Template
 * @var $isMainPageUpperBlock bool
 */

use backend\models\Template;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\JuiAsset;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = ($model->isNewRecord) ? 'Create Template' : 'Update Template';
$isAdvert = ($model->scenario == Template::SCENARIO_ADVERT);
//$gridContainerClass = $isAdvert
//    ? 'col-md-2 col-md-offset-5 col-xs-12' : 'col-md-6 col-md-offset-3 col-xs-12'
$templateZoneWidth = $isMainPageUpperBlock ? '620px' : '450px';
?>

    <div class="template-update">

        <h1><?= Html::encode($this->title) ?></h1>

        <?php Pjax::begin(['id' => 'template_form_pjax']) ?>
        <?php $form = ActiveForm::begin(['id' => 'template_form']); ?>
        <?= $form->field($model, 'items_amount')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'items_classes')->hiddenInput()->label(false) ?>

        <div class="row" style="padding-bottom: 10px;">
            <?= $this->render('update/dropdowns', [
                'model' => $model, 'form' => $form, 'isMainPageUpperBlock' => $isMainPageUpperBlock])
            ?>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-xs-12">
                <h2 style="display:inline"> Template zone </h2>
                <?php
                echo Html::a('Cancel', Url::to(['/template/index']), [
                    'class' => 'btn btn-default pull-right', 'style' => 'width: 120px;', 'data-pjax' => 0]);
                echo Html::button('Save', [
                    'type' => 'button', 'class' => 'btn btn-success pull-right',
                    'id' => 'template_form_submit_button', 'style' => 'width: 120px; margin-right: 15px;'
                ]);

                // For validation errors output
                echo $form->field($model, 'data')->hiddenInput()->label(false);
                ?>
            </div>

            <div style="width: <?= $templateZoneWidth ?>; margin: 50px auto;">
                <div class="grid grid_template">
                    <?= $model->getHtmlFromData() ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
        <?php Pjax::end() ?>

        <div class="row">
            <div class="text-center">
                <h2> Available Items </h2>
                Click Plus button to add Items to template
            </div>

            <div style="width: <?= $templateZoneWidth ?>; margin: 10px auto;">
                <div class="grid grid_source">

                    <?php
                    if ($isAdvert) {
                        echo $this->render('update/advert_source_grid', ['model' => $model]);
                    } else {
                        echo $this->render('update/news_source_grid', [
                            'model' => $model, 'isMainPageUpperBlock' => $isMainPageUpperBlock]);
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>

<?php
// Styles and scripts are in /css/template.css & /js/template.js
$this->registerJsFile('/js/packery.pkgd.min.js', ['depends' => JuiAsset::class]);
