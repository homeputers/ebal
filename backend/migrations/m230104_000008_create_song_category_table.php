<?php
use yii\db\Migration;

class m230104_000008_create_song_category_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%song_category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'description' => $this->text(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%song_category}}');
    }
}
