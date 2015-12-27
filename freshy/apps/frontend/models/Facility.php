<?php

namespace Modules\Frontend\Models;

class Facility extends \Phalcon\Mvc\Model {
	public function initialize() {
		$this->setConnectionService (DATABASE);
	}
}