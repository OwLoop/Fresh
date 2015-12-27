<?php

namespace Modules\Frontend\Controllers;
use DateTime;

class LoginController extends BaseController {
	public function initialize() {
	}
	public function indexAction() {
		echo "welcome login page!";
	}
	public function logoutAction() {
		echo "welcome logout page!";
	}
}
