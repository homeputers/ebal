<?php
return [
    'class' => yii\db\Connection::class,
    'dsn' => getenv('DB_DSN') ?: 'mysql:host=127.0.0.1;dbname=app',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'charset' => 'utf8',
];
