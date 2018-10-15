<?php

namespace common\models\forms;

use Yii;
use yii\base\Model;
use common\models\User;

class PasswordForm extends Model
{
    public $email;
    public $oldPassword;
    public $newPassword;
    public $repeatNewPassword;

    private $user;

    /**
     * forgotPassword   - Запрашиваем ввод Email и отправляем ссылку с passwordResetToken
     * resetPassword    - Приходим по этой ссылке и запрашиваем Новый пароль и его подтверждение
     * changePassword   - Когда залогиненный пользователь из своего Личного кабинета хочет изменить пароль
     *                      Запрашиваем Старый пароль, Новый пароль и подтверждение нового пароля
     */
    public function scenarios()
    {
        return [
            'forgotPassword' => ['email'],
            'resetPassword' => ['newPassword', 'repeatNewPassword'],
            'changePassword' => ['oldPassword', 'newPassword', 'repeatNewPassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'oldPassword', 'newPassword', 'repeatNewPassword'], 'trim'],
            [['email', 'oldPassword', 'newPassword', 'repeatNewPassword'], 'string', 'max' => 100],

            ['email', 'required', 'on' => 'forgotPassword'],
            ['email', 'email'],
            ['email', 'validateEmail'],

            ['oldPassword', 'required', 'on' => 'changePassword'],
            ['oldPassword', 'validateOldPassword'],

            [['newPassword', 'repeatNewPassword'], 'required', 'on' => ['resetPassword', 'changePassword']],
            ['newPassword', 'string', 'min' => 6],
            ['repeatNewPassword', 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }

    public function validateEmail($attribute, $params)
    {
        $user = User::findOne(['email' => $this->email]);

        if (!$user) {
            $this->addError('email', Yii::t('app', 'There is no user with this email address.'));
        } elseif ($user->status == User::STATUS_INACTIVE) {
            $this->addError('email', Yii::t('app', 'You account is inactive. 
                Please check your Email to find an instruction on how to activate it'));
        } elseif ($user->status == User::STATUS_BLOCKED) {
            $this->addError('email', Yii::t('app', 'You account is blocked by Administrator'));
        } elseif ($user->status == User::STATUS_DELETED) {
            $this->addError('email', Yii::t('app', 'You account is deleted by Administrator'));
        }

        $this->user = $user;
    }

    public function validateOldPassword($attribute, $params)
    {
        $a = 2;
        if (!Yii::$app->user->identity->validatePassword($this->oldPassword)) {
            $this->addError('oldPassword', Yii::t('app', 'Incorrect old password'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'oldPassword' => Yii::t('app', 'Old password'),
            'newPassword' => Yii::t('app', 'New password'),
            'repeatNewPassword' => Yii::t('app', 'Repeat new password'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        if (!User::isPasswordResetTokenValid($this->user->password_reset_token)) {
            $this->user->generatePasswordResetToken();

            if (!$this->user->save(false, ['password_reset_token'])) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $this->user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }


    public function populateAndSave($user)
    {
        /** @var $user \common\models\User */
        $user->setPassword($this->newPassword);
        $user->generateAuthKey();
        $user->removePasswordResetToken();
        return $user->save(false, ['auth_key', 'password_hash', 'password_reset_token']);
    }
}
