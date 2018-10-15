<?php

namespace common\components;

use Yii;

/**
 * Class FlashMessages
 * @package common\components
 *
 * Класс хранит флеш-сообщения, которые используются во всем проекте.
 * Для вывода сообщений используется единственный view message.php
 *
 * Пример вызова в контроллерах :
 * FlashMessages::setMessage("REGISTERED_SUCCESSFULLY");
 * return $this->redirect(['/site/page', 'view' => 'message']);
 *
 * Во вьюхе вызываем так :
 * $mess FlashMessages::getMessage();
 * echo $mess['title]; echo $mess['text];
 */
class FlashMessages
{
    private static $flashMessageKey = 'flashMessageKey';

    /**
     * @param $value string
     */
    public static function setMessage($value)
    {
        Yii::$app->session->setFlash(static::$flashMessageKey, $value);
    }

    /**
     * @return array
     */
    public static function getMessage()
    {
        $value = Yii::$app->session->getFlash(static::$flashMessageKey);
        if (!$value || !isset(static::$data[$value])) {
            return [
                'title' => get_called_class() . ' error',
                'text' => Yii::t('app', 'Demanded key does not exist ') . $value,
            ];
        }

        return array_map('trim', static::$data[$value]);
    }

    private static $data = [
        'REGISTERED_SUCCESSFULLY' => [
            'title' => 'Confirm registration',
            'text' => '
                You are successfully registered!<br />
                All details sent to your Email.',
        ],
        'EMAIL_SUCCESSFULLY_CONFIRMED' => [
            'title' => 'Email confirmed',
            'text' => '
                Congratulations! Your Email confirmed successfully!<br />
                Our Administrator will get notification in the nearest time.<br />
                You will be able to work with our Admin panel if he grant you <br />
                the neccesary privileges.',
        ],
        'INCORRECT_INCOMING_PARAMETERS' => [
            'title' => 'Request error',
            'text' => '
                Incorrect ioncoming parameters.',
        ],
        'FORGOT_PASSWORD_SENT_EMAIL' => [
            'title' => 'Instruction sent',
            'text' => '
                Check your email, we sent you instructions on how to recover your password.',
        ],
    ];
}