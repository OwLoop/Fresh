<?php

namespace Modules\Frontend\ModelViews;

class ProductView extends \Phalcon\Mvc\Model {
	public static $needGet=array('columns' => 'productid, categoryid, productname, title, description, rated, price, sale, quantity, weight, unit, image, created_at, updated_at');
	
	public function initialize() {
		$this->setSource("product");
		$this->setConnectionService (DATABASE);
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