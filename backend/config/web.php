<?php
$params = [];

return [
    'id' => 'backend-app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'changeit',
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
            ],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
        ],
        'cache' => [
            'class' => yii\caching\FileCache::class,
        ],
        'user' => [
            'identityClass' => app\models\User::class,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'jwt' => [
            'class' => app\components\Jwt::class,
            'key' => getenv('JWT_KEY') ?: 'secret',
        ],
        'authManager' => [
            'class' => yii\rbac\DbManager::class,
        ],
        'db' => require __DIR__ . '/db.php',
        'log' => [
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => yii\rest\UrlRule::class,
                    'controller' => [
                        'members' => 'member',
                        'groups' => 'group',
                    ],
                    'extraPatterns' => [
                        'POST {id}/members' => 'add-member',
                        'DELETE {id}/members/<member_id>' => 'remove-member',
                    ],
                ],
                'POST login' => 'site/login',
                'GET public' => 'site/public',
                'GET dashboard' => 'site/dashboard',
                'GET admin' => 'site/admin',
            ],
        ],
    ],
    'params' => $params,
];
