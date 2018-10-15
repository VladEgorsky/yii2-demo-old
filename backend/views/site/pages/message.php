<?php

use common\components\FlashMessages;
use yii\helpers\Html;
use yii\helpers\Url;

$message = FlashMessages::getMessage();

?>

<div style="margin:0 100px;">
    <section class="content-header">
        <h1>
            <?= Yii::$app->name ?>
        </h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= $message['title'] ?? ''; ?>
                </h3>
            </div>

            <div class="box-body">
                <?= $message['text'] ?? ''; ?>
            </div>

            <div class="box-footer text-center">
                <?= Html::a(Yii::t('app', 'Back to Homepage'), Url::home()) ?>
            </div>
        </div>
    </section>
</div>
