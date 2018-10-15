<?php
return [
    'id' => 'news-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=yii2base_test',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
        ],
    ],
];
