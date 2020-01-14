<?php

namespace api\modules\v1;

use yii\base\BootstrapInterface;
use yii\base\Module;

class V1 extends Module implements BootstrapInterface
{
    public $controllerNamespace = 'api\modules\v1\controllers';

    public function init()
    {
        parent::init();
    }


    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules(require('config/routes.php'));
    }

}
