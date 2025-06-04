<?php
use yii\db\Migration;

class m230104_000011_create_song_list_song_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%song_list_song}}', [
            'song_list_id' => $this->integer()->notNull(),
            'song_id' => $this->integer()->notNull(),
            'song_order' => $this->integer()->notNull(),
            'actual_key' => $this->string(),
            'version' => $this->string(),
            'notes' => $this->text(),
        ]);
        $this->addPrimaryKey('pk_song_list_song', '{{%song_list_song}}', ['song_list_id','song_id']);
        $this->addForeignKey('fk_song_list_song_list', '{{%song_list_song}}', 'song_list_id', '{{%song_list}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_song_list_song_song', '{{%song_list_song}}', 'song_id', '{{%song}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_song_list_song_song', '{{%song_list_song}}');
        $this->dropForeignKey('fk_song_list_song_list', '{{%song_list_song}}');
        $this->dropTable('{{%song_list_song}}');
    }
}
