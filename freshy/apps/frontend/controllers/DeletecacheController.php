<?php

namespace Modules\Frontend\Controllers;
use Phalcon\Mvc\Controller;

class DeletecacheController extends Controller {
	
	public function indexAction(){
		$cache = MEMCACHE;
		$keys = $this->$cache->queryKeys(MEMCACHE);
		foreach($keys as $key) {
			$a = explode(MEMCACHE, $key);
			$this->$cache->delete($a[1]);
		}
		echo 'done';
	}
}