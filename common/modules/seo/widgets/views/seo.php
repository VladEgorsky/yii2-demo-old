<?php
/**
 * Created by PhpStorm.
 * User: yurik
 * @var $seo \common\modules\seo\models\Seo
 * @var $model \yii\db\ActiveRecord
 */

use yii\bootstrap\Tabs;

?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" onclick="$(this).next().toggle('fast');" style="cursor: pointer">
                <h3 class="panel-title">SEO</h3>
            </div>
            <div class="panel-body" style="display: none;">
                <?= $this->render('_seo_form', [
                        'seo' => $seo,
                        'model' => $model,
                ])?>
            </div>
        </div>
    </div>
</div>