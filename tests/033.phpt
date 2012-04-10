--TEST--
Check for Yaf_View_Simple with predefined template dir
--SKIPIF--
<?php if (!extension_loaded("yaf")) print "skip"; ?>
--INI--
--FILE--
<?php 
$config = array(
	"application" => array(
		"directory" => realpath(dirname(__FILE__)),
		"dispatcher" => array(
			"catchException" => 0,
			"throwException" => 1,
		),
        "modules" => "module",
	),
);

class ControllerController extends Yaf_Controller_Abstract {
    public function actionAction() {
    }

    public function indexAction() {
        Yaf_Dispatcher::getInstance()->disableView();
        $this->forward("dummy");
    }

    public function dummyAction() {
        Yaf_Dispatcher::getInstance()->enableView();
    }
}


$app = new Yaf_Application($config);
$request = new Yaf_Request_Http("/module/controller/action");

try {
  $app->getDispatcher()->returnResponse(false)->dispatch($request);
} catch (Yaf_Exception $e) {
  echo $e->getMessage(), "\n";
}

$view = new Yaf_View_Simple(dirname(__FILE__) . 'no-exists');
$app->getDispatcher()->setView($view);
try {
  $app->getDispatcher()->returnResponse(false)->dispatch($request);
} catch (Yaf_Exception $e) {
  echo $e->getMessage(), "\n";
}

$request = new Yaf_Request_Http("/module/controller/index");
try {
  $app->getDispatcher()->returnResponse(false)->dispatch($request);
} catch (Yaf_Exception $e) {
  echo $e->getMessage(), "\n";
}
?>
--EXPECTF--
Unable to find template /home/huixinchen/packages/php-5.2.17/ext/yaf/tests/modules/Module/views/controller/action.phtml
Unable to find template /home/huixinchen/packages/php-5.2.17/ext/yaf/testsno-exists/controller/action.phtml
Unable to find template /home/huixinchen/packages/php-5.2.17/ext/yaf/testsno-exists/controller/dummy.phtml
