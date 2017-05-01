<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m170501_073749_create_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string(10)->notNull(),
            'password' => $this->string(255)->notNull(),
            'authKey' => $this->string(255),
            'accessToken' => $this->string(255),
            'created_at' => $this->datetime()->notNull()

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
