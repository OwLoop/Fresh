<?php

namespace Modules\Frontend\Models;

class Category extends \Phalcon\Mvc\Model {
	public function initialize() {
		$this->setConnectionService (DATABASE);
	}
}