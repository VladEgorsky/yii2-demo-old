<?php
/**
 * @var $user backend\models\User
 */

use yii\helpers\Html;

$url = Yii::$app->urlManager->createAbsoluteUrl([
    'site/confirm-email',
    'email' => $user->email,
    'token' => $user->password_reset_token,
]);
?>

<div class="">
    <p>Hello <?= Html::encode($user->getFullName()) ?>,</p>

    <p>Follow the link below to confirm your Email:</p>

    <p><?= Html::a(Html::encode($url), $url) ?></p>
</div>
