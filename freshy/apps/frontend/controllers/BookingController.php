<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Models\Booking;
use DateTime;

class BookingController extends BaseController {

	public function initialize() {
		// parent::initialize();
	}
	public function queryAction() {
		$aParam = $this->getParam();
		$keyCache = 'booking_query_'.sha1($aParam['where'].$aParam['group'].$aParam["order"].$aParam['offset'].$aParam['limit']);
		// $this->deleteCache(MEMCACHE,$keyCache);
		$resultCache = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($resultCache)){
			$sql = "WHERE 1";
			if(isset($aParam['where'])) $sql = "WHERE ".$aParam['where'];
			if(isset($aParam['group'])) $sql .= " GROUP BY ".$aParam['group'];
			if(isset($aParam['order'])) $sql .= " ORDER BY ".$aParam['order'];
			$result = $this->query(DATABASE,
				"SELECT * FROM booking ".$sql." LIMIT ".$aParam['offset'].",".$aParam['limit']
			);
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}else $result = $resultCache;
		
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Booking");
		}
			
	}

	public function findFirstAction() {
		$aParam = $this->getParam();
		
		$keyCache = 'booking_findfirst_'.$aParam['id'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$resultCache = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($resultCache)){
			$result = Booking::findFirst("bookingid='".$aParam['bookingid']."'");
			if ($result == true) {
				$result = $result->toArray();
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}else $result = $resultCache;
		
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Booking");
		}
			
	}
	public function findAllAction() {
		$aParam = $this->getParam();

		$keyCache = 'booking_findall';
		// $this->deleteCache(MEMCACHE,$keyCache);
		$resultCache = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($resultCache)){
			$result = Booking::find();
			if ($result == true) {
				$result = $result->toArray();
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}else $result = $resultCache;
		
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Booking");
		}
	}
	public function addAction(){
		$aParam = $this->getParam();
		$check = Booking::findFirst("bookingname='".$aParam['bookingname']."'");
		if ($check == false){
			$aParam["bookingid"]     		= $this->randomToken(10);
			// $aParam["editor"]     		= "";
			$aParam["created_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$new = new Booking();
			if ($new->save($aParam)==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Booking",$aParam);
			}
		}else{
			return $this->showErrorJson(2,"Booking",$aParam);
		}
	}
	public function editAction(){
		$aParam = $this->getParam();
		$check = Booking::findFirst("bookingname='".$aParam['bookingname']."' AND bookingid<>'".$aParam['bookingid']."'");
		if ($check == false){
			// $aParam["editor"]     		= "";
			$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$update = Booking::findFirst("bookingid='".$aParam['bookingid']."'");
			if ($update->update($aParam)==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Booking",$aParam);
			}
		}else{
			return $this->showErrorJson(2,"Booking",$aParam);
		}
	}
	public function deleteAction(){
		$aParam = $this->getParam();
		$check = Booking::findFirst("bookingid='".$aParam['bookingid']."'");
		if ($check == true){
			$this->execute(DATABASE,"DELETE FROM booking WHERE parentid='".$aParam['bookingid']."'");
			$this->execute(DATABASE,"DELETE FROM product WHERE bookingid='".$aParam['bookingid']."'");
			$this->execute(DATABASE,"DELETE FROM comment WHERE bookingid='".$aParam['bookingid']."'");
			if ($check->delete()==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Booking",$aParam);
			}
		}else{
			return $this->showErrorJson(7,"Booking",$aParam);
		}
	}
}