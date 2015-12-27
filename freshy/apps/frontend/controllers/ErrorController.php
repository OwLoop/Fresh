<?php

namespace Modules\Frontend\Controllers;
use DateTime;

class ErrorController extends BaseController {
	public function initialize() {
	}
	public function indexAction() {
		$array = array("token"=>TOKEN);
		return $this->sendJson(json_encode($array));
	}
	public function error404Action() {
		
		return $this->showErrorJson(6,'');
	}
}
