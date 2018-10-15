<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

class UserRegistration extends User
{
    public $password;
    public $repeat_password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['password', 'repeat_password'], 'required'],
                [['password'], 'string', 'min' => 6],
                [['repeat_password'], 'compare', 'compareAttribute' => 'password'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeLabels()
    {
        return ArrayHelper::merge(
            parent::getAttributeLabels(),
            [
                'password' => Yii::t('app', 'Password'),
                'repeat_password' => Yii::t('app', 'Repeat password'),
            ]
        );

    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function populateAndValidate()
    {
        $this->setPassword($this->password);
        $this->generateAuthKey();
        $this->generatePasswordResetToken();
        return $this->validate();
    }

    /**
     * @return bool
     */
    public function sendInstruction()
    {
        return Yii::$app->mailer
            ->compose(
                ['html' => 'registrationSuccessfull-html'],
                ['user' => $this]
            )
            ->setFrom([Yii::$app->params['robotEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject(Yii::t('app', 'Confirm Email on the site') . ' ' . Yii::$app->name)
            ->send();
    }

}