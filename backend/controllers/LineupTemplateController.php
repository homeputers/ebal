<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use Yii;
use yii\web\BadRequestHttpException;
use app\models\LineupTemplateGroup;
use app\models\Group;
use app\models\LineupTemplate;

class LineupTemplateController extends ActiveController
{
    public $modelClass = LineupTemplate::class;

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

    public function actionAddGroup($id)
    {
        $groupId = Yii::$app->request->bodyParams['group_id'] ?? null;
        $count = Yii::$app->request->bodyParams['count'] ?? 1;
        if (!$groupId || !Group::findOne($groupId)) {
            throw new BadRequestHttpException('Invalid group_id');
        }
        if (!LineupTemplate::findOne($id)) {
            throw new BadRequestHttpException('Invalid template');
        }
        $model = LineupTemplateGroup::findOne(['template_id' => $id, 'group_id' => $groupId]);
        if ($model) {
            $model->count = $count;
        } else {
            $model = new LineupTemplateGroup(['template_id' => $id, 'group_id' => $groupId, 'count' => $count]);
        }
        if ($model->save()) {
            Yii::$app->response->statusCode = 201;
            return $model;
        }
        Yii::$app->response->statusCode = 400;
        return $model->errors;
    }

    public function actionUpdateGroup($id, $group_id)
    {
        $count = Yii::$app->request->bodyParams['count'] ?? null;
        $model = LineupTemplateGroup::findOne(['template_id' => $id, 'group_id' => $group_id]);
        if (!$model) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Not found'];
        }
        if ($count !== null) {
            $model->count = $count;
            if ($model->save()) {
                return $model;
            }
            Yii::$app->response->statusCode = 400;
            return $model->errors;
        }
        throw new BadRequestHttpException('count required');
    }

    public function actionRemoveGroup($id, $group_id)
    {
        $model = LineupTemplateGroup::findOne(['template_id' => $id, 'group_id' => $group_id]);
        if ($model) {
            $model->delete();
            Yii::$app->response->statusCode = 204;
            return null;
        }
        Yii::$app->response->statusCode = 404;
        return ['error' => 'Not found'];
    }
}
