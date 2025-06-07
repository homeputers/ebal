<?php
namespace tests\unit;

use app\components\Jwt;
use app\models\User;
use PHPUnit\Framework\TestCase;
use Yii;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        $jwt = new Jwt();
        $jwt->key = 'secret';
        // Mock minimal Yii application container
        Yii::$app = (object)['jwt' => $jwt];
    }

    public function testGenerateJwt(): void
    {
        $user = new User();
        $user->id = 42;
        $user->role = 'admin';

        $token = $user->generateJwt();

        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9\-_]+\.[a-zA-Z0-9\-_]+\.[a-zA-Z0-9\-_]+$/',
            $token,
            'Generated token is not in the expected JWT format (header.payload.signature). Actual token: ' . $token
        );

        $payload = Yii::$app->jwt->decode($token);

        $this->assertSame(42, $payload['id']);
        $this->assertSame('admin', $payload['role']);
    }
}
