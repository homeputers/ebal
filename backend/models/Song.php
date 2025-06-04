<?php
namespace app\models;

use yii\db\ActiveRecord;

class Song extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%song}}';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'original_key', 'original_author'], 'string', 'max' => 255],
            [['category_id'], 'integer'],
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(SongCategory::class, ['id' => 'category_id']);
    }
}
