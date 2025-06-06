<?php
require_once __DIR__ . '/bootstrap.php';

namespace tests\unit;

use app\controllers\LineupController;
use PHPUnit\Framework\TestCase;
use yii\web\BadRequestHttpException;

class LineupControllerTest extends TestCase
{
    protected function setUp(): void
    {
        \Yii::$app = (object)[
            'request' => (object)['bodyParams' => []],
            'response' => (object)['statusCode' => null],
        ];
    }

    public function testAddMemberRequiresIds(): void
    {
        $controller = new LineupController('lineup', null);
        $this->expectException(BadRequestHttpException::class);
        $controller->actionAddMember(1);
    }
}
