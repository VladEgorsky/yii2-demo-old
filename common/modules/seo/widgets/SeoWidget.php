<?php
/**
 * Created by PhpStorm.
 * User: yurik
 * Date: 28.05.14
 * Time: 10:40
 */

namespace common\modules\seo\widgets;

use common\modules\seo\models\Seo;
use yii\base\Widget;

class SeoWidget extends Widget
{
    public $model;

    /**
     * @return string
     */
    public function run()
    {
        $seo = $seo_ru = null;
        if ($this->model->id) {
            $seo = Seo::find()->where([
                'model_name' => $this->model->getModelName(),
                'model_id' => $this->model->id,
            ])->one();
        }
        if ($seo == null) {
            $seo = new Seo();
        }

        return $this->render('seo', [
            'seo' => $seo,
            'model' => $this->model,
        ]);
    }

} 