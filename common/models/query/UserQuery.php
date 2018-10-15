<?php
namespace common\models\query;

use common\models\User;
use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => User::STATUS_ACTIVE]);
    }

    public function inactive()
    {
        return $this->andWhere(['status' => User::STATUS_INACTIVE]);
    }

    public function blocked()
    {
        return $this->andWhere(['status' => User::STATUS_BLOCKED]);
    }

    public function status(int $statusCode)
    {
        return $this->andWhere(['status' => $statusCode]);
    }
}