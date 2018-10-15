<?php
/*
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \common\models\forms\LoginForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Sign in');
?>

<div class="login-box">
    <div class="login-logo">
        <a href="<?= Url::home() ?>">
            <?= Yii::$app->name ?>
        </a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">
            <?= Yii::t('app', 'Sign in to start your session') ?>
        </p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <div class="form-group has-feedback">
            <?= $form->field($model, 'email')
                ->textInput(['type' => 'email', 'class' => 'form-control', 'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('email')])
                ->label(false) ?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
            <?= $form->field($model, 'password')
                ->passwordInput(['class' => 'form-control', 'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('password')])
                ->label(false) ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
            <div class="col-xs-6">
                <div class="checkbox icheck">
                    <?= Html::activeCheckbox($model, 'rememberMe', [
                        'uncheck' => null
                    ]) ?>
                </div>
            </div>

            <div class="col-xs-6">
                <?= Html::submitButton(Yii::t('app', 'Sign in'), [
                    'class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button'])
                ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <?php ActiveForm::end(); ?>


        <!--        <div class="social-auth-links text-center">-->
        <!--            <p>- OR -</p>-->
        <!--            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat">-->
        <!--                <i class="fa fa-facebook"></i> --><?php //= Yii::t('app', 'Sign up using Facebook') ?>
        <!--            </a>-->
        <!--            <a href="#" class="btn btn-block btn-social btn-google btn-flat">-->
        <!--                <i class="fa fa-google-plus"></i> --><?php //= Yii::t('app', 'Sign up using Google+') ?>
        <!--            </a>-->
        <!--        </div>-->
        <!-- /.social-auth-links -->

        <a href="<?= Url::to(['forgot-password']) ?>">
            <?= Yii::t('app', 'I forgot my password') ?>
        </a><br/>
        <a href="<?= Url::to(['register']) ?>" class="text-center">
            <?= Yii::t('app', 'Register a new membership') ?>
        </a>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
