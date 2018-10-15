<?php

/**
 * @var yii\web\View $this
 * @var $model \common\modules\seo\models\Seo
 */

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'SEO', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>