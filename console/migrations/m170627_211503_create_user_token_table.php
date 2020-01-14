<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_token`.
 */
class m170627_211503_create_user_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%user_token}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'token' => $this->string()->notNull(),
            'refresh_token' => $this->string()->notNull(),
            'expires' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_token}}');
    }
}
