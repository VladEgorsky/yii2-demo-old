<?php
/**
 * Created by PhpStorm.
 * User: yurik
 * Date: 29.08.18
 * Time: 14:32
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * Class InitController
 * @package console\controllers
 */
class InitController extends Controller
{
    /**
     * Создаем символическую ссылку на uploads
     */
    public function actionRun()
    {
        if (!is_link(Yii::getAlias('@backend') . '/web/uploads')) {
            symlink(Yii::getAlias('@frontend') . '/web/uploads', Yii::getAlias('@backend') . '/web/uploads');
        }
    }
}