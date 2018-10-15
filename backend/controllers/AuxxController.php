<?php

namespace backend\controllers;

use backend\components\Y;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Common actions for all Models
 *
 * Class AuxxController
 * @package backend\controllers
 */
class AuxxController extends \yii\web\Controller
{
    /**
     * @param bool|false $validate
     * @return array|bool
     *
     * Action must get 2 post parameters:
     * 1. model_classname   - "backend\models\User" for example
     * 2. model_attributes  - User[id], User[name] ...
     * Action creates a new record if User[id] is absent or empty,
     *      otherwise updates existing record
     */

    /**
     * Requst must contain 2 or 3 post parameters:
     * 1. classname     - "backend\models\User" for example
     * 2. attributes    - [id => 1, name => Vasya, attr1 => 1, attr2 => 2 ...]
     * 3. validate      - optional, true by default
     * Action creates a new record if User[id] is absent or empty,
     *      otherwise updates existing record
     * Action must return "ok" if all is ok or any other string otherwise
     *
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionAjaxUpdateModel()
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException('Ajax Request is waiting');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!Yii::$app->request->isPost) {
            return 'Post Request is waiting';
        }

        try {
            $modelClass = stripslashes(Yii::$app->request->post('classname', ''));
            if (empty($modelClass) || !class_exists($modelClass)) {
                return 'Incorrect model classname';
            }
            if (empty($modelData = Yii::$app->request->post('attributes'))) {
                return 'Incorrect incoming parameters';
            }

            /** @var \yii\db\ActiveRecord $model */
            $model = empty($modelData['id']) ? new $modelClass()
                : $modelClass::findOne($modelData['id']);
            $model->setAttributes($modelData);

            // Validate = true by default
            $validate = Yii::$app->request->post('validate', true);
            if ($validate && !$model->validate()) {
                return Y::getModelErrorsAsStrings($model);
            }

            $model->save(false);
            return 'ok';

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}