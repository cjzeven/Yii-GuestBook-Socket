<?php

use yii\db\Migration;

/**
 * Handles the creation of table `guestbook`.
 */
class m170428_024305_create_guestbook_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('guestbook', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'email' => $this->string(50)->notNull(),
            'read' => $this->boolean()->defaultValue(0),
            'created_at' => $this->datetime()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('guestbook');
    }
}
