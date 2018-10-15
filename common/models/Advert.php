<?php

namespace common\models;

class Advert
{
    // Values for template_location.advert_key
    const LOCATION_RIGHT = 'RIGHT';
    const LOCATION_BOTTOM = 'BOTTOM';
    const LOCATION_COMMENT = 'COMMENT';

    protected static $data = [
        self::LOCATION_RIGHT => 'Right Panel',
        self::LOCATION_BOTTOM => 'Bottom Panel',
        self::LOCATION_COMMENT => 'User Comments',
    ];

    /**
     * @return array
     */
    public static function getListData()
    {
        return static::$data;
    }

    /**
     * @param $ids integer|array
     * @return array
     */
    public static function getListDataByIds($ids)
    {
        if (!is_scalar($ids) && !is_array($ids)) {
            return ['#N/A' => '#N/A'];
        } else if (!is_array($ids)) {
            $ids = [$ids];
        }

        $ret = [];
        foreach ($ids as $id) {
            if (array_key_exists($id, static::$data)) {
                $ret[$id] = static::$data[$id];
            }
        }

        return $ret;
    }

    /**
     * @param $ids integer|array
     * @return array
     */
    public static function getNamesByIds($ids)
    {
        if (!is_integer($ids) || !is_array($ids)) {
            return ['#N/A'];
        } else if (!is_array($ids)) {
            $ids = [$ids];
        }

        $ret = [];
        foreach ($ids as $id) {
            if (array_key_exists($id, static::$data)) {
                $ret[] = static::$data[$id];
            }
        }

        return $ret;
    }
}