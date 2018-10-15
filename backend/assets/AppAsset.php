<?php

namespace backend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/main.css',
        '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic'
    ];
    public $js = [
        'js/main.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        parent::init();

        // Если нужно, то для контроллеров создаем одноименные файлы стилей и скриптов
        // т.е.для контроллера UserController создаем /css/user.css & /js/user.js
        // Здесь их подключаем. Файлы /css/main.css & /js/main.js подключаются всегда
        $ctrlId = Yii::$app->controller->id;
        $controllerRelatedCssFile = '/css/controllers/' . $ctrlId . '.css';
        $controllerRelatedJsFile = '/js/controllers/' . $ctrlId . '.js';

        if (is_file($this->basePath . $controllerRelatedCssFile)) {
            $this->css[] = $controllerRelatedCssFile;
        }
        if (is_file($this->basePath . $controllerRelatedJsFile)) {
            $this->js[] = $controllerRelatedJsFile;
        }
    }
}
