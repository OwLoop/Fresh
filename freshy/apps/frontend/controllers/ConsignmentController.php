<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Models\Consignment;
use Modules\Frontend\Models\FacilityConsignmentAssocs;
use DateTime;

class ConsignmentController extends BaseController {

	public function initialize() {
		// parent::initialize();
	}

	public function findFirstAction() {
		$aParam = $this->getParam();
		
		$keyCache = 'consignment_findfirst_'.$aParam['language'].$aParam['productid'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$columns = ",consignmentname_vi as consignmentname,description_vi as description";
			if ($aParam['language'] == EN) $columns = ",consignmentname_en as consignmentname,description_en as description";
			$result = $this->query(DATABASE, 
				"SELECT consignmentid,productid,rated,price,quantity,quantity_per_price,totalprice $columns ".
				" FROM consignment WHERE consignmentid='".$aParam['consignmentid']."'"
			);
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Consignment");
		}
			
	}
	public function findAllAction() {
		$aParam = $this->getParam();

		$keyCache = 'consignment_findall'.$aParam['language'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$columns = ",consignmentname_vi as consignmentname,description_vi as description";
			if ($aParam['language'] == EN) $columns = ",consignmentname_en as consignmentname,description_en as description";
			$result = $this->query(DATABASE, "SELECT consignmentid,productid,rated,price,quantity,quantity_per_price,totalprice $columns FROM consignment");
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Consignment");
		}
	}
	public function addAction(){
		$aParam = $this->getParam();
		$check = Consignment::findFirst("consignmentname_vi='".$aParam['consignmentname_vi']."' OR consignmentname_en='".$aParam['consignmentname_en']."'");
		if ($check == false){
			$aParam["consignmentid"]     	= $this->randomToken(10);
			$aParam["created_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$new 							= new Consignment();
			$result 						= $new->save($aParam);
			$aParam['facilityid']			= NHAKHO;
			$aParam['consignmentid']		= $new->consignmentid;
			$new_ 							= new FacilityConsignmentAssocs();
			$result_ 						= $new_->save($aParam);

			if ($result==true && $result_==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Consignment",$aParam);
			}
		}else{
			return $this->showErrorJson(2,"Consignment",$aParam);
		}
	}
	public function editAction(){
		$aParam = $this->getParam();
		$check = Consignment::findFirst("(consignmentname_vi='".$aParam['consignmentname_vi']."' OR consignmentname_en='".$aParam['consignmentname_en']."') AND consignmentid<>'".$aParam['consignmentid']."'");
		if ($check == false){
			$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$update 						= Consignment::findFirst("consignmentid='".$aParam['consignmentid']."'");
			$result 						= $update->update($aParam);
			$update_						= FacilityConsignmentAssocs::findFirst("facilityid='".NHAKHO."' AND consignmentid='".$update->consignmentid."'");
			$result_ 						= $update_->save($aParam);
			if ($result==true && $result_==true){
				$data						= FacilityConsignmentAssocs::findFirst("facilityid='".NHAKHO."' AND consignmentid='".$update->consignmentid."'");
				return $this->showErrorJson(1,"Consignment",$data);
			}else{
				return $this->showErrorJson(0,"Consignment",$aParam);
			}
		}else{
			return $this->showErrorJson(2,"Consignment",$aParam);
		}
	}
	public function deleteAction(){
		$aParam = $this->getParam();
		$check = Consignment::findFirst("consignmentid='".$aParam['consignmentid']."'");
		if ($check == true){
			$this->execute(DATABASE,"DELETE FROM facility_consignment_assocs WHERE consignmentid='".$aParam['consignmentid']."'");
			if ($check->delete()==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Consignment",$aParam);
			}
		}else{
			return $this->showErrorJson(7,"Consignment",$aParam);
		}
	}
	public function queryAction() {
		$aParam = $this->getParam();
		
		$keyCache = 'product_query_'.sha1($aParam['where'].$aParam['group'].$aParam["order"].$aParam['offset'].$aParam['limit'].$aParam['language']);
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$sql = "WHERE 1";
			if(isset($aParam['where'])) $sql = "WHERE ".$aParam['where'];
			if(isset($aParam['group'])) $sql .= " GROUP BY ".$aParam['group'];
			if(isset($aParam['order'])) $sql .= " ORDER BY ".$aParam['order'];
			$columns = ",consignmentname_vi as consignmentname,description_vi as description";
			if ($aParam['language'] == EN) $columns = ",consignmentname_en as consignmentname,description_en as description";
			$result = $this->query(DATABASE, 
				"SELECT consignmentid,productid,rated,price,quantity,quantity_per_price,totalprice $columns ".
				" FROM consignment ".$sql." LIMIT ".$aParam['offset'].",".$aParam['limit']
			);
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
			return $this->showErrorJson(7,"Consignment");
		}
			
	}
}