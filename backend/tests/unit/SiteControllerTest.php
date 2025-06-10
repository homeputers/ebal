<?php
namespace tests\unit;

require_once __DIR__ . '/bootstrap.php';

// Define the mock user class within the tests\unit namespace
// Renamed to avoid any potential collision and to be clear it's a mock.
class MockUserForSiteTest {
    public $id;
    public $username;
    public $password_hash;
    public $role = 'user';
    public static $stored;

    public static function findOne($cond) {
        if (isset($cond['username']) && isset(self::$stored) && self::$stored->username === $cond['username']) {
            return self::$stored;
        }
        return null;
    }
    public function generateJwt() { return 'jwt-' . $this->id; }
}


use app\controllers\SiteController;
use PHPUnit\Framework\TestCase;
use Yii;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SiteControllerTest extends TestCase
{
    protected function setUp(): void
    {
        // Alias app\models\User to our mock for this test process
        class_alias('tests\unit\MockUserForSiteTest', 'app\models\User');

        \Yii::$app = (object)[
            'request' => (object)['bodyParams' => []],
            'response' => (object)['statusCode' => null],
            'user' => (object)['identity' => (object)['username' => 'john', 'role' => 'user']],
            'security' => new class {
                public function validatePassword($password, $hash) {
                    return $password === 'secret' && $hash === 'hashed';
                }
            },
            'jwt' => new class {
                public function encode($payload) { return 'jwt-' . ($payload['id'] ?? ''); }
            },
        ];
    }

    public function testPublic(): void
    {
        $controller = new SiteController('site', null);
        $result = $controller->actionPublic();
        $this->assertSame(['message' => 'Public endpoint'], $result);
    }

    public function testLoginSuccess(): void
    {
        $user = new MockUserForSiteTest();
        $user->id = 1;
        $user->username = 'john';
        $user->password_hash = 'hashed';
        MockUserForSiteTest::$stored = $user;
        \Yii::$app->request->bodyParams = ['username' => 'john', 'password' => 'secret'];

        $controller = new SiteController('site', null);
        $result = $controller->actionLogin();

        $this->assertSame(['token' => 'jwt-1'], $result);
        $this->assertNull(\Yii::$app->response->statusCode);
    }

    public function testLoginFailure(): void
    {
        MockUserForSiteTest::$stored = null;
        \Yii::$app->request->bodyParams = ['username' => 'john', 'password' => 'wrong'];

        $controller = new SiteController('site', null);
        $result = $controller->actionLogin();

        $this->assertSame(['error' => 'Invalid credentials'], $result);
        $this->assertSame(401, \Yii::$app->response->statusCode);
    }
}
