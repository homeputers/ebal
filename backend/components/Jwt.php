<?php
namespace app\components;

use Firebase\JWT\JWT as BaseJwt;
use Firebase\JWT\Key;

class Jwt
{
    public string $key;
    public string $alg = 'HS256';

    public function encode(array $payload): string
    {
        return BaseJwt::encode($payload, $this->key, $this->alg);
    }

    public function decode(string $token): array
    {
        return (array) BaseJwt::decode($token, new Key($this->key, $this->alg));
    }
}
