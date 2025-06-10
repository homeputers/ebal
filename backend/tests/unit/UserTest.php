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
        $jwtComponent = new \app\components\Jwt();
        $jwtComponent->key = 'secret';

        $mockDbConnection = new class {
            public function getTableSchema($name, $refresh = false) {
                $tableSchema = new class {
                    public $fullName;
                    public $columns;
                    public $primaryKey;

                    public function getColumnNames() {
                        return array_keys($this->columns);
                    }

                    public function getColumn($name) {
                        return $this->columns[$name] ?? null;
                    }
                };
                $tableSchema->fullName = $name;
                $tableSchema->columns = [
                    'id' => (object)['name'=>'id', 'type' => 'integer', 'phpType' => 'integer', 'isPrimaryKey' => true, 'autoIncrement' => true, 'allowNull' => false, 'dbType'=>'int(11)'],
                    'username' => (object)['name'=>'username', 'type' => 'string', 'phpType' => 'string', 'isPrimaryKey' => false, 'autoIncrement' => false, 'allowNull' => false, 'dbType'=>'varchar(255)'],
                    'password_hash' => (object)['name'=>'password_hash', 'type' => 'string', 'phpType' => 'string', 'isPrimaryKey' => false, 'autoIncrement' => false, 'allowNull' => false, 'dbType'=>'varchar(255)'],
                    'role' => (object)['name'=>'role', 'type' => 'string', 'phpType' => 'string', 'isPrimaryKey' => false, 'autoIncrement' => false, 'allowNull' => true, 'dbType'=>'varchar(64)'],
                    'status' => (object)['name'=>'status', 'type' => 'integer', 'phpType' => 'integer', 'isPrimaryKey' => false, 'autoIncrement' => false, 'allowNull' => false, 'dbType'=>'int(11)'],
                    'created_at' => (object)['name'=>'created_at', 'type' => 'integer', 'phpType' => 'integer', 'isPrimaryKey' => false, 'autoIncrement' => false, 'allowNull' => false, 'dbType'=>'int(11)'],
                    'updated_at' => (object)['name'=>'updated_at', 'type' => 'integer', 'phpType' => 'integer', 'isPrimaryKey' => false, 'autoIncrement' => false, 'allowNull' => false, 'dbType'=>'int(11)'],
                ];
                $tableSchema->primaryKey = ['id'];
                return $tableSchema;
            }
            public function getSchema() {
                return new class($this) {
                    private $db;
                    public function __construct($db) { $this->db = $db; }
                    public function quoteTableName($name) { return '`' . $name . '`'; }
                    public function quoteColumnName($name) { return '`' . $name . '`'; }
                    public function getTableSchema($name, $refresh = false) { return $this->db->getTableSchema($name, $refresh); }
                };
            }
            public function createCommand($sql = null, $params = []) {
                 return new class {
                    public function queryOne($fetchMode = null) { return false; } 
                    public function queryAll($fetchMode = null) { return []; }
                    public function execute() { return 0; }
                    public function bindValues($values){ return $this;}
                    public function getRawSql(){ return "";}
                };
            }
            public $driverName = 'mysql';
            public $charset = 'utf8mb4';
        };

        Yii::$app = new class($jwtComponent, $mockDbConnection) {
            public $jwt; // Public property for direct access Yii::$app->jwt
            private $db;

            public function __construct($jwt, $db) {
                $this->jwt = $jwt;
                $this->db = $db;
            }

            public function getDb() {
                return $this->db;
            }

            // Mock Yii::$app->get('component') behavior
            public function get($id, $throwException = true) {
                if ($id === 'jwt') return $this->jwt;
                if ($id === 'db') return $this->db;
                // Add other components if needed by User model or its methods
                if ($id === 'security') {
                    return new class {
                        public function generatePasswordHash($password, $cost = null) { return 'hashed-' . $password; }
                        public function validatePassword($password, $hash) { return $hash === ('hashed-' . $password); }
                    };
                }
                if ($throwException) {
                    throw new \yii\base\InvalidConfigException("Unknown component ID: $id");
                }
                return null;
            }
        };
    }

    public function testGenerateJwt(): void
    {
        $user = new User();
        $user->id = 42;
        $user->role = 'admin';

        $token = $user->generateJwt(); 

        $this->assertMatchesRegularExpression(
            '/^[a-zA-Z0-9\-_]+\.[a-zA-Z0-9\-_]+\.[a-zA-Z0-9\-_]+$/',
            $token,
            'Generated token is not in the expected JWT format (header.payload.signature). Actual token: ' . $token
        );

        $decodedPayload = Yii::$app->jwt->decode($token);

        $this->assertSame(42, $decodedPayload['id']);
        $this->assertSame('admin', $decodedPayload['role']);
    }
}
