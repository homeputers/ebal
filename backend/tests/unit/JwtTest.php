<?php
namespace tests\unit;

use app\components\Jwt;
use PHPUnit\Framework\TestCase;

class JwtTest extends TestCase
{
    public function testEncodeDecode(): void
    {
        $jwt = new Jwt();
        $jwt->key = 'secret';
        $payload = ['foo' => 'bar'];

        $token = $jwt->encode($payload);
        $decoded = $jwt->decode($token);

        $this->assertSame($payload, $decoded);
    }
}
