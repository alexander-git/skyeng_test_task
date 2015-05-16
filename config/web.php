<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'name' => 'dictionary',
    'language' => 'ru',
    'id' => 'app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'VbYZEECcoYmZ0GUbPm3NIGbDUjDsSH01',
            'enableCsrfValidation' => false,
            'enableCookieValidation' => true
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
                '/' => 'test/index',    
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],      
        'errorHandler' => [

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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // Оставим пока debug-версию, но отключим Yii-Debugger.
    //$config['bootstrap'][] = 'debug';
    //$config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
