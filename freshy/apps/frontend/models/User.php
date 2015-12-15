<?php

namespace Modules\Frontend\Models;

class User extends \Phalcon\Mvc\Model {
	public function initialize() {
		$this->setConnectionService ('dbs');
	}
}