<?php
error_reporting ( E_ALL & ~ E_NOTICE );
ini_set ( 'display_errors', 1 );
include __DIR__."/common/define/var.php";
$loader = new \Phalcon\Loader ();
$loader->registerNamespaces(array(
	'Modules\Frontend\Controllers' 	=> __DIR__ . '/frontend/controllers/',
	'Modules\Frontend\Models' 		=> __DIR__ . '/frontend/models/',
	'Modules\Frontend\Services' 	=> __DIR__ . '/frontend/services/',
));
$loader->registerDirs ( array (__DIR__ . '/frontend/tasks/'));
$loader->register();

$di = new Phalcon\DI\FactoryDefault\CLI ();
$config = require __DIR__ . "/common/configs/config.php";
$di-> set('crm',function() use ($config) {
	return new Phalcon\Db\Adapter\Pdo\Mysql(array(
		"host" 		=> $config->crm->host,
		"username"  => $config->crm->username,
		"password"  => $config->crm->password,
		"dbname" 	=> $config->crm->name,
		'charset' 	=> $config->crm->charset
	));
});
$di-> set('ads',function() use ($config) {
	return new Phalcon\Db\Adapter\Pdo\Mysql(array(
			"host" 		=> $config->ads->host,
			"username"  => $config->ads->username,
			"password"  => $config->ads->password,
			"dbname" 	=> $config->ads->name,
// 			'charset' 	=> $config->ads->charset
	));
});
$di->set ( 'collectionManager', function(){return new Phalcon\Mvc\Collection\Manager ();},true);
$di->set ( 'bigdata', function () use($config) {
	if (! $config->bigdata->username or ! $config->bigdata->password) {
		$mongo = new MongoClient ( 'mongodb://' . $config->bigdata->host);
	} else {
		$mongo = new MongoClient ( "mongodb://" . $config->bigdata->username . ":" . $config->bigdata->password . "@" . $config->bigdata->host, array (
				"db" => $config->bigdata->name 
		));
	}
	return $mongo->selectDb ( $config->bigdata->name );
}, TRUE );
$di->set('crmcache', function () {
	// Cache data for one day by default
	$frontCache = new \Phalcon\Cache\Frontend\Data(array("lifetime" => 86400));
	// Memcached connection settings
	$cache = new \Phalcon\Cache\Backend\Memcache(
			$frontCache,
			array(
					"host" => "221.133.7.87",
					"port" => "11211",
					"prefix" => PREFIX_CACHE
			)
	);
	return $cache;
});
$di->set('crmcacheap', function () {
	// Cache data for one day by default
	$frontCache = new \Phalcon\Cache\Frontend\Data(array("lifetime" => 86400));
	// Memcached connection settings
	$cache = new \Phalcon\Cache\Backend\Memcache(
		$frontCache,
		array(
				"host" => "221.133.7.87",
				"port" => "11211",
				"prefix" => 'crmcacheap'
		)
	);
	return $cache;
});
// Create a console application
$console = new Phalcon\CLI\Console ();
$console->setDI ( $di );

/**
 * Process the console arguments
 */
$arguments = array ();
foreach ( $argv as $k => $arg ) {
	if ($k == 1) {
		$arguments ['task'] = $arg;
	} elseif ($k == 2) {
		$arguments ['action'] = $arg;
	} elseif ($k >= 3) {
		$arguments ['params'] [] = $arg;
	}
}

// define global constants for the current task and action
define ( 'CURRENT_TASK', (isset ( $argv [1] ) ? $argv [1] : null) );
define ( 'CURRENT_ACTION', (isset ( $argv [2] ) ? $argv [2] : null) );

try {
	// handle incoming arguments
	$console->handle ( $arguments );
} catch ( \Phalcon\Exception $e ) {
	echo $e->getMessage ();
	exit ( 255 );
}