<?php

namespace Modules\Frontend\Controllers;
use DateTime;

class TestController extends BaseController {
	public function initialize() {
		
	}
	public function indexAction() {
		$array1 = array("username"=>"nghia","password"=>123456,"email"=>"nhnghia@gmail.com","birthday"=>"1991-10-25");
		echo 'http://api.freshy.vn/signin/resgister?data='.serialize($array1);
		echo '<br><br>'.'http://api.freshy.vn/signin/resgister?data='.serialize($array1).'&token='.TOKEN;
		$array2 = array("username"=>"nghia","password"=>123456,"email"=>"nhnghia@gmail.com","birthday"=>"1991-10-25");
		echo '<br><br>'.'http://api.freshy.vn/signin?data='.serialize($array2).'&token='.TOKEN;
		$array2 = array("username"=>"nhnghia@gmail.com","password"=>123456);
		echo '<br><br>'.'http://api.freshy.vn/signin?data='.serialize($array2).'&token='.TOKEN;
	}
	public function setCacheAction() {
		$this->memcache->save("test_1","this is the test");
		
	}
	public function getCacheAction() {
		$value=$this->memcache->get("test_1");
		print_r($value);exit;
		
	}
}
