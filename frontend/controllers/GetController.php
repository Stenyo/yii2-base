<?php


namespace frontend\controllers;


use common\models\user\User;
use Facebook\Facebook;
use Yii;
use yii\web\Controller;

class GetController extends Controller
{

    public function actionInvite($code, $access_token)
    {
        $inviter = User::findByCode($code);


        if ($access_token != null) {
            $fb = new Facebook([
                'app_id' => FACEBOOK_CLIENT_ID,
                'app_secret' => FACEBOOK_CLIENT_SECRET,
                'default_access_token' => $access_token,
            ]);

            $response = $fb->get('/me?fields=name,email');
            $userGraph = $response->getGraphUser();

            $model = new User();

            //load from post

            $model->name = $userGraph->getName() . ' ' . $userGraph->getLastName();
            $model->email = $userGraph->getEmail();


            if(true) {
                $this->redirect(['app']);
            }

            return $this->render('register');
        } else {
            return $this->render('invite');
        }
    }

    public function actionApp() {
        return $this->render('app');
    }

}