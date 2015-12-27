<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Services;
use Modules\Frontend\Services\Utils;
use DateTime;

class TestlucController extends BaseController {
	public function initialize() {
	}
	public function indexAction() {
		//print_r(strtolower ("Nguyễn Nghị Lực"));exit;
		print_r(Utils::toEnglish('Nguyễn Nghị Lực'));exit;
		exit;
		//phpinfo();exit;
		$this->view->disable();
		$params=array(
			'apa'=>1,
			'bpa'=>2
		);
		try{
			$result = $this->sendMail('lucnn@s-wifi.vn','test',$params);
			echo "Done!";
		}catch(Exception $ex){
			echo "Something wrong!";
		}
	}
	public function checkPermissionAction(){
		$result = $this->checkPermission(array(CLIENT));
		if(!$result['status']){
			$this->sendJson(json_encode($result));
		} else {
			echo "welcome!";
			$this->sendJson(json_encode($result));
		}
		
		//print_r();
		//$this->checkPermission(array(WAREHOUSEMAN));
		//$this->checkPermission(array(STOREKEEPER));
		//$this->checkPermission(array(DELIVERER));
		//$this->checkPermission(array(CLIENT));
		
		
	}
}
