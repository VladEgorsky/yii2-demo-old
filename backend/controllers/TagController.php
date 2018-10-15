<?php
namespace backend\controllers;

use backend\components\BaseController;
use backend\models\search\TagsSearch;
use backend\models\Tag;
use richardfan\sortable\SortableAction;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

class TagController extends BaseController
{
    public $modelClass = Tag::class;
    public $searchModelClass = TagsSearch::class;
    public $newModelDefaultAttributes = ['status' => Tag::STATUS_VISIBLE];

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'sortItem' => [
                'class' => SortableAction::class,
                'activeRecordClassName' => Tag::class,
                'orderColumn' => 'ordering',
            ],
        ];
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->fillSectionIds();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Url::previous());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param null $q
     * @param null $id
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];

        if ($id > 0) {
            $tag = Tag::find()->where(['id' => $id])->one();
            $out['results'] = ['id' => $id, 'text' => $tag->title];
        } else {
            $query = Tag::find();
            if (!is_null($q)) {
                $query->where(['like', 'title', $q]);
            }
            $command = $query->createCommand();

            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }

    /**
     * @return array|\backend\models\Section[]|Tag[]|\yii\db\ActiveRecord[]
     */
    public function actionGetList()
    {
        $section = Yii::$app->request->post('section');
        Yii::$app->response->format = Response::FORMAT_JSON;

        return Tag::getListBySections($section);
    }
}
