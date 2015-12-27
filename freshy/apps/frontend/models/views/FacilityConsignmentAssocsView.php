<?php

namespace Modules\Frontend\ModelViews;

class FacilityConsignmentAssocsView extends \Phalcon\Mvc\Model {
	public static $needGet=array('columns' => '*');
	public function initialize() {
		$this->setSource("facility_consignment_assocs");
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