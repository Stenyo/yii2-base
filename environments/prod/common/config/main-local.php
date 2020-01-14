<?php

define('FACEBOOK_CLIENT_ID', '1847155495603931');
define('FACEBOOK_CLIENT_SECRET', 'f9fbf81398f3a896ac4bf891b7fff56a');
define('CIELO_MERCHANT_ID', '5537f8c6-3bf5-4ac5-a8c2-db531eb3c313');
define('CIELO_MERCHANT_KEY', 'KxYuWJvfRT31IcgRolzxtD59g2LccveU6i48OhZh');
define('CIELO_MODE', 'production');



return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=database.ccdslczvlhfu.us-east-1.rds.amazonaws.com;dbname=lanceminimo',
            'username' => 'lanceminimo',
            'password' => 'facamsw114lanceminimo',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
