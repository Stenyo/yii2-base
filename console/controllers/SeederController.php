<?php


namespace console\controllers;


use console\seeder\DatabaseSeeder;
use Yii;
use yii\console\Controller;

class SeederController extends Controller
{

    /**
     * @var string the default command action.
     */
    public $defaultAction = 'seed';

    public function actionSeed($name = null)
    {
        if ($name) {
            $seederClass = "console\\seeder\\tables\\{$name}TableSeeder";
            if (class_exists($seederClass)) {
                (new $seederClass)->run();
            } else {
                $this->stdout("Class {$seederClass} not exists");
            }
        } else {
            (new DatabaseSeeder())->run();
        }
    }
    
}