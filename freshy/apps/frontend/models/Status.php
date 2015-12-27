<?php

namespace Modules\Frontend\Models;

class Status extends \Phalcon\Mvc\Model {
	public function initialize() {
		$this->setConnectionService (DATABASE);
	}
}