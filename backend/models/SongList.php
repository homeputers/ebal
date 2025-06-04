<?php
namespace app\models;

use yii\db\ActiveRecord;

class SongList extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%song_list}}';
    }

    public function rules()
    {
        return [
            [['lineup_id', 'author'], 'required'],
            [['lineup_id'], 'integer'],
            [['author'], 'string', 'max' => 255],
        ];
    }

    public function getLineup()
    {
        return $this->hasOne(Lineup::class, ['id' => 'lineup_id']);
    }

    public function getSongListSongs()
    {
        return $this->hasMany(SongListSong::class, ['song_list_id' => 'id']);
    }

    public function getSongs()
    {
        return $this->hasMany(Song::class, ['id' => 'song_id'])->via('songListSongs');
    }
}
