<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Models\Consignment;
use Modules\Frontend\Models\FacilityConsignmentAssocs;
use DateTime;

class OperationController extends BaseController {

	public function initialize() {
		// parent::initialize();
	}

	public function datastorehouseAction() {
		$aParam = $this->getParam();
		
		$keyCache = 'operation_datastorehouse_'.$aParam['language'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$result = $this->query(DATABASE, 
				"SELECT fc.created_at,fc.consignmentid,c.productid,c.price,fc.quantity,c.quantity_per_price,".
				"(c.price*fc.quantity/c.quantity_per_price) as totalprice ".
				" FROM consignment c INNER JOIN facility_consignment_assocs fc ON c.consignmentid=fc.consignmentid ".
				" WHERE facilityid='".NHAKHO."'"
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
	public function datastoreAction() {
		$aParam = $this->getParam();
		
		$keyCache = 'operation_datastore_'.$aParam['language'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$result = $this->query(DATABASE, 
				"SELECT fc.created_at,fc.consignmentid,c.productid,c.price,fc.quantity,c.quantity_per_price,".
				"(c.price*fc.quantity/c.quantity_per_price) as totalprice ".
				" FROM consignment c INNER JOIN facility_consignment_assocs fc ON c.consignmentid=fc.consignmentid ".
				" WHERE facilityid='".CUAHANG."'"
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
	public function xuatkhoAction(){
		$aParam = $this->getParam();
		$kho = FacilityConsignmentAssocs::findFirst("facilityid='".NHAKHO."' AND consignmentid='".$aParam['consignmentid']."'");
		if ($kho == true){
			$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			if ($aParam['quantity'] <= $kho->quantity){
				$kho->quantity = $kho->quantity-$aParam['quantity'];
				$result = $kho->update();
				$cuahang = FacilityConsignmentAssocs::findFirst("facilityid='".$aParam['facilityid']."' AND consignmentid='".$aParam['consignmentid']."'");
				if ($cuahang == true){
					$cuahang->quantity = $cuahang->quantity+$aParam['quantity'];
					$result_ = $cuahang->update();
				}else{
					$aParam["created_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
					$new = new FacilityConsignmentAssocs();
					$result_ = $new->save($aParam);
				}
				if ($result==true && $result_==true){
					return $this->showErrorJson(1);
				}else{
					return $this->showErrorJson(0,"Consignment",$aParam);
				}
			}else{
				return $this->showErrorJson(22,"Consignment",$aParam);
			}
		}else{
			return $this->showErrorJson(2,"Consignment",$aParam);
		}
	}
	
}