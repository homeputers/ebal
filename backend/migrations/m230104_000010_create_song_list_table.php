<?php
use yii\db\Migration;

class m230104_000010_create_song_list_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%song_list}}', [
            'id' => $this->primaryKey(),
            'lineup_id' => $this->integer()->notNull(),
            'author' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('fk_song_list_lineup', '{{%song_list}}', 'lineup_id', '{{%lineup}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_song_list_lineup', '{{%song_list}}');
        $this->dropTable('{{%song_list}}');
    }
}
