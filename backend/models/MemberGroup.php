<?php
namespace app\models;

use yii\db\ActiveRecord;

class MemberGroup extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%member_group}}';
    }

    public static function primaryKey()
    {
        return ['member_id', 'group_id'];
    }
}
