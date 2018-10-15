<?php
namespace common\models;

use common\modules\seo\models\Seo;
use ReflectionClass;
use yii\helpers\ArrayHelper;

class BaseModel extends \yii\db\ActiveRecord
{
    protected $isSeoRequired = false;
    protected static $nameField = 'name';
    protected static $statuses = [];

    /**
     * @return string
     */
    public function getStatus($key = null)
    {
        if (is_null($key)) {
            return static::$statuses[$this->status];
        }

        return static::$statuses[$key] ?? '#N/A';
    }

    /**
     * @return array
     */
    public static function getStatusListData()
    {
        return static::$statuses;
    }

    /**
     * @param array $where
     * @param array $orderBy
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getListData($where = [], $orderBy = [])
    {
        $data = static::find()
            ->select([static::getTableSchema()->primaryKey[0] . ' AS id', static::$nameField . ' AS name'])
            ->where($where)->orderBy($orderBy)->asArray()->all();

        return ArrayHelper::map($data, 'id', 'name');
    }

    /**
     * @return array
     */
    public static function getYesNoListData()
    {
        return [0 => 'No', 1 => 'Yes'];
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public static function getModelName()
    {
        $reflect = new ReflectionClass(static::class);
        return $reflect->getShortName();
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \ReflectionException
     */
    public function getSeo()
    {
        return $this->isSeoRequired
            ? $this->hasOne(Seo::class, ['model_id' => 'id'])->where(['model_name' => static::getModelName()])
            : null;
    }

    /**
     * @return null|string
     */
    public function getSeoUrl()
    {
        if (isset($this->seo))
            return '/' . trim($this->seo->external_link, '/');

        return null;
    }
}