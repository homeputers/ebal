<?php
namespace tests\unit;

require_once __DIR__ . '/bootstrap.php';

use app\controllers\LineupTemplateController;
use PHPUnit\Framework\TestCase;
use yii\web\BadRequestHttpException;

class LineupTemplateControllerTest extends TestCase
{
    protected function setUp(): void
    {
        \Yii::$app = (object)[
            'request' => (object)['bodyParams' => []],
            'response' => (object)['statusCode' => null],
        ];
    }

    public function testAddGroupRequiresGroupId(): void
    {
        $controller = new LineupTemplateController('lineup-template', null);
        $this->expectException(BadRequestHttpException::class);
        $controller->actionAddGroup(1);
    }
}
