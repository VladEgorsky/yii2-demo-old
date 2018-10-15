<?php
/**
 * Created by PhpStorm.
 * Date: 15.09.18
 */

namespace common\components\elastic;

use Elasticsearch\ClientBuilder;
use yii\base\Component;

/**
 * Class ElasticComponent
 * @package common\components\elastic
 */
class ElasticComponent extends Component
{
    public $host = 'localhost';
    public $port = 9200;

    protected $client;

    /**
     *
     */
    public function init()
    {
        parent::init();

        $host = [
            [
                'host' => $this->host,
                'port' => $this->port,
            ]
        ];

        $this->client = ClientBuilder::create()->setHosts($host)->build();

    }

    /**
     * @param $params
     * @return mixed
     */
    public function deleteIndex($params)
    {

        return $this->client->indices()->delete($params);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function createIndex($params)
    {
        return $this->client->indices()->create($params);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function add($params)
    {
        return $this->client->index($params);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function update($params)
    {
        return $this->client->update($params);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function delete($params)
    {
        return $this->client->delete($params);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function search($params)
    {
        return $this->client->search($params);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function get($params)
    {
        return $this->client->get($params);
    }

}