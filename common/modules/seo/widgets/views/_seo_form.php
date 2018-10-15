<?php
/**
 * @var $seo \common\modules\seo\models\Seo
 */

use yii\helpers\Html;

$data = $model->getBehavior('seo');
$path = method_exists($data, 'view_category') ? $data->view_category : false;
?>
<div class="form-group  col-md-12">
    <?= Html::activeLabel($seo, 'title', ['class' => 'control-label']) ?>
    <?= Html::activeTextInput($seo, 'title', ['class' => 'form-control']) ?>
</div>

<div class="form-group col-md-6">
    <?= Html::activeLabel($seo, 'description', ['class' => 'control-label']) ?>
    <?= Html::activeTextInput($seo, 'description', ['class' => 'form-control']) ?>
</div>

<div class="form-group col-md-6">
    <?= Html::activeLabel($seo, 'keywords', ['class' => 'control-label']) ?>
    <?= Html::activeTextInput($seo, 'keywords', ['class' => 'form-control']) ?>
</div>

<div class="form-group col-md-12">
    <?= Html::activeLabel($seo, 'external_link', ['class' => 'control-label']) ?>
    <div class="input-group">
        <span class="input-group-addon">http://<?= $_SERVER['HTTP_HOST'] ?>/</span>
        <?= Html::activeTextInput($seo, 'external_link', ['class' => 'form-control']) ?>
    </div>
</div>


<div class="form-group col-md-3">
    <?= Html::activeCheckbox($seo, 'noindex') ?>
</div>

<div class="form-group col-md-3">
    <?= Html::activeCheckbox($seo, 'nofollow') ?>
</div>

<div class="form-group col-md-3">
    <?= Html::activeCheckbox($seo, 'is_canonical') ?>
</div>

<div class="form-group col-md-3">
    <?= Html::activeCheckbox($seo, 'in_sitemap') ?>
</div>