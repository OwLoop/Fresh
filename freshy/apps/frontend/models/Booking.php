<?php

namespace Modules\Frontend\Models;

class Booking extends \Phalcon\Mvc\Model {
	public function initialize() {
		$this->setConnectionService (DATABASE);
	}
}