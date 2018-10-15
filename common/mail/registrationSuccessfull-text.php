<?php
/**
 * @var $user backend\models\User
 */

$url = Yii::$app->urlManager->createAbsoluteUrl([
    'site/confirm-email',
    'email' => $user->email,
    'token' => $user->password_reset_token,
]);
?>

Hello <?= $user->getFullName() ?>,

Follow the link below to confirm your Email:

<?= $url ?>
