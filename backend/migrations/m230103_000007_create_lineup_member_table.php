<?php
use yii\db\Migration;

class m230103_000007_create_lineup_member_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%lineup_member}}', [
            'lineup_id' => $this->integer()->notNull(),
            'member_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('pk_lineup_member', '{{%lineup_member}}', ['lineup_id','member_id']);
        $this->addForeignKey('fk_lineup_member_lineup', '{{%lineup_member}}', 'lineup_id', '{{%lineup}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_lineup_member_member', '{{%lineup_member}}', 'member_id', '{{%member}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_lineup_member_group', '{{%lineup_member}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_lineup_member_group', '{{%lineup_member}}');
        $this->dropForeignKey('fk_lineup_member_member', '{{%lineup_member}}');
        $this->dropForeignKey('fk_lineup_member_lineup', '{{%lineup_member}}');
        $this->dropTable('{{%lineup_member}}');
    }
}
