<?php
/**
 * Created by PhpStorm.
 * Date: 15.09.18
 */

namespace common\components\elastic;

use backend\models\News;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class ElasticNewsBehavior
 * @package common\components\elastic
 */
class ElasticNewsBehavior extends Behavior
{

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'addToSearch',
            ActiveRecord::EVENT_AFTER_UPDATE => 'addToSearch',
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteFromSearch',
        ];
    }

    public function addToSearch($event)
    {
        $model = $event->sender;

        /**
         * @var $model News
         */

        $data = [
            'title'         => $model->title,
            'content'       => $model->content,
            'image'         => $model->cover_image,
            'url'           => $model->seoUrl,
            'created_at'    => $model->created_at,
            'sections_list' => implode(', ', $model->getSectionsList()),
            'tags_list'     => implode(', ', $model->getTagsList()),
            'sections'      => $model->getSections()->select('id')->column(),
            'tags'          => $model->getTags()->select('id')->column(),
        ];

        $params = [
            'index' => 'news_index',
            'type'  => 'news_type',
            'id'    => $model->id,
            'body'  => $data,
        ];

        if ($model->isNewRecord)
            \Yii::$app->elastic->add($params);
        else {
            unset($data['id']);
            $params = [
                'index' => 'news_index',
                'type'  => 'news_type',
                'id'    => $model->id,
                'body'  => [
                    'doc' => $data,
                ],
            ];

            \Yii::$app->elastic->update($params);
        }


    }

}