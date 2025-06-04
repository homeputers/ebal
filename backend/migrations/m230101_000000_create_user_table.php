<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m230101_000000_create_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'role' => $this->string()->notNull()->defaultValue('user'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
