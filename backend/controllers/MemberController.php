<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class MemberController extends ActiveController
{
    public $modelClass = \app\models\Member::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function () {
                        return Yii::$app->user->identity->role === 'admin';
                    },
                ],
            ],
        ];
        return $behaviors;
    }
}
