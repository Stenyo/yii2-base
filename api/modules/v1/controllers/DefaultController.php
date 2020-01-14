<?php

namespace api\modules\v1\controllers;

use common\actions\CepAction;
use common\base\RestController;
use common\models\Config;
use yii\web\NotFoundHttpException;

class DefaultController extends RestController
{
    protected function except()
    {
        return [
            'version',
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'cep-search' => CepAction::class,
        ];
    }
    public function actionIndex()
    {
        throw new NotFoundHttpException("Unsuported action request", 100);
    }

    public function actionVersion()
    {
        return '1.0';
    }

}
