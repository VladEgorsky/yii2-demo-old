<?php
/**
 * @var $this yii\web\View
 * @var $user backend\models\User
 */

use yii\helpers\Html;

$url = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>


<div class="password-reset">
    <p>Hello <?= Html::encode($user->getFullName()) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($url), $url) ?></p>
</div>
