<?php
/**
 */

namespace common\components;

use Yii;

/**
 * Class CaptchaAction
 * @package common\components
 */
class CaptchaAction extends \yii\captcha\CaptchaAction
{
    /**
     * @param string $input
     * @param bool $caseSensitive
     * @return bool
     */
    public function validate($input, $caseSensitive)
    {
        $code = $this->getVerifyCode();
        $valid = $caseSensitive ? ($input === $code) : strcasecmp($input, $code) === 0;
        $session = Yii::$app->getSession();
        $session->open();
        $name = $this->getSessionKey() . 'count';
        $session[$name] += 1;
        if ($valid || $session[$name] > $this->testLimit && $this->testLimit > 0) {
            if (\Yii::$app->request->isAjax == false) {
                $this->getVerifyCode(true);
            }
        }

        return $valid;
    }
}