<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdvertiseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\models\Advertise;
use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\widgets\Pjax;

$this->title = 'Advertises';
$this->blocks['content_header'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
$advertiseStatusListData = Advertise::getStatusListData();
?>

    <div class="advertise-index">
        <h3><?= Html::encode($this->title) ?></h3>

        <!-- For using in advertise.js -->
        <input type="hidden" id="ajax_update_model_url" name="hi1" value="<?= Url::to(['/auxx/ajax-update-model']) ?>"/>
        <input type="hidden" id="advertise_model_class" name="hi2" value="<?= addcslashes(Advertise::class, '\\') ?>"/>

        <?= Html::a('New Advertise', Url::to(['create']), ['class' => 'btn btn-primary', 'style' => 'margin-left: 10px;']) ?>

        <?php Pjax::begin(['id' => 'grid_advertise_pjax']); ?>
        <?= GridView::widget([
            'id' => 'grid_advertise',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                    'data-advertise' => $model->getAttributes(['id', 'name', 'email']),
                    'data-files' => $model->getFiles(true),
                ];
            },
            'columns' => [
                [
                    'attribute' => 'id',
                    'filter' => false,
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:d.m.Y H:i'],
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'created_at',
                        'pickerButton' => false,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]
                    ]),
                    'label' => 'Date',
                    'options' => ['style' => 'width: 150px;'],
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) use ($advertiseStatusListData) {
                        return Html::activeDropDownList($model, 'status', $advertiseStatusListData, [
                            'class' => 'form-control'
                        ]);
                    },
                    'filter' => $advertiseStatusListData,
                    'options' => ['style' => 'width: 140px;'],

                ],
                [
                    'attribute' => 'content',
                    'format' => 'html',
                    'value' => function ($model) {
                        return StringHelper::truncate($model->content, Yii::$app->params['ingridStringMaxLength']);
                    }
                ],
                [
                    'format' => 'raw',
                    'label' => 'Info',
                    'value' => function ($model) {
                        $buttons = Html::button('User',
                            ['type' => 'button', 'class' => 'btn btn-default btn-xs btn_user_info']);

                        if ($model->files) {
                            $buttons .= ' &nbsp;' . Html::button('Files',
                                    ['type' => 'button', 'class' => 'btn btn-default btn-xs btn_files_info']);
                        }
                        return $buttons;
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} &nbsp; {delete}',
                ],
            ],
        ]); ?>
        <?php Pjax::end() ?>
    </div>

<?php
// file "advertise.js" is blocked by browser and does not load
$this->registerJsFile('/js/controllers/aadvertise.js', ['depends' => JqueryAsset::class]);
