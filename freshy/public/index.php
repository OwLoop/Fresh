<?php
// phpinfo();exit;
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
include __DIR__."/../apps/common/define/var.php";
try {
	$di = new \Phalcon\DI\FactoryDefault();
	$di['router'] = function() {
		$router = new \Phalcon\Mvc\Router(true);
		///////////// FONTEND ///////////////
		$router->add('/:params', 							array('module' => 'frontend','controller' => 'index',	'action' => 'index',"params" => 1));
		$router->add('/:controller/:params', 				array('module' => 'frontend','controller' => 1,			'action' => 'index',"params" => 2));
		$router->add('/:controller/:action/:params', 		array('module' => 'frontend','controller' => 1,			'action' => 2,		"params" => 3));
		
		///////////// BACKEND ///////////////
		$router->add('/admin/:params', 						array('module' => 'backend','controller' => 'index',	'action' => 'index',"params" => 1));
		$router->add('/admin/:controller/:params', 			array('module' => 'backend','controller' => 1,			'action' => 'index',"params" => 2));
		$router->add('/admin/:controller/:action/:params', 	array('module' => 'backend','controller' => 1,			'action' => 2,		"params" => 3));
		
		$router->setDefaults(array('module' => 'frontend','controller' => 'index','action' => 'index'));
		return $router;
	};
	$di->set('url', function() {
		$url = new \Phalcon\Mvc\Url();
		$url->setBaseUri('/');
		return $url;
	});
	$di->set('session', function() {
		$session = new \Phalcon\Session\Adapter\Files();
		$session->start();
		return $session;
	});
	$di->set('memcache', function () {
		$frontCache = new \Phalcon\Cache\Frontend\Data(array("lifetime" => 86400));
		$cache = new \Phalcon\Cache\Backend\Memcache(
			$frontCache,
			array(
				"host" => "localhost",
				"port" => "11211",
				"prefix" => PREFIX_CACHE
			)
		);
		return $cache;
	});
	$config = require __DIR__ . "/../apps/common/configs/config.php";
	$di-> set('dbs',function() use ($config) {
		return new Phalcon\Db\Adapter\Pdo\Mysql(array(
			"host" 		=> $config->dbs->host,
			"username"  => $config->dbs->username,
			"password"  => $config->dbs->password,
			"dbname" 	=> $config->dbs->name,
			'charset' 	=> $config->dbs->charset
		));
	});
	$application = new \Phalcon\Mvc\Application();
	$application->setDI($di);
	$application->registerModules(array(
		'frontend' => array(
			'className' => 'Modules\Frontend\Module',
			'path' => '../apps/frontend/Module.php'
		),
		'backend' => array(
			'className' => 'Modules\Backend\Module',
			'path' => '../apps/backend/Module.php'
		)
	));
	echo $application->handle()->getContent();
} catch (Phalcon\Exception $e) {
	echo $e->getMessage();
} catch (PDOException $e){
	echo $e->getMessage();
}
