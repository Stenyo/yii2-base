<?php

namespace console\seeder\tables;

use common\models\address\State;
use common\models\notification\Notification;
use common\models\user\User;
use console\seeder\DatabaseSeeder;
use console\seeder\TableSeeder;
use Yii;

class UserTableSeeder extends TableSeeder
{

    function run()
    {
        $this->disableForeginKeyChecks();

        $this->truncateTable('{{%user}}');


        loop(function ($i) {
            $face_id = $this->faker->unique()->numerify('######');
            $this->insert('{{%user}}', [
                'email' => "user$i@gmail.com",
                'name' => $this->faker->name,
                'auth_key' => Yii::$app->security->generateRandomString(),
                'password_hash' => Yii::$app->security->generatePasswordHash('user', 6),
                'facebook_id' =>  $face_id,
                'document' => $this->faker->numerify('##############'),
                'street' => $this->faker->streetName,
                'number' => $this->faker->numerify('###'),
                'neighborhood' => $this->faker->name,
                'zip_code' => $this->faker->postcode,
                'complement' => 'Complemento',
                //'state_code' =>
                'city' => $this->faker->city,
                //TODO country
                'status' => '10',
                'created_at' => time(),
                'updated_at' => time(),
            ]);

        }, DatabaseSeeder::USER_COUNT);

        $this->enableForeginKeyChecks();
    }
}