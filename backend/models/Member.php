<?php
namespace app\models;

use yii\db\ActiveRecord;

class Member extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%member}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'email', 'phone'], 'string', 'max' => 255],
            ['email', 'email'],
        ];
    }

    public function getGroups()
    {
        return $this->hasMany(Group::class, ['id' => 'group_id'])
            ->viaTable('{{%member_group}}', ['member_id' => 'id']);
    }
}
