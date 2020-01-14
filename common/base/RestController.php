<?php

namespace common\base;

use filsh\yii2\oauth2server\filters\auth\CompositeAuth;
use yii\base\Model;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Response;

class RestController extends Controller
{

    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => $this->verbs(),
            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'only' => $this->only(),
                'except' => $this->except(),
                'authMethods' => [
                    ['class' => HttpBearerAuth::className()],
                    ['class' => QueryParamAuth::className(), 'tokenParam' => 'access_token'],
                ],
            ],
            'rateLimiter' => [
                'class' => RateLimiter::className(),
            ],
        ];
    }

    public function returnResultAndErrors(Model $model)
    {
        if(empty($model->getErrors())){
            return ['result'=>true];
        }
        $errors = [];
        foreach (array_keys($model->getErrors()) as $field) {
            $errors[] = ['field' => $field,'msgs' => $model->getErrors()["$field"]];
        }
        return ['result'=>false,'errors' => $errors];
    }

    protected function only() {
        return [
        ];
    }

    protected function except() {
        return [
        ];
    }

}