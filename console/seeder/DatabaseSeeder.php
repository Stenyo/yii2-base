<?php

namespace console\seeder;




use console\seeder\tables\UserTableSeeder;

class DatabaseSeeder extends TableSeeder
{

    const ADMIN_COUNT = 1;
    const USER_COUNT = 40;


    public function run()
    {
        (new UserTableSeeder())->run();
    }

}