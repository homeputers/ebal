<?php
use yii\db\Migration;

class m230102_000003_create_member_group_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%member_group}}', [
            'member_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('pk_member_group', '{{%member_group}}', ['member_id', 'group_id']);
        $this->addForeignKey('fk_member_group_member', '{{%member_group}}', 'member_id', '{{%member}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_member_group_group', '{{%member_group}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_member_group_member', '{{%member_group}}');
        $this->dropForeignKey('fk_member_group_group', '{{%member_group}}');
        $this->dropTable('{{%member_group}}');
    }
}
