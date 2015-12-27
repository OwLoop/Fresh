<?php

namespace Modules\Frontend\Models;

class Comment extends \Phalcon\Mvc\Model {
	public function initialize() {
		$this->setConnectionService (DATABASE);
	}
}