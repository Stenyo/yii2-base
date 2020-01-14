<?php

use common\components\Hash;
use common\components\managers\Config;

use common\models\Config as ConfigModel;
use yii\caching\FileCache;
use yii\i18n\Formatter;

return [
    'name' => 'Base Yii2',
    'version' => '1.0.0',
    'language' => 'pt-BR',
    'timeZone' => 'America/Sao_Paulo',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'fcm' => [
            'class' => 'understeam\fcm\Client',
            'apiKey' => 'AAAAq6EmS4U:APA91bE4EAZfXX8OKxm5S5-b0R36pgODd1VmvBOgKznE-xASwaIY5pTbdtxqFKTj4g2nFb03-JGH5ih02Un3bVbF3CNB38NaN1YT8JSbfxWkWg-nABCffkeaLfuTQEQutcnGtDyPdq-skGa3r_jNUFlTAY9foTfx0A', // Server API Key (you can get it here: https://firebase.google.com/docs/server/setup#prerequisites)
        ],
        's3' => [
            'class' => 'frostealth\yii2\aws\s3\Service',
            'credentials' => [ // Aws\Credentials\CredentialsInterface|array|callable
                'key' => 'AKIAJCNFBHFZBRFRFDDA',
                'secret' => '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',
            ],
            'region' => 'us-east-1',
            'defaultBucket' => 'lanceminimo.com',
            'defaultAcl' => 'public-read',
        ],
        'formatter' => [
            'class' => Formatter::class,
            'thousandSeparator' => '.',
            'decimalSeparator' => ',',
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
        ],
        'config' => [
            'class' => Config::class,
            'modelClass' => ConfigModel::class,
            'cache' => true,
        ],
        'hash' => [
            'class' => Hash::class,
            'salt' => 'HayrGLrgJnk26V5zHltB',
            'minHashLenght' => 6,
            'alphabet' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'email-smtp.us-east-1.amazonaws.com',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'username' => 'AKIAISV6PLQWQ6YEOVLA',
                'password' => 'AsdLyi6CbrkbLQVuzUIvQWV1/M4WWtUhwocODvtjxtA9',
                'port' => '465', // Port 25 is a very common port too
                'encryption' => 'ssl', // It is often used, check your provider or mail server specs
            ],
            /*'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'br132.hostgator.com.br',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'username' => 'contato@lanceminimo.com',
                'password' => 'facamsw114lance',
                'port' => '465', // Port 25 is a very common port too
                'encryption' => 'ssl', // It is often used, check your provider or mail server specs
            ],*/
        ],
    ],
];
