<?php

namespace Modules\Frontend\Controllers;
use DateTime;

class IndexController extends BaseController {
	public function initialize() {
	}
	public function indexAction() {
		$array = array("token"=>TOKEN);
		return $this->sendJson(json_encode($array));
	}
}
