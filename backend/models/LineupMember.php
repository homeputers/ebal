<?php
namespace app\models;

use yii\db\ActiveRecord;

class LineupMember extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%lineup_member}}';
    }

    public static function primaryKey()
    {
        return ['lineup_id', 'member_id'];
    }

    public function rules()
    {
        return [
            [['lineup_id', 'member_id', 'group_id'], 'required'],
            [['lineup_id', 'member_id', 'group_id'], 'integer'],
        ];
    }

    public function getLineup()
    {
        return $this->hasOne(Lineup::class, ['id' => 'lineup_id']);
    }

    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
    }
}
