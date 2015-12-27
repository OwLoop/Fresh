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
			'Modules\Frontend\ModelViews' 	=> __DIR__ . '/models/views/',
			'Modules\Frontend\Libraries' 	=> __DIR__ . '/libraries/',
			'Modules\Frontend\Services' 	=> __DIR__ . '/services/',
			
		));
		$loader->register();
	}
	public function registerServices(DiInterface $di){
		$di['dispatcher'] = function() {
			$eventsManager = new \Phalcon\Events\Manager();
            //Attach a listener
            $eventsManager->attach("dispatch:beforeException", function ($event, $dispatcher, $exception) {
                //controller or action doesn't exist
                if ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception) {
                 $dispatcher->forward(
                  array(
                   'controller' => 'error',
                   'action'     => 'error404'
                  )
                 );
                 return false;
                }
                switch ($exception->getCode()) {
                 case \Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                 case \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                  $dispatcher->forward(array(
                   'controller' => 'error',
                   'action' => 'error404'
                  ));
                  return false;
                }
            });
			$dispatcher = new Dispatcher();
			$dispatcher->setDefaultNamespace("Modules\Frontend\Controllers");
			$dispatcher->setEventsManager($eventsManager);
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
