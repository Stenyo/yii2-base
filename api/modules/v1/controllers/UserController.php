<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\PublicUser;
use common\base\RestController;
use common\models\Config;
use common\models\user\User;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class UserController extends RestController
{

    protected function except()
    {
        return [
            'facebook-auth',
            'create-auth',
            'email-auth',
            'forget-password',
            'list'
        ];
    }

    public function actionUpdateTokenAndroid()
    {
        $model = $this->findModel(Yii::$app->user->id);

        $model->android_token = Yii::$app->getRequest()->getBodyParam('androidToken');

        $model->save(true, ['android_token']);

        return $this->returnResultAndErrors($model);
    }

    public function actionMe()
    {
        /** @var User $modelUser */
        $modelUser = Yii::$app->user->identity;
        return $modelUser;
    }



    public function actionView($id)
    {
        $model = PublicUser::findIdentity($id);
        return $model;
    }




    public function actionCreateAuth()
    {
        $name =  Yii::$app->getRequest()->getBodyParam('name');
        $email = Yii::$app->getRequest()->getBodyParam('email');
        $psw = Yii::$app->getRequest()->getBodyParam('password');
        $androidToken = Yii::$app->getRequest()->getBodyParam('androidToken');

        $model = new User();
        $model->name = $name;
        $model->email = $email;
        $model->password_hash = $psw;
        $model->android_token = $androidToken;

        if($model->save(true,['android_token','name','email','password_hash','status', 'created_at', 'updated_at'])){

            return ['result' => true];
        }else
            return $this->returnResultAndErrors($model);
    }

    public function actionFacebookAuth()
    {
        $accessToken = Yii::$app->getRequest()->getBodyParam('accessToken');
        $androidToken = Yii::$app->getRequest()->getBodyParam('androidToken');
        $result = [
            'result' => false,
        ];

        try {
            $fb = new Facebook([
                'app_id' => FACEBOOK_CLIENT_ID,
                'app_secret' => FACEBOOK_CLIENT_SECRET,
                'default_access_token' => $accessToken,
            ]);
            $response = $fb->get('/me?fields=name,email,link');
            $userGraph = $response->getGraphUser();

            // se usuário já existir retorna erro
            if (($model = User::findByFacebookId($userGraph->getId())) != null) {
                $model->android_token = $androidToken;
                $model->facebook_link = $userGraph->getLink();
                $model->save(true, ['android_token', 'facebook_link']);
                $result['result'] = true;
            } else {
                $model = new User();
                $model->email = $userGraph->getEmail();
                $model->facebook_id = $userGraph->getId();
                $model->name = $userGraph->getName();
                $model->status = User::STATUS_USER_ACTIVE;
                $model->android_token = $androidToken;

                if ($model->save(true, ['email', 'facebook_id', 'android_token', 'name', 'status', 'created_at', 'updated_at'])) {
                    $result['result'] = true;
                } else {
                    $result = $this->returnResultAndErrors($model);
                }
            }
        } catch (FacebookSDKException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $result;
    }

    public function actionUpdate()
    {
        $model = $this->findModel(Yii::$app->user->id);
        $model->scenario = User::SCENARIO_UPDATE_API_APP;
        $scenarios = $model->scenarios();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->save(true, $scenarios[User::SCENARIO_UPDATE_API_APP]);
        return $this->returnResultAndErrors($model);
    }


    public function actionUpdatePassword()
    {
        $model = $this->findModel(Yii::$app->user->id);

        $model->password = Yii::$app->getRequest()->getBodyParam('password');

        $model->save(true, ['password_hash']);

        return $this->returnResultAndErrors($model);
    }

    public function actionForgetPassword()
    {
        $user =  User::findByEmail(Yii::$app->getRequest()->getBodyParam('email'));
        if(empty($user))
            return ['result'=>false,'errors' =>[['field' => "Email não encontrado",'msgs' => ["Verifique o email!"]]]];
        $user->forgetPassword();
        return $this->returnResultAndErrors($user);
    }




    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList()
    {
        return User::find()->all();
    }

}