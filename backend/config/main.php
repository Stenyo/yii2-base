<?php
use common\models\user\Admin;
use kartik\date\DatePicker;
use kartik\datecontrol\Module as DateControlModule;
use kartik\datetime\DateTimePicker;
use kartik\grid\Module as GridViewModule;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'timeZone' => 'America/Sao_Paulo',
    'modules' =>
        [
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
        'datecontrol' => [
            'class' => DateControlModule::className(),

            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
                DateControlModule::FORMAT_DATE => 'dd/MM/yyyy',
                DateControlModule::FORMAT_TIME => 'HH:mm:ss',
                DateControlModule::FORMAT_DATETIME => 'dd/MM/yyyy HH:mm',
            ],

            // format settings for saving each date attribute (PHP format example)
            'saveSettings' => [
                DateControlModule::FORMAT_DATE => 'php:Y-m-d',
                DateControlModule::FORMAT_TIME => 'php:H:i:s',
                DateControlModule::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
            ],

            // set your display timezone
            'displayTimezone' => 'America/Recife',
            // set your timezone for date saved to db
            'saveTimezone' => 'UTC',
            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,
            // use ajax conversion for processing dates from display format to save format.
            'ajaxConversion' => true,
            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                DateControlModule::FORMAT_DATE => [
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose' => true,
                    ]],
                DateControlModule::FORMAT_DATETIME => [
                    'type' => DateTimePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ],
                ],
                DateControlModule::FORMAT_TIME => [
                ],
            ],
            // custom widget settings that will be used to render the date input instead of kartik\widgets,
            // this will be used when autoWidget is set to false at module or widget level.
            'widgetSettings' => [
                DateControlModule::FORMAT_DATE => [
                    'class' => DatePicker::className(), // example
                    'options' => [
                        'dateFormat' => 'php:d/M/Y',
                        'options' => [
                            'class' => 'form-control',
                        ],
                    ]
                ]
            ]
        ],
    ],
    'components' => [
        'assetManager' => [
            'assetMap' => [
                'js/jquery.maskMoney.min.js' => '@web/js/jquery.maskMoney.js'
            ],
        ],
        'user' => [
            'identityClass' => Admin::className(),
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
