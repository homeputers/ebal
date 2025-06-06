<?php
require_once __DIR__ . '/bootstrap.php';

namespace tests\unit;

use app\controllers\GroupController;
use PHPUnit\Framework\TestCase;
use yii\web\BadRequestHttpException;

class GroupControllerTest extends TestCase
{
    protected function setUp(): void
    {
        \Yii::$app = (object)[
            'request' => (object)['bodyParams' => []],
            'response' => (object)['statusCode' => null],
        ];
    }

    public function testAddMemberRequiresMemberId(): void
    {
        $controller = new GroupController('group', null);
        $this->expectException(BadRequestHttpException::class);
        $controller->actionAddMember(1);
    }
}
