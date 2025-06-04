<?php
use yii\db\Migration;

class m230103_000004_create_lineup_template_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%lineup_template}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%lineup_template}}');
    }
}
