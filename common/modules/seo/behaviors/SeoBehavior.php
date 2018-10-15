<?php
/**
 * Created by PhpStorm.
 * User: yurik
 */

namespace common\modules\seo\behaviors;

use common\modules\seo\models\Seo;
use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class SeoBehavior extends Behavior
{

    public $model;
    public $view_category;
    public $view_action;
    public $url_attribute = 'title';

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'writeSeo',
            ActiveRecord::EVENT_AFTER_UPDATE => 'writeSeo',
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteSeo',
        ];
    }

    /**
     * @param $event
     *
     * @throws \Exception
     */
    public function deleteSeo($event)
    {
        Seo::deleteAll(['model_name' => $this->model, 'model_id' => $event->sender->id]);
    }

    /**
     * @param $event
     */
    public function writeSeo($event)
    {

        $seo = Seo::find()->where([
            'model_name' => $this->model,
            'model_id' => $event->sender->id,
        ])->one();

        if ($seo == null) {
            $seo = new Seo();
        }

        $title = trim(str_replace('  ', ' ', $event->sender->{$this->url_attribute}));

        if (method_exists(Yii::$app->request, 'post')) {
            $seo->load(Yii::$app->request->post());
        }

        if ($seo->external_link == '') {
            $seo->external_link = $this->checkUnicUrl(($this->view_category ? $this->view_category . "/" : "") .  Inflector::slug($title),
                $event->sender->id);
            if (method_exists($event->sender, 'buildUrl')) {
                $seo->external_link = $this->checkUnicUrl($event->sender->buildUrl() . '/' . Inflector::slug($title),
                    $event->sender->id);
            }
        } else {
            $seo->external_link = $this->checkUnicUrl($seo->external_link, $event->sender->id);
        }

        $seo->title = $seo->title ? $seo->title : $title;
        $seo->h1 = $seo->h1 ? $seo->h1 : $seo->title;
        $seo->internal_link = $this->view_action;
        $seo->model_name = $this->model;
        $seo->model_id = $event->sender->id;
        $seo->external_link = trim($seo->external_link, '/');
        $seo->save(false);

    }

    /**
     * @param $url
     * @param $id
     *
     * @return string
     */
    public function checkUnicUrl($url, $id)
    {
        $result = Seo::find()->where(['external_link' => $url])->andWhere(['!=', 'model_id', $id])->one();
        if ($result != null) {
            return $url . '-' . $id;
        }

        return $url;
    }

}