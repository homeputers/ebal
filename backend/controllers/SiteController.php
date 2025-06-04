<?php
namespace app\controllers;

use yii\rest\Controller;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class SiteController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['public', 'login'],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['dashboard', 'admin'],
            'rules' => [
                [
                    'actions' => ['dashboard'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'actions' => ['admin'],
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

    public function actionPublic()
    {
        return ['message' => 'Public endpoint'];
    }

    public function actionLogin()
    {
        $body = Yii::$app->request->bodyParams;
        $user = \app\models\User::findOne(['username' => $body['username'] ?? null]);
        if ($user && Yii::$app->security->validatePassword($body['password'] ?? '', $user->password_hash)) {
            return ['token' => $user->generateJwt()];
        }
        Yii::$app->response->statusCode = 401;
        return ['error' => 'Invalid credentials'];
    }

    public function actionDashboard()
    {
        return ['message' => 'Dashboard', 'user' => Yii::$app->user->identity->username];
    }

    public function actionAdmin()
    {
        return ['message' => 'Admin area'];
    }
}
