<?php
require_once __DIR__ . '/bootstrap.php';

namespace app\models;
class User {
    public $id;
    public $username;
    public $password_hash;
    public $role = 'user';
    public static $stored;
    public static function findOne($cond) {
        if (isset($cond['username']) && self::$stored && self::$stored->username === $cond['username']) {
            return self::$stored;
        }
        return null;
    }
    public function generateJwt() { return 'jwt-' . $this->id; }
}

namespace tests\unit;

use app\controllers\SiteController;
use PHPUnit\Framework\TestCase;
use Yii;

class SiteControllerTest extends TestCase
{
    protected function setUp(): void
    {
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
        $user = new \app\models\User();
        $user->id = 1;
        $user->username = 'john';
        $user->password_hash = 'hashed';
        \app\models\User::$stored = $user;
        \Yii::$app->request->bodyParams = ['username' => 'john', 'password' => 'secret'];

        $controller = new SiteController('site', null);
        $result = $controller->actionLogin();

        $this->assertSame(['token' => 'jwt-1'], $result);
        $this->assertNull(\Yii::$app->response->statusCode);
    }

    public function testLoginFailure(): void
    {
        \app\models\User::$stored = null;
        \Yii::$app->request->bodyParams = ['username' => 'john', 'password' => 'wrong'];

        $controller = new SiteController('site', null);
        $result = $controller->actionLogin();

        $this->assertSame(['error' => 'Invalid credentials'], $result);
        $this->assertSame(401, \Yii::$app->response->statusCode);
    }
}
