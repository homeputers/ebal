<?php
namespace app\models;

use yii\db\ActiveRecord;

use app\models\MemberGroup;

class Member extends ActiveRecord
{
    /**
     * @var int[] IDs of groups the member belongs to
     */
    public array $group_ids = [];
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
            ['group_ids', 'each', 'rule' => ['integer']],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['group_ids'] = function (self $model) {
            return array_map(fn($g) => $g->id, $model->groups);
        };
        return $fields;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->group_ids = array_map(fn($g) => $g->id, $this->groups);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->group_ids !== null) {
            MemberGroup::deleteAll(['member_id' => $this->id]);
            foreach ($this->group_ids as $gid) {
                $mg = new MemberGroup(['member_id' => $this->id, 'group_id' => $gid]);
                $mg->save();
            }
        }
    }

    public function getGroups()
    {
        return $this->hasMany(Group::class, ['id' => 'group_id'])
            ->viaTable('{{%member_group}}', ['member_id' => 'id']);
    }
}
