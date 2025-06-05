<?php
use yii\db\Migration;
class m230105_000012_insert_admin_user extends Migration
{
    public function safeUp()
    {
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'password_hash' => \Yii::$app->security->generatePasswordHash('admin123'),
            'role' => 'admin',
        ]);
    }

    public function safeDown()
    {
        $this->delete('{{%user}}', ['username' => 'admin']);
    }
}
