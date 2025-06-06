<?php
require_once __DIR__ . '/bootstrap.php';

namespace tests\unit;

use app\controllers\SongListController;
use PHPUnit\Framework\TestCase;
use yii\web\BadRequestHttpException;

class SongListControllerTest extends TestCase
{
    protected function setUp(): void
    {
        \Yii::$app = (object)[
            'request' => (object)['bodyParams' => []],
            'response' => (object)['statusCode' => null],
        ];
    }

    public function testAddSongRequiresSongId(): void
    {
        $controller = new SongListController('song-list', null);
        $this->expectException(BadRequestHttpException::class);
        $controller->actionAddSong(1);
    }
}
