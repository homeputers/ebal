<?php
namespace tests\unit;

require_once __DIR__ . '/bootstrap.php';

class MockLineupTemplate
{
    public static bool $exists = false;
    public static function findOne($id)
    {
        return self::$exists ? new self : null;
    }
}

class MockLineupTemplateGroup
{
    public static array $list = [];
    public static function find()
    {
        return new class {
            public function where($cond) { return $this; }
            public function with($rel) { return $this; }
            public function all() { return \tests\unit\MockLineupTemplateGroup::$list; }
        };
    }
}

use app\controllers\LineupTemplateController;
use PHPUnit\Framework\TestCase;
use yii\web\BadRequestHttpException;

class LineupTemplateControllerTest extends TestCase
{
    protected function setUp(): void
    {
        if (!class_exists('app\\models\\LineupTemplate', false)) {
            class_alias(MockLineupTemplate::class, 'app\\models\\LineupTemplate');
        }
        if (!class_exists('app\\models\\LineupTemplateGroup', false)) {
            class_alias(MockLineupTemplateGroup::class, 'app\\models\\LineupTemplateGroup');
        }
        \Yii::$app = (object)[
            'request' => (object)['bodyParams' => []],
            'response' => (object)['statusCode' => null],
        ];
        MockLineupTemplate::$exists = false;
        MockLineupTemplateGroup::$list = [];
    }

    public function testAddGroupRequiresGroupId(): void
    {
        $controller = new LineupTemplateController('lineup-template', null);
        $this->expectException(BadRequestHttpException::class);
        $controller->actionAddGroup(1);
    }

    public function testGroupsNotFound(): void
    {
        $controller = new LineupTemplateController('lineup-template', null);
        $result = $controller->actionGroups(1);
        $this->assertSame(['error' => 'Not found'], $result);
        $this->assertSame(404, \Yii::$app->response->statusCode);
    }

    public function testGroupsReturnsList(): void
    {
        MockLineupTemplate::$exists = true;
        MockLineupTemplateGroup::$list = [['group_id' => 1, 'count' => 2]];
        $controller = new LineupTemplateController('lineup-template', null);
        $result = $controller->actionGroups(1);
        $this->assertSame([[
            'group_id' => 1,
            'count' => 2
        ]], $result);
        $this->assertNull(\Yii::$app->response->statusCode);
    }
}
