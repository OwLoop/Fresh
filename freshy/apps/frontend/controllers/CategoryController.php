<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Models\Category;
use Modules\Frontend\ModelViews\CategoryView;

use DateTime;

class CategoryController extends BaseController {

	public function initialize() {
		parent::initialize();
	}
	public function findFirstAction() {
		$aParam = $this->getParam();
		
		$keyCache = 'category_findfirst_'.$aParam['language'].$aParam['categoryid'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$columns = ",categoryname_vi as categoryname";
			if ($aParam['language'] == EN) $columns = ",categoryname_en as categoryyname";
			$result = $this->query(DATABASE, "SELECT categoryid $columns FROM category WHERE categoryid='".$aParam['categoryid']."'");
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Category");
		}
			
	}
	public function findAllAction() {
		$aParam = $this->getParam();
		
		$keyCache = 'category_findall'.$aParam['language'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$columns = ",categoryname_vi as categoryname";
			if ($aParam['language'] == EN) $columns = ",categoryname_en as categoryyname";
			$result = $this->query(DATABASE, "SELECT categoryid $columns FROM category");
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Category");
		}
	}
	public function addAction(){
		//check permission
		
		$aParam = $this->getParam();
		$check = Category::findFirst("categoryname_vi='".$aParam['categoryname_vi']."' OR categoryname_en='".$aParam['categoryname_en']."'");
		if ($check == false){
			$aParam["categoryid"]     		= $this->randomToken(10);
			$aParam["created_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$new = new Category();
			if ($new->save($aParam)==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Category",$aParam);
			}
		}else{
			return $this->showErrorJson(2,"Category",$aParam);
		}
	}
	public function editAction(){
		//check permission
		
		$aParam = $this->getParam();
		$check = Category::findFirst("(categoryname_vi='".$aParam['categoryname_vi']."' OR categoryname_en='".$aParam['categoryname_en']."') AND categoryid<>'".$aParam['categoryid']."'");
		if ($check == false){
			// $aParam["editor"]     		= "";
			$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$update = Category::findFirst("categoryid='".$aParam['categoryid']."'");
			if ($update->update($aParam)==true){
				$data = Category::findFirst("categoryid='".$aParam['categoryid']."'");
				return $this->showErrorJson(1,"Category",$data);
			}else{
				return $this->showErrorJson(0,"Category",$aParam);
			}
		}else{
			return $this->showErrorJson(2,"Category",$aParam);
		}
	}
	public function deleteAction(){
		//check permission
		
		$aParam = $this->getParam();
		$check = Category::findFirst("categoryid='".$aParam['categoryid']."'");
		if ($check == true){
			$this->execute(DATABASE,"DELETE FROM product WHERE categoryid='".$aParam['categoryid']."'");
			if ($check->delete()==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Category",$aParam);
			}
		}else{
			return $this->showErrorJson(7,"Category",$aParam);
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
			$columns = ",categoryname_vi as categoryname";
			if ($aParam['language'] == EN) $columns = ",categoryname_en as categoryyname";
			$result = $this->query(DATABASE, "SELECT categoryid $columns FROM category ".$sql." LIMIT ".$aParam['offset'].",".$aParam['limit']);
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