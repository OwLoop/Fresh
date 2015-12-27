<?php

namespace Modules\Frontend\Models;

class Consignment extends \Phalcon\Mvc\Model {
	public function initialize() {
		$this->setConnectionService (DATABASE);
	}
}