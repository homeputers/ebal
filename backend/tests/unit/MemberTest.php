<?php
namespace tests\unit {
    require_once __DIR__ . '/bootstrap.php';
}

namespace app\models {
    /** Simple stub used by the unit test to mimic a group model */
    class DummyGroup {
        public int $id;

        public function __construct(int $id) { $this->id = $id; }
    }
}

namespace tests\unit {
    use app\models\Member;
    use PHPUnit\Framework\TestCase;
    use Yii;

    class MemberTest extends TestCase
    {
        protected function setUp(): void
        {
            $mockDbConnection = new class {
                public function getTableSchema($name, $refresh = false) {
                    $tableSchema = new class {
                        public $fullName;
                        public $columns = [];
                        public $primaryKey;

                        public function getColumnNames() { return array_keys($this->columns); }
                        public function getColumn($name) { return $this->columns[$name] ?? null; }
                    };
                    $tableSchema->fullName = $name;
                    $tableSchema->columns = [ 'id' => (object)['name'=>'id'] ];
                    $tableSchema->primaryKey = ['id'];
                    return $tableSchema;
                }
                public function getSchema() {
                    return new class($this) {
                        private $db; public function __construct($db){$this->db=$db;}
                        public function quoteTableName($n){return '`'.$n.'`';}
                        public function quoteColumnName($n){return '`'.$n.'`';}
                        public function getTableSchema($n,$r=false){return $this->db->getTableSchema($n,$r);} };
                }
                public function createCommand($sql=null,$params=[]){ return new class { public function execute(){return 0;} public function queryAll(){return [];} public function queryOne(){return false;} public function bindValues($v){return $this;} public function getRawSql(){return "";} }; }
                public $driverName = 'mysql';
                public $charset = 'utf8mb4';
            };

            Yii::$app = new class($mockDbConnection) {
                private $db; public function __construct($db){$this->db=$db;}
                public function getDb(){return $this->db;}
                public function get($id,$throw=true){ if($id==='db') return $this->db; return null; }
            };
        }
        public function testAfterFindPopulatesGroupIds(): void
        {
            $member = new Member();
            $member->populateRelation('groups', [new \app\models\DummyGroup(1), new \app\models\DummyGroup(2)]);
            $member->afterFind();
            $this->assertSame([1,2], $member->group_ids);
        }

        // afterSave relies on database interaction which is beyond the scope of this unit test
    }
}
