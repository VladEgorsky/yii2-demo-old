<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\UserRegistration
 * @var $form yii\widgets\ActiveForm
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Registration');
?>

<div class="register-box">
    <div class="register-logo">
        <a href="<?= Url::home() ?>">
            <?= Yii::$app->name ?>
        </a>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg"><?= Yii::t('app', 'Register a new membership') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'registration-form']); ?>

        <div class="form-group has-feedback">
            <?= $form->field($model, 'name')
                ->textInput(['class' => 'form-control', 'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('name')])
                ->label(false) ?>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
            <?= $form->field($model, 'surname')
                ->textInput(['class' => 'form-control', 'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('surname')])
                ->label(false) ?>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
            <?= $form->field($model, 'email')
                ->textInput(['type' => 'email', 'class' => 'form-control', 'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('email')])
                ->label(false) ?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '+7 999 999-99-99',
                'options' => [
                    'class' => 'form-control', 'placeholder' => $model->getAttributeLabel('phone')
                ],
            ])->label(false) ?>
            <span class="glyphicon glyphicon-phone-alt form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
            <?= $form->field($model, 'password')
                ->passwordInput(['class' => 'form-control', 'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('password')])
                ->label(false) ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
            <?= $form->field($model, 'repeat_password')
                ->passwordInput(['class' => 'form-control', 'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('repeat_password')])
                ->label(false) ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block btn-flat" style="width:50%; margin-left:25%">
                <?= Yii::t('app', 'Register') ?>
            </button>
        </div>
        <?php ActiveForm::end(); ?>

        <!--        <div class="social-auth-links text-center">-->
        <!--            <p>- --><?php //= Yii::t('app', 'OR') ?><!-- -</p>-->
        <!--            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat">-->
        <!--                <i class="fa fa-facebook"></i> --><?php //= Yii::t('app', 'Sign up using Facebook') ?>
        <!--            </a>-->
        <!--            <a href="#" class="btn btn-block btn-social btn-google btn-flat">-->
        <!--                <i class="fa fa-google-plus"></i> --><?php //= Yii::t('app', 'Sign up using Google+') ?>
        <!--            </a>-->
        <!--        </div>-->

        <a href="<?= Url::to(['login']) ?>" class="text-center">
            <?= Yii::t('app', 'I already have a membership') ?>
        </a>
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->
