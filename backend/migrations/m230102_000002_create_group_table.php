<?php
use yii\db\Migration;

class m230102_000002_create_group_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'description' => $this->text(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%group}}');
    }
}
