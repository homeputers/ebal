<?php
namespace app\models;

use yii\db\ActiveRecord;

class Lineup extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%lineup}}';
    }

    public function rules()
    {
        return [
            [['service_datetime'], 'required'],
            [['service_datetime'], 'safe'],
            [['template_id'], 'integer'],
        ];
    }

    public function getTemplate()
    {
        return $this->hasOne(LineupTemplate::class, ['id' => 'template_id']);
    }

    public function getLineupMembers()
    {
        return $this->hasMany(LineupMember::class, ['lineup_id' => 'id']);
    }

    public function getMembers()
    {
        return $this->hasMany(Member::class, ['id' => 'member_id'])->via('lineupMembers');
    }
}
