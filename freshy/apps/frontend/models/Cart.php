<?php

namespace Modules\Frontend\Models;

class Cart extends \Phalcon\Mvc\Model {
	public function initialize() {
		$this->setConnectionService (DATABASE);
	}
}