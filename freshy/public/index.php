<?php
// phpinfo();exit;
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
include __DIR__."/../apps/common/define/var.php";
include_once __DIR__.'/../apps/frontend/services/vendor/swift_required.php';
include_once __DIR__ . '/../apps/frontend/libraries/hybridauth/Hybrid/Auth.php';
include_once __DIR__ . '/../apps/frontend/libraries/hybridauth/Hybrid/Endpoint.php';
try {
	$di = new \Phalcon\DI\FactoryDefault();
	//nnluc073
	$di['hybridauth'] = function() {
		return new Hybrid_Auth( __DIR__ . '/../apps/frontend/libraries/hybridauth/config.php' );
	};
	$di['hybridauth_endpoint'] = function() {
		return new Hybrid_Endpoint();
	};
	$configMail = require __DIR__ . "/../apps/common/configs/configEmail.php";
	
	$di['mail_message'] = function() use ($configMail){
		$message = Swift_Message::newInstance();
		$message->setFrom(array($configMail['fromEmail'] => $configMail['fromName']));
		$message->setSubject("Freshy.vn");
		$message->setContentType("text/html");
		return $message;
	};
	$di['mail_mailer'] = function() use ($configMail){
		$hostInfo = $configMail['smtp'];
		$transport = Swift_SmtpTransport::newInstance($hostInfo['server'], $hostInfo['port'], $hostInfo['security']);
		$transport->setUsername($hostInfo['username']);
		$transport->setPassword($hostInfo['password']);
		return Swift_Mailer::newInstance($transport);
	};
	
	//end nnluc073
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
	$di->set ( 'cookie', function () {
		$cookie = new \Phalcon\Http\Response\Cookies ();
		$cookie->useEncryption ( false );
		return $cookie;
	});
	$di->set('session', function() {
		$session = new \Phalcon\Session\Adapter\Files();
		$session->start();
		return $session;
	});
	$di->set(MEMCACHE, function () {
		$frontCache = new \Phalcon\Cache\Frontend\Data(array("lifetime" => 86400));
		$cache = new \Phalcon\Cache\Backend\Memcache(
			$frontCache,
			array(
				"host" => "localhost",
				"port" => "11211",
				"prefix" => MEMCACHE
			)
		);
		return $cache;
	});
	$config = require __DIR__ . "/../apps/common/configs/config.php";
	$di-> set(DATABASE,function() use ($config) {
		return new Phalcon\Db\Adapter\Pdo\Mysql(array(
			"host" 		=> $config->dbs->host,
			"username"  => $config->dbs->username,
			"password"  => $config->dbs->password,
			"dbname" 	=> $config->dbs->name,
			'charset' 	=> $config->dbs->charset
		));
	});
	$di->set('collectionManager', function(){return new Phalcon\Mvc\Collection\Manager();}, true);
	$di->set('dbm', function () use ($config) {
		if (!$config->dbm->username OR !$config->dbm->password) {
			$mongo = new MongoClient('mongodb://'.$config->dbm->host);
		} else {
			$mongo = new MongoClient("mongodb://" . $config->dbm->username . ":" . $config->dbm->password . "@" . $config->dbm->host,array("db" => $config->dbm->name));
		}
		return $mongo->selectDb($config->dbm->name);
	}, TRUE);
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
