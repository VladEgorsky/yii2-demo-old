<?php
namespace backend\models;

use backend\components\LoggingBehavior;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class User extends \common\models\User
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'log' => [
                    'class' => LoggingBehavior::class,
                    'excludedAttribs' => [
                        'id', 'auth_key', 'password_hash', 'password_reset_token', 'created_at'
                    ],
                ]
            ]
        );
    }

    ///////////////////////////////////////////////////////////
    ///                 EVENTS
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = time();
        }

        return parent::beforeSave($insert);
    }

    public function afterDelete()
    {
        Yii::$app->authManager->revokeAll($this->id);
        parent::afterDelete();
    }

    ///////////////////////////////////////////////////////////
    ///                 HELPERS
    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == static::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isInactive()
    {
        return $this->status == static::STATUS_INACTIVE;
    }

    /**
     * @return bool
     */
    public function isBlocked()
    {
        return $this->status == static::STATUS_BLOCKED;
    }


    ///////////////////////////////////////////////////////////
    ///                 STATIC  FUNCTIONS
    /**
     * @param array $where
     * @param array $orderBy
     * @return array
     */
    public static function getListData($where = [], $orderBy = [])
    {
        return static::find()->select(['name' => 'CONCAT(name, " ", surname)', 'id'])
            ->where($where)->orderBy($orderBy)->asArray()->indexBy('id')->column();
    }

    /**
     * @return array
     */
    public static function getRolesGroupedByUserId()
    {
        $userRoles = (new Query())->from('{{%auth_assignment}}')->all();
        return ArrayHelper::map($userRoles, 'created_at', 'item_name', 'user_id');
    }

    public static function getRolesListData($type = null)
    {
        $query = (new Query())->select(['name', 'id' => 'name'])->from('{{%auth_item}}');
        if (!is_null($type)) {
            $query->where = ['type' => $type];
        }

        $listData = $query->indexBy('id')->column();
        return array_merge(['#N/A' => '#N/A'], $listData);
    }
}