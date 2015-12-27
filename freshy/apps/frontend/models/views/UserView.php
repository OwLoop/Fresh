<?php

namespace Modules\Frontend\ModelViews;

class UserView extends \Phalcon\Mvc\Model {
	public static $needGet=array('columns' => 'userid, username, hash, fullname, password, email, token, role, created_at, updated_at, birthday, birthmonth, birthyear');
	
	public function initialize() {
		$this->setSource("user");
		$this->setConnectionService (DATABASE);
	}
	public static function findFirst($parameters=null){
		$needFind = array_merge ($parameters,self::$needGet);
		return parent::findFirst($needFind);
    }
	public static function find($parameters=null){
		
        return parent::find(self::$needGet);
    }
}