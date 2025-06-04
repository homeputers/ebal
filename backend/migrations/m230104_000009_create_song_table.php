<?php
use yii\db\Migration;

class m230104_000009_create_song_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%song}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'title' => $this->string()->notNull(),
            'original_key' => $this->string(),
            'original_author' => $this->string(),
        ]);
        $this->addForeignKey('fk_song_category', '{{%song}}', 'category_id', '{{%song_category}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_song_category', '{{%song}}');
        $this->dropTable('{{%song}}');
    }
}
