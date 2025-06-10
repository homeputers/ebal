<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return '{{%user}}';
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $data = Yii::$app->jwt->decode($token);
        return static::findOne($data['id'] ?? null);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function generateJwt(): string
    {
        $payload = [
            'id' => $this->id,
            'role' => $this->role, 
        ];
        return Yii::$app->jwt->encode($payload);
    }
}
