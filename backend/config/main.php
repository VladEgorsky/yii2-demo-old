<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$jquery_js_cdn = YII_DEBUG ? '//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js'
    : '//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js';
$bootstrap_js_cdn = YII_DEBUG ? '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js'
    : '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js';
$bootstrap_css_cdn = YII_DEBUG ? '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css'
    : '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css';

return [
    'id' => 'news-backend',
    'name' => 'The Siberian Times',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'backend\controllers',
    'container' => require __DIR__ . '/container.php',
    'modules' => [
        'rbac'       => [
            'class' => 'backend\components\rbac\Module',
//            'as access' => [
//                'class' => yii2mod\rbac\filters\AccessControl::class
//            ],
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
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [$jquery_js_cdn],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'css' => [$bootstrap_css_cdn],
                    'js' => [$bootstrap_js_cdn],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-blue',
                ],
                'yii2mod\alert\AlertAsset' => [
                    'css' => [
                        'dist/sweetalert.css',
                        'themes/twitter/twitter.css',
                    ]
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            //'defaultRoles' => ['guest', 'user'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.rbac' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/rbac/messages',
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'news-backend_-6n0mKATWQnIRfmaW4DcROol95yGP',
            'csrfParam' => '_csrf-backend',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'news-backend',
        ],
        'user' => [
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<module:(rbac)>/<controller:[\w-]+>/<action:[\w-]+>/<id:[\w-]>' => '<module>/<controller>/<action>',
                '<module:(rbac)>/<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module:(rbac)>/<controller:[\w-]+>/<action:[\w-]+>' => '<module>/<controller>/<action>',
                '<module:(rbac)>/<controller:[\w-]+>' => 'rbac/<controller>/index',
                '<controller:(page)>/<view:[\w-]+>' => 'site/page',
                '<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[\w-]+>/<action:[\w-]+>' => '<controller>/<action>',
                '<controller:[\w-]+>' => '<controller>/index',
            ],
        ],
    ],
    'as afterAction' => [
        'class' => '\common\components\LastVisitBehavior'
    ],
    'params' => $params,
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\Controller',
            'connectOptions' => [
                'uploadMaxSize' => '500M',
            ],
            'plugin' => [
                [
                    'class' => '\mihaildev\elfinder\plugin\Sluggable',
                    'lowercase' => true,
                    'replacement' => '-',
                ],
            ],
            'roots' => [
                [
                    'baseUrl' => '/uploads',
                    'basePath' => '@frontend/web/uploads',
                    'path' => '/',
                    'name' => 'Downloads',
                    'plugin' => [
                        'Sluggable' => [
                            'lowercase' => false,
                        ],
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
