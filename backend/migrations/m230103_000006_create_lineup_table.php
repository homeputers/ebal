<?php
use yii\db\Migration;

class m230103_000006_create_lineup_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%lineup}}', [
            'id' => $this->primaryKey(),
            'service_datetime' => $this->dateTime()->notNull(),
            'template_id' => $this->integer(),
        ]);
        $this->addForeignKey('fk_lineup_template', '{{%lineup}}', 'template_id', '{{%lineup_template}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_lineup_template', '{{%lineup}}');
        $this->dropTable('{{%lineup}}');
    }
}
