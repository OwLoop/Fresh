<?php

namespace Modules\Backend;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Db\Adapter\Pdo\Mysql as MySQLAdapter;

class Module{

	public function registerAutoloaders(){
		$loader = new Loader();
		$loader->registerNamespaces(array(
			'Modules\Backend\Controllers' => __DIR__ . '/controllers/',
			'Modules\Backend\Models'      => __DIR__ . '/models/',
			'Modules\Backend\Services'    => __DIR__ . '/services/',
		));
		$loader->register();
	}

	public function registerServices(DiInterface $di){
		$di['dispatcher'] = function() {
			$dispatcher = new Dispatcher();
			$dispatcher->setDefaultNamespace("Modules\Backend\Controllers");
			return $dispatcher;
		};
		$di['view'] = function() {
			$view = new View();
			$view->setViewsDir(__DIR__ . '/views/');
			$view->setLayoutsDir('../../common/layout_backend/');
			return $view;
		};
	}
}
