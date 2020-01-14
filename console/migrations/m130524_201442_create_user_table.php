<?php

use console\migrations\base\Migration;

class m130524_201442_create_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'android_token' => $this->string(),
            'auth_key' => $this->string(32),
            'facebook_id' => $this->bigInteger(64)->unique(),

            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            //informations
            'document' => $this->string(11)->defaultValue(""),
            'phone' => $this->string(14)->defaultValue(""),
            'birthday' => $this->date(),

            'street' => $this->string(),
            'number' => $this->string(40),
            'neighborhood' => $this->string(),
            'zip_code' => $this->string(8),
            'complement' => $this->string(),
            'city' => $this->string(),
            'state_code' => $this->char(2),
            'country' => $this->string(),

            'status' => $this->integer(0)->notNull(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->getDefaultTableOptions());

        $this->createIndex('idx-user-facebook_id', '{{%user}}', 'facebook_id', true);

    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
