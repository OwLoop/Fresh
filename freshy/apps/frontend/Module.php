<?php

namespace Modules\Frontend;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Db\Adapter\Pdo\Mysql as MySQLAdapter;

class Module{

	public function registerAutoloaders(){
		$loader = new Loader();
		$loader->registerNamespaces(array(
			'Modules\Frontend\Controllers' 	=> __DIR__ . '/controllers/',
			'Modules\Frontend\Models' 		=> __DIR__ . '/models/',
			'Modules\Frontend\Services' 	=> __DIR__ . '/services/',
		));
		$loader->register();
	}
	public function registerServices(DiInterface $di){
		$di['dispatcher'] = function() {
			$dispatcher = new Dispatcher();
			$dispatcher->setDefaultNamespace("Modules\Frontend\Controllers");
			return $dispatcher;
		};
		$di['view'] = function() {
			$view = new View();
			$view->setViewsDir(__DIR__ . '/views/');
			$view->setLayoutsDir('../../common/layout_frontend/');
			return $view;
		};
	}
}
