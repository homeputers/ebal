<?php
use yii\db\Migration;

class m230103_000005_create_lineup_template_group_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%lineup_template_group}}', [
            'template_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull()->defaultValue(1),
        ]);
        $this->addPrimaryKey('pk_lineup_template_group', '{{%lineup_template_group}}', ['template_id','group_id']);
        $this->addForeignKey('fk_lt_group_template', '{{%lineup_template_group}}', 'template_id', '{{%lineup_template}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_lt_group_group', '{{%lineup_template_group}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_lt_group_group', '{{%lineup_template_group}}');
        $this->dropForeignKey('fk_lt_group_template', '{{%lineup_template_group}}');
        $this->dropTable('{{%lineup_template_group}}');
    }
}
