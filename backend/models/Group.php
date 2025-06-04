<?php
namespace app\models;

use yii\db\ActiveRecord;

class Group extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%group}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['name'], 'unique'],
        ];
    }

    public function getMembers()
    {
        return $this->hasMany(Member::class, ['id' => 'member_id'])
            ->viaTable('{{%member_group}}', ['group_id' => 'id']);
    }
}
