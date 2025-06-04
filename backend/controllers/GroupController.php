<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use Yii;
use yii\web\BadRequestHttpException;
use app\models\MemberGroup;
use app\models\Group;
use app\models\Member;

class GroupController extends ActiveController
{
    public $modelClass = \app\models\Group::class;

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

    public function actionAddMember($id)
    {
        $memberId = Yii::$app->request->bodyParams['member_id'] ?? null;
        if (!$memberId) {
            throw new BadRequestHttpException('member_id required');
        }
        if (!Group::findOne($id) || !Member::findOne($memberId)) {
            throw new BadRequestHttpException('Invalid group or member');
        }
        $link = MemberGroup::findOne(['group_id' => $id, 'member_id' => $memberId]);
        if ($link) {
            return $link; // already assigned
        }
        $link = new MemberGroup(['group_id' => $id, 'member_id' => $memberId]);
        if ($link->save()) {
            Yii::$app->response->statusCode = 201;
            return $link;
        }
        Yii::$app->response->statusCode = 400;
        return $link->errors;
    }

    public function actionRemoveMember($id, $member_id)
    {
        $link = MemberGroup::findOne(['group_id' => $id, 'member_id' => $member_id]);
        if ($link) {
            $link->delete();
            Yii::$app->response->statusCode = 204;
            return null;
        }
        Yii::$app->response->statusCode = 404;
        return ['error' => 'Not found'];
    }
}
