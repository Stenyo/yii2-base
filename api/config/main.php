<?php

use api\modules\v1\V1;
use api\security\UserCredentials;
use api\security\LanceUserCredentials;
use common\models\user\User;
use filsh\yii2\oauth2server\Module;
use OAuth2\GrantType\RefreshToken;
use yii\web\JsonResponseFormatter;
use yii\web\Response;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'defaultRoute' => 'v1/default',
    'timeZone' => 'America/Sao_Paulo',
    'modules' => [
        'v1' => [
            'class' => V1::className(),
        ],
        'oauth2' => [
            'class' => Module::className(),
            'tokenParamName' => 'accessToken',
            'tokenAccessLifetime' => 3600 * 24 * 90,
            'storageMap' => [
                'user_credentials' => \common\models\user\User::className(),
            ],
            'grantTypes' => [
                'user_credentials' => [
                    'class' => UserCredentials::class,
                ],
                /*
                'refresh_token' => [
                    'class' => RefreshToken::class,
                    'always_issue_new_refresh_token' => true
                ],
                */
            ],
            'components' => [
                'request' => function () {
                    return \filsh\yii2\oauth2server\Request::createFromGlobals();
                },
                'response' => [
                    'class' => \filsh\yii2\oauth2server\Response::class,
                ],
            ],
        ]
    ],
    'bootstrap' => [
        'log',
        'v1',
    ],
    'components' => [
        'user' => [
            'identityClass' => User::className(),
            'enableAutoLogin' => true,
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
            'rules' => include('routes.php'),
        ],
        'response' => [
            'class' => Response::className(),
            'formatters' => [
                Response::FORMAT_JSON => [
                    'class' => JsonResponseFormatter::className(),
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ]
    ],
    'params' => $params,
];
        
