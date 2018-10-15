<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $user_id
 * @property string $model_class
 * @property int $model_id
 * @property string $action
 * @property int $created_at
 * @property string $values
 */
class Log extends \yii\db\ActiveRecord
{
    const ACTION_DELETED = 'DELETED';
    const ACTION_CREATED = 'CREATED';
    const ACTION_UPDATED = 'UPDATED';

    public static function getActions()
    {
        return [
            static::ACTION_DELETED => 'Deleted',
            static::ACTION_CREATED => 'Created',
            static::ACTION_UPDATED => 'Updated',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'model_class', 'model_id', 'action', 'created_at', 'values'], 'required'],
            [['user_id', 'model_id', 'created_at'], 'integer', 'min' => 1],
            [['values'], 'string'],
            [['model_class'], 'string', 'max' => 255],
            [['action'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Corrector',
            'model_class' => 'Model',
            'model_id' => 'Model ID',
            'action' => 'Action',
            'created_at' => 'Date',
            'values' => 'Values',
        ];
    }


    ///////////////////////////////////////////////////////////////////
    ///             RELATIONS
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    ///////////////////////////////////////////////////////////////////
    ///             STATIC  FUNCTIONS
    public static function getModelNamesForLogging()
    {
        $models = glob(Yii::getAlias('@backend/models/*.php'));
        $ret = [];

        foreach ($models as $model) {
            $name = basename($model, '.php');
            $ret['backend\\models\\' . $name] = $name;
        }
        return $ret;
    }
}
