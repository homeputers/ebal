<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use Yii;
use yii\web\BadRequestHttpException;
use app\models\LineupMember;
use app\models\Lineup;
use app\models\Member;
use app\models\Group;

class LineupController extends ActiveController
{
    public $modelClass = Lineup::class;

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
        $groupId = Yii::$app->request->bodyParams['group_id'] ?? null;
        if (!$memberId || !$groupId) {
            throw new BadRequestHttpException('member_id and group_id required');
        }
        if (!Lineup::findOne($id) || !Member::findOne($memberId) || !Group::findOne($groupId)) {
            throw new BadRequestHttpException('Invalid lineup, member or group');
        }
        $model = LineupMember::findOne(['lineup_id' => $id, 'member_id' => $memberId]);
        if ($model) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'Member already assigned'];
        }
        $model = new LineupMember(['lineup_id' => $id, 'member_id' => $memberId, 'group_id' => $groupId]);
        if ($model->save()) {
            Yii::$app->response->statusCode = 201;
            return $model;
        }
        Yii::$app->response->statusCode = 400;
        return $model->errors;
    }

    public function actionUpdateMember($id, $member_id)
    {
        $newMemberId = Yii::$app->request->bodyParams['member_id'] ?? $member_id;
        $groupId = Yii::$app->request->bodyParams['group_id'] ?? null;
        $model = LineupMember::findOne(['lineup_id' => $id, 'member_id' => $member_id]);
        if (!$model) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Not found'];
        }
        if ($newMemberId != $member_id) {
            if (!Member::findOne($newMemberId)) {
                throw new BadRequestHttpException('Invalid member_id');
            }
            $model->delete();
            $model = new LineupMember(['lineup_id' => $id, 'member_id' => $newMemberId, 'group_id' => $groupId ?? $model->group_id]);
            if ($model->save()) {
                return $model;
            }
            Yii::$app->response->statusCode = 400;
            return $model->errors;
        }
        if ($groupId !== null) {
            if (!Group::findOne($groupId)) {
                throw new BadRequestHttpException('Invalid group_id');
            }
            $model->group_id = $groupId;
            if ($model->save()) {
                return $model;
            }
            Yii::$app->response->statusCode = 400;
            return $model->errors;
        }
        throw new BadRequestHttpException('Nothing to update');
    }

    public function actionRemoveMember($id, $member_id)
    {
        $model = LineupMember::findOne(['lineup_id' => $id, 'member_id' => $member_id]);
        if ($model) {
            $model->delete();
            Yii::$app->response->statusCode = 204;
            return null;
        }
        Yii::$app->response->statusCode = 404;
        return ['error' => 'Not found'];
    }
}
