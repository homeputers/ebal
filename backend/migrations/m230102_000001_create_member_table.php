<?php
use yii\db\Migration;

class m230102_000001_create_member_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%member}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->unique(),
            'phone' => $this->string(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%member}}');
    }
}
