<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
$spotify = require(__DIR__ . '/spotify.php');
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'spotifySession' => function () use ($spotify){
          return new SpotifyWebAPI\Session(...$spotify);
        },
        'redis' => [
          'class' => 'yii\redis\Connection',
          'hostname' => 'localhost',
          'port' => 6379,
          'database' => 0,
        ],
        'request' => [
            'cookieValidationKey' => '94u9mu09034292039u4c3209744890530943jpo',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'user',
                'extraPatterns' => [
                    'GET authorize-url' => 'authorize-url',
                    'GET refresh-token' => 'refresh-token',
                    'GET me' => 'me',
                    'OPTIONS me' => 'options',
                    'OPTIONS refresh-token' => 'options'
                  ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'recommendation',
                'extraPatterns' => [
                    'OPTIONS' => 'options'
                  ],
              ],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['oauth'=>'oauth'],
                'extraPatterns' => [
                    'GET,OPTIONS  callback' => 'callback',
                  ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'track',
                'extraPatterns' => [
                    'GET stats' => 'stats',
                    'GET <id:\w+>' => 'view',
                    'OPTIONS stats' => 'options',
                    'OPTIONS <id:\w+>' => 'options'
                  ],
              ],
              ['class' => 'yii\rest\UrlRule', 'controller' => 'artist',
              'extraPatterns' => [
                  'GET stats' => 'stats',
                  'GET <id:\w+>' => 'view',
                  'OPTIONS stats' => 'options',
                  'OPTIONS <id:\w+>' => 'options',

                ],
            ],
          ],
        ],
    ],
    'params' => $params,
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
