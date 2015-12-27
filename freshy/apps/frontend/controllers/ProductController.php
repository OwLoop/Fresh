<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Models\Product;
use Modules\Frontend\ModelViews\ProductView;
use Modules\Frontend\Services\Utils;
use DateTime;

class ProductController extends BaseController {

	public function initialize() {
		// parent::initialize();
	}
	
	public function lazyloadAction() {
		$aParam = $this->getParam();
		
		$keyCache = 'product_query_'.sha1($aParam['search_key'].$aParam["order"].$aParam['offset'].$aParam['limit']);
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$sql = 'WHERE productname_key like "'.$aParam['search_key'].'"';
			if(isset($aParam['order'])) $sql .= " ORDER BY ".$aParam['order'];
			$result = $this->query(DATABASE,
				"SELECT ".ProductView::$needGet['columns']." FROM product ".$sql." LIMIT ".$aParam['offset'].",".$aParam['limit']
			);
			if ($result == true) {
				//if ($aParam['search_key']==''){
					$this->saveCache(MEMCACHE,$keyCache,$result);
				//}
			}
		}
		if ($result == true){
			$response = array (
				"offset"	=> count($result),
				"limit"		=> $aParam['limit'],
				"data"		=> $result
			);
			return $this->sendJson(json_encode($response));
		}else{
			return $this->showErrorJson(7,"Product");
		}
			
	}

	public function findFirstAction() {
		$aParam = $this->getParam();
		
		$keyCache = 'product_findfirst_'.$aParam['language'].$aParam['productid'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$columns = ",productname_vi as productname,description_vi as description";
			if ($aParam['language'] == EN) $columns = ",productname_en as productname,description_en as description";
			$result = $this->query(DATABASE, 
				"SELECT productid,categoryid,rated,price,sale,quantity_per_price,unit $columns ".
				" FROM product WHERE productid='".$aParam['productid']."'"
			);
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Product");
		}
			
	}
	public function findAllAction() {
		$aParam = $this->getParam();

		$keyCache = 'product_findall'.$aParam['language'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$columns = ",productname_vi as productname,description_vi as description";
			if ($aParam['language'] == EN) $columns = ",productname_en as productname,description_en as description";
			$result = $this->query(DATABASE, "SELECT productid,categoryid,rated,price,sale,quantity_per_price,unit $columns  FROM product");
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Product");
		}
	}
	public function addAction(){
		$aParam = $this->getParam();
		$check = Product::findFirst("productname_vi='".$aParam['productname_vi']."' OR productname_en='".$aParam['productname_en']."'");
		if ($check == false){
			$aParam["productid"]     		= $this->randomToken(10);
			$aParam["created_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$aParam['productname_key_vi']	= Utils::toEnglish($aParam['productname_vi']);
			$aParam['productname_key_en']	= Utils::toEnglish($aParam['productname_en']);
			$new = new Product();
			if ($new->save($aParam)==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Product",$aParam);
			}
		}else{
			return $this->showErrorJson(2,"Product",$aParam);
		}
	}
	public function editAction(){
		$aParam = $this->getParam();
		$check = Product::findFirst("(productname_vi='".$aParam['productname_vi']."' OR productname_en='".$aParam['productname_en']."') AND productid<>'".$aParam['productid']."'");
		if ($check == false){
			$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$aParam['productname_key_vi']	= Utils::toEnglish($aParam['productname_vi']);
			$aParam['productname_key_en']	= Utils::toEnglish($aParam['productname_en']);
			$update = Product::findFirst("productid='".$aParam['productid']."'");
			if ($update->update($aParam)==true){
				$data 						= Product::findFirst("productid='".$aParam['productid']."'");
				return $this->showErrorJson(1,"Product","Product",$aParam);
			}else{
				return $this->showErrorJson(0,"Product",$aParam);
			}
		}else{
			return $this->showErrorJson(2,"Product",$aParam);
		}
	}
	public function deleteAction(){
		$aParam = $this->getParam();
		$check = Product::findFirst("productid='".$aParam['productid']."'");
		if ($check == true){
			$this->execute(DATABASE,"DELETE FROM comment WHERE productid='".$aParam['productid']."'");
			if ($check->delete()==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Product",$aParam);
			}
		}else{
			return $this->showErrorJson(7,"Product",$aParam);
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
			$columns = ",productname_vi as productname,description_vi as description";
			if ($aParam['language'] == EN) $columns = ",productname_en as productname,description_en as description";
			$result = $this->query(DATABASE, 
				"SELECT productid,categoryid,rated,price,sale,quantity_per_price,unit $columns ".
				" FROM product ".$sql." LIMIT ".$aParam['offset'].",".$aParam['limit']
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
			return $this->showErrorJson(7,"Product");
		}
			
	}
}