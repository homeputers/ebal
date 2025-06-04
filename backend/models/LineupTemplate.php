<?php
namespace app\models;

use yii\db\ActiveRecord;

class LineupTemplate extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%lineup_template}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    public function getTemplateGroups()
    {
        return $this->hasMany(LineupTemplateGroup::class, ['template_id' => 'id']);
    }

    public function getGroups()
    {
        return $this->hasMany(Group::class, ['id' => 'group_id'])->via('templateGroups');
    }
}
