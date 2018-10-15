<?php
/**
 * @var $this yii\web\View
 * @var $user backend\models\User
 */

$url = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>


Hello <?= $user->getFullName() ?>,

Follow the link below to reset your password:

<?= $url ?>
