<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Models\Facility;
use DateTime;

class FacilityController extends BaseController {

	public function initialize() {
		parent::initialize();
	}
	public function findFirstAction() {
		$aParam = $this->getParam();
		$keyCache = 'facility_findfirst_'.$aParam['language'].$aParam['facilityid'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$columns = ",facilityname_vi as facilityname";
			if ($aParam['language'] == EN) $columns = ",facilityname_en as facilityname";
			$result = $this->query(DATABASE, "SELECT facilityid $columns FROM facility WHERE facilityid='".$aParam['facilityid']."'");
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Facility");
		}
			
	}
	public function findAllAction() {
		$aParam = $this->getParam();
		$keyCache = 'facility_findall'.$aParam['language'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$columns = ",facilityname_vi as facilityname";
			if ($aParam['language'] == EN) $columns = ",facilityname_en as facilityname";
			$result = $this->query(DATABASE, "SELECT facilityid $columns FROM facility");
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Facility");
		}
	}
	public function queryAction() {
		$aParam = $this->getParam();
		$keyCache = 'facility_query_'.sha1($aParam['where'].$aParam['group'].$aParam["order"].$aParam['offset'].$aParam['limit'].$aParam['language']);
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($resultCache)){
			$sql = "WHERE 1";
			if(isset($aParam['where'])) $sql = "WHERE ".$aParam['where'];
			if(isset($aParam['group'])) $sql .= " GROUP BY ".$aParam['group'];
			if(isset($aParam['order'])) $sql .= " ORDER BY ".$aParam['order'];
			$columns = ",facilityname_vi as facilityname";
			if ($aParam['language'] == EN) $columns = ",facilityname_en as facilityname";
			$result = $this->query(DATABASE,"SELECT facilityid $columns FROM facility ".$sql." LIMIT ".$aParam['offset'].",".$aParam['limit']);
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			$response = array (
				"offset"	=> count($result)+$aParam['offset'],
				"limit"		=> $aParam['limit'],
				"data"		=> $result
			);
			return $this->sendJson(json_encode($response));
		}else{
			return $this->showErrorJson(7,"Facility");
		}
			
	}
}