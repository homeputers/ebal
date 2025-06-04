<?php
namespace app\models;

use yii\db\ActiveRecord;

class LineupTemplateGroup extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%lineup_template_group}}';
    }

    public static function primaryKey()
    {
        return ['template_id', 'group_id'];
    }

    public function rules()
    {
        return [
            [['template_id', 'group_id', 'count'], 'required'],
            [['template_id', 'group_id', 'count'], 'integer'],
        ];
    }

    public function getTemplate()
    {
        return $this->hasOne(LineupTemplate::class, ['id' => 'template_id']);
    }

    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
    }
}
