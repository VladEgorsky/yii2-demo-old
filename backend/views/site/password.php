<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\forms\PasswordForm
 * @var $form yii\widgets\ActiveForm
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

if ($model->scenario == 'forgotPassword') {
    $this->title = Yii::t('app', 'Forgot Password');
} elseif ($model->scenario == 'resetPassword') {
    $this->title = Yii::t('app', 'Set New Password');
} elseif ($model->scenario == 'changePassword') {
    $this->title = Yii::t('app', 'Change Password');
}
?>


<div class="login-box">
    <div class="login-logo">
        <a href="<?= Url::home() ?>">
            <?= Yii::$app->name ?>
        </a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">
            <?= Yii::t('app', $this->title) ?>
        </p>

        <?php $form = ActiveForm::begin(['id' => 'password-form']); ?>

        <?php if ($model->scenario == 'forgotPassword') : ?>
            <div style="padding-bottom: 10px;">
                <?= Yii::t('app', 'Specify the email that was used during registration') ?>
            </div>

            <div class="form-group has-feedback">
                <?= $form->field($model, 'email')
                    ->textInput(['type' => 'email', 'class' => 'form-control', 'maxlength' => true,
                        'placeholder' => $model->getAttributeLabel('email')])
                    ->label(false) ?>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
        <?php endif; ?>


        <?php if ($model->scenario == 'changePassword') : ?>
            <div class="form-group has-feedback">
                <?= $form->field($model, 'oldPassword')
                    ->passwordInput(['class' => 'form-control', 'maxlength' => true,
                        'placeholder' => $model->getAttributeLabel('oldPassword')])
                    ->label(false) ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        <?php endif; ?>


        <?php if ($model->scenario != 'forgotPassword') : ?>
            <div class="form-group has-feedback">
                <?= $form->field($model, 'newPassword')
                    ->passwordInput(['class' => 'form-control', 'maxlength' => true,
                        'placeholder' => $model->getAttributeLabel('newPassword')])
                    ->label(false) ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <?= $form->field($model, 'repeatNewPassword')
                    ->passwordInput(['class' => 'form-control', 'maxlength' => true,
                        'placeholder' => $model->getAttributeLabel('repeatNewPassword')])
                    ->label(false) ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        <?php endif; ?>


        <div class="row" style="padding: 5px 0 15px 0">
            <div class="col-xs-6 text-center">
                <?= Html::a(Yii::t('app', 'Back to Homepage'), Url::home(), [
                    'class' => 'btn btn-default', 'style' => 'width:140px;'])
                ?>
            </div>

            <div class="col-xs-6 text-center">
                <?= Html::submitButton(Yii::t('app', 'OK'), [
                    'class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button', 'style' => 'width: 140px;'])
                ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <?php if ($model->scenario != 'forgotPassword') : ?>
            <a href="<?= Url::to(['forgot-password']) ?>">
                <?= Yii::t('app', 'I forgot my password') ?>
            </a><br/>
        <?php endif; ?>

        <a href="<?= Url::to(['register']) ?>" class="text-center">
            <?= Yii::t('app', 'Register a new membership') ?>
        </a>
    </div>
</div><!-- /.login-box -->
