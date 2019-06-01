<?php

$host = 'car-sharing.loc';
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'Каршеринг клиент',
    'language' => 'ru_RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'PGl5g0Qfp-5pd6hiZf8tJYD5d5prKiTn',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,
        
        'yandexMapsApi' => [
            'class' => 'mirocow\yandexmaps\Api',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'http://<subdomain:\w+>.' . $host => 'customer',
                'http://<subdomain:\w+>.' . $host . '/<action:\w+>' => 'customer/default/<action>',
                'http://<subdomain:\w+>.' . $host . '/<controller:\w+>' => 'customer/<controller>',
                'http://<subdomain:\w+>.' . $host . '/<controller:\w+>/<action:\w+>' => 'customer/<controller>/<action>',
                '<action:\w+>' => 'site/<action>',
                'password-reset' => 'site/password-reset',
                
                 
            ],
        ],
        
    ],
    'modules' => [
        'settings' => [
            'class' => 'app\modules\settings\Module',
        ],
        'customer' => [
            'class' => 'app\modules\customer\Module',
        ],
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
    ],
    'params' => $params,
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\Controller',
            'access' => ['@'], //глобальный доступ к фаил менеджеру @ - для авторизорованных , ? - для гостей , чтоб открыть всем ['@', '?']
            'disabledCommands' => ['netmount'], //отключение ненужных команд https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#commands
            'roots' => [
                [
                    'baseUrl'=>'@web',
                    'basePath'=>'@webroot',
                    'path' => 'uploads/photos',
                    'name' => 'Photos'
                ],
            ],
        ]
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
