<?php

namespace Modules\Frontend\ModelViews;

class CategoryView extends \Phalcon\Mvc\Model {
	public static $needGet=array('columns' => 'categoryid, categoryname, image, created_at, updated_at');
	public function initialize() {
		$this->setSource("category");
		$this->setConnectionService(DATABASE);
	}
	public static function findFirst($parameters=null){
		$needFind = array_merge ($parameters,self::$needGet);
		return parent::findFirst($needFind);
    }
	public static function find($parameters=null){
		$needFind = array_merge ($parameters,self::$needGet);
        return parent::find($needFind);
    }
}