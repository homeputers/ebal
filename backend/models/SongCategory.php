<?php
namespace app\models;

use yii\db\ActiveRecord;

class SongCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%song_category}}';
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

    public function getSongs()
    {
        return $this->hasMany(Song::class, ['category_id' => 'id']);
    }
}
