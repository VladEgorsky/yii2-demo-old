<?php
/**
 * Created by PhpStorm.
 */

namespace console\controllers;


use common\models\News;
use frontend\models\Subscribe;
use yii\console\Controller;
use yii\helpers\Html;

class SubscribeController extends Controller
{

    public function actionOneDay()
    {
        $from = strtotime('-1 day');
        $to = time();

        $news = News::find()
            ->with(['seo'])
            ->where(['between', 'created_at', $from, $to])
            ->asArray()
            ->all();

        if ($news) {
            $message = '';
            foreach ($news as $model)
                $message .= Html::tag('p', Html::a($model['title'], [$model['seo']['external_link']]));

            $subscribers = Subscribe::find()
                ->where(['period' => Subscribe::SUBSCRIBE_PERIOD_ONE_DAY])
                ->andWhere(['status' => Subscribe::STATUS_ACTIVE])
                ->select('email')
                ->column();

            if ($subscribers) {
                foreach ($subscribers as $email) {
                    \Yii::$app->mailer->compose()
                        ->setFrom('robot@test.com')
                        ->setTo($email)
                        ->setSubject('Last news list')
                        ->setHtmlBody($message)
                        ->send();
                }
            }
        }

    }


}