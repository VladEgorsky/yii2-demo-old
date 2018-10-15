<?php

/**
 * @var yii\web\View $this
 * @var $model \common\modules\seo\models\Seo
 */

$this->title = 'Update';
$this->params['breadcrumbs'][] = ['label' => 'SEO', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box">
    <div class="box-body">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>