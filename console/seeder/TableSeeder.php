<?php

namespace console\seeder;

use Faker\Provider\pt_BR\Address;
use Faker\Provider\pt_BR\Company;
use Faker\Provider\pt_BR\Person;
use Faker\Provider\pt_BR\PhoneNumber;
use Yii;
use yii\db\Migration;

abstract class TableSeeder extends Migration
{
    
    /**
     * @var \Faker\Generator
     */
    public $faker;

    public function __construct(array $config = [])
    {
        $this->faker = \Faker\Factory::create();
        $this->faker->addProvider(new Address($this->faker));
        $this->faker->addProvider(new Company($this->faker));
        $this->faker->addProvider(new Person($this->faker));
        $this->faker->addProvider(new PhoneNumber($this->faker));

        parent::__construct($config);
    }

    abstract function run();

    public function disableForeginKeyChecks()
    {
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
    }

    public function enableForeginKeyChecks()
    {
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
    }
}