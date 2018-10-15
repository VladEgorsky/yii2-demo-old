<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'id' => 'news-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
    ],
    'controllerNamespace' => 'console\controllers',
    'modules' => [
        'rbac' => [
            'class' => 'yii2mod\rbac\ConsoleModule'
        ],
        'yii2images' => [
            'class'                   => 'rico\yii2images\Module',
            'imagesStorePath'         => '@frontend/web/uploads',
            'imagesCachePath'         => '@frontend/web/uploads/cache',
            'graphicsLibrary'         => 'GD',
            'imageCompressionQuality' => 85,
        ],
    ],

    'components' => [
        'log'        => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'baseUrl'         => '/',
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                '<id:([0-9])+>/images/image-by-item-and-alias' => 'yii2images/images/image-by-item-and-alias',
            ],
        ],
    ],
    'params' => $params,
];
