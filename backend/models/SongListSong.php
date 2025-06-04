<?php
namespace app\models;

use yii\db\ActiveRecord;

class SongListSong extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%song_list_song}}';
    }

    public static function primaryKey()
    {
        return ['song_list_id', 'song_id'];
    }

    public function rules()
    {
        return [
            [['song_list_id', 'song_id', 'song_order'], 'required'],
            [['song_list_id', 'song_id', 'song_order'], 'integer'],
            [['actual_key', 'version'], 'string', 'max' => 255],
            [['notes'], 'string'],
        ];
    }

    public function getSongList()
    {
        return $this->hasOne(SongList::class, ['id' => 'song_list_id']);
    }

    public function getSong()
    {
        return $this->hasOne(Song::class, ['id' => 'song_id']);
    }
}
