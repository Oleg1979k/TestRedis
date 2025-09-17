<?php

return [
    'aliases' => [
    '@bower' => '@vendor/bower-asset',
],
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'secret-key',
        ],
         'cache' => [
        'class' => \yii\redis\Cache::class, // ✅ правильно
        'redis' => [
            'hostname' => 'redis',
            'port' => 6379,
            'database' => 0,
        ],
    ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,  // пока без красивых URL
            'showScriptName' => true,
        ],
        'assetManager' => [
            'linkAssets' => true, // вместо копирования Yii будет делать symlink — не всегда возможно в Windows, но попробуйте
            'dirMode' => 0777,
            'fileMode' => 0666,
            'forceCopy' => false,
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
        'db' => require __DIR__ . '/db.php',
    ],
];
