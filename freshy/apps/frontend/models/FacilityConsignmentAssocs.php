<?php

namespace Modules\Frontend\Models;

class FacilityConsignmentAssocs extends \Phalcon\Mvc\Model {
	public function initialize() {
		$this->setConnectionService (DATABASE);
	}
}