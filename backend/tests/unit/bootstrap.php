<?php
// Minimal Yii stubs for controller unit tests
namespace yii\rest {
    class Controller {
        public function __construct($id = '', $module = null, $config = []) {}
        public function behaviors() { return []; }
    }
    class ActiveController extends Controller {
        public $modelClass;
    }
}
namespace yii\filters {
    class AccessControl {}
}
namespace yii\filters\auth {
    class HttpBearerAuth {}
}
namespace yii\web {
    class BadRequestHttpException extends \Exception {}
}
namespace yii {
    class BaseYii {
        public static $app;
    }
}
namespace {
    class Yii extends \yii\BaseYii {}
}
