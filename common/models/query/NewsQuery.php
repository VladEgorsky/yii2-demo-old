<?php

namespace common\models\query;

use common\models\News;
use yii\db\ActiveQuery;

class NewsQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => News::STATUS_VISIBLE]);
    }

    public function inactive()
    {
        return $this->andWhere(['status' => News::STATUS_HIDDEN]);
    }

}