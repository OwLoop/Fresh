<?php

namespace Modules\Frontend\Models;

class Product extends \Phalcon\Mvc\Model {
	public function initialize() {
		$this->setConnectionService (DATABASE);
	}
}