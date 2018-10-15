<?php
/**
 * Created by PhpStorm.
 * User: yurik
 * Date: 14.09.18
 * Time: 10:43
 */

namespace console\controllers;


use backend\models\News;
use yii\console\Controller;

class ElasticController extends Controller
{
    protected $client;

    public function init()
    {

        $this->client = \Yii::$app->elastic;

        parent::init();
    }


    public function actionCreateIndex()
    {

        $params = [
            'index' => 'news_index',
            'body'  => [
                'mappings' => [
                    '_default_' => [
                        'properties' => [
                            'title'         => [
                                'type' => 'text',
                            ],
                            'content'       => [
                                'type' => 'text',
                            ],
                            'image'         => [
                                'type' => 'keyword'
                            ],
                            'url'           => [
                                'type' => 'keyword',
                            ],
                            'created_at'    => [
                                'type' => 'integer'
                            ],
                            'sections'      => [
                                'type'      => 'text',
                                'fielddata' => true,
                            ],
                            'tags'          => [
                                'type'      => 'text',
                                'fielddata' => true,
                            ],
                            'sections_list' => [
                                'type' => 'text',
                            ],
                            'tags_list'     => [
                                'type' => 'text',
                            ],
                        ]
                    ]
                ],
            ]
        ];

        try {
            $this->client->deleteIndex(['index' => 'news_index']);
        } catch (\Exception $e) {
        }

        $this->client->createIndex($params);

    }


    /**
     *
     */
    public function actionAddToIndex()
    {
        $news = News::find()->all();
        foreach ($news as $model) {
            $image = $model->getImage();
            if ($image)
                $image = $image->getUrl('184x106');

            $data = [
                'title'         => $model->title,
                'content'       => $model->content,
                'image'         => $image,
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

            $s = [
                'index' => 'news_index',
                'type'  => 'news_type',
                'id'    => $model->id,
            ];

            try {
                $response = \Yii::$app->elastic->get($s);
            } catch (\Exception $e) {
                $response = null;
            }

            if ($response == null)
                \Yii::$app->elastic->add($params);
            else {
                unset($data['id']);
                $params = [
                    'index' => 'news_index',
                    'type'  => 'news_type',
                    'id'    => $response['_id'],
                    'body'  => [
                        'doc' => $data,
                    ],
                ];

                \Yii::$app->elastic->update($params);

            }
        }
    }

}