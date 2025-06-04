<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use Yii;
use yii\web\BadRequestHttpException;
use app\models\SongListSong;
use app\models\SongList;
use app\models\Song;

class SongListController extends ActiveController
{
    public $modelClass = SongList::class;

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

    public function actionAddSong($id)
    {
        $songId = Yii::$app->request->bodyParams['song_id'] ?? null;
        $order = Yii::$app->request->bodyParams['order'] ?? null;
        $actualKey = Yii::$app->request->bodyParams['actual_key'] ?? null;
        $version = Yii::$app->request->bodyParams['version'] ?? null;
        $notes = Yii::$app->request->bodyParams['notes'] ?? null;
        if (!$songId || $order === null) {
            throw new BadRequestHttpException('song_id and order required');
        }
        if (!SongList::findOne($id) || !Song::findOne($songId)) {
            throw new BadRequestHttpException('Invalid song list or song');
        }
        if (SongListSong::findOne(['song_list_id' => $id, 'song_id' => $songId])) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'Song already added'];
        }
        $model = new SongListSong([
            'song_list_id' => $id,
            'song_id' => $songId,
            'song_order' => $order,
            'actual_key' => $actualKey,
            'version' => $version,
            'notes' => $notes,
        ]);
        if ($model->save()) {
            Yii::$app->response->statusCode = 201;
            return $model;
        }
        Yii::$app->response->statusCode = 400;
        return $model->errors;
    }

    public function actionUpdateSong($id, $song_id)
    {
        $newSongId = Yii::$app->request->bodyParams['song_id'] ?? $song_id;
        $order = Yii::$app->request->bodyParams['order'] ?? null;
        $actualKey = Yii::$app->request->bodyParams['actual_key'] ?? null;
        $version = Yii::$app->request->bodyParams['version'] ?? null;
        $notes = Yii::$app->request->bodyParams['notes'] ?? null;
        $model = SongListSong::findOne(['song_list_id' => $id, 'song_id' => $song_id]);
        if (!$model) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Not found'];
        }
        if ($newSongId != $song_id) {
            if (!Song::findOne($newSongId)) {
                throw new BadRequestHttpException('Invalid song_id');
            }
            $model->delete();
            $model = new SongListSong([
                'song_list_id' => $id,
                'song_id' => $newSongId,
                'song_order' => $order ?? $model->song_order,
                'actual_key' => $actualKey ?? $model->actual_key,
                'version' => $version ?? $model->version,
                'notes' => $notes ?? $model->notes,
            ]);
            if ($model->save()) {
                return $model;
            }
            Yii::$app->response->statusCode = 400;
            return $model->errors;
        }
        $changed = false;
        if ($order !== null) { $model->song_order = $order; $changed = true; }
        if ($actualKey !== null) { $model->actual_key = $actualKey; $changed = true; }
        if ($version !== null) { $model->version = $version; $changed = true; }
        if ($notes !== null) { $model->notes = $notes; $changed = true; }
        if ($changed && $model->save()) {
            return $model;
        }
        if ($changed) {
            Yii::$app->response->statusCode = 400;
            return $model->errors;
        }
        throw new BadRequestHttpException('Nothing to update');
    }

    public function actionRemoveSong($id, $song_id)
    {
        $model = SongListSong::findOne(['song_list_id' => $id, 'song_id' => $song_id]);
        if ($model) {
            $model->delete();
            Yii::$app->response->statusCode = 204;
            return null;
        }
        Yii::$app->response->statusCode = 404;
        return ['error' => 'Not found'];
    }
}
