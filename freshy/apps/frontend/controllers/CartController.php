<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Models\Cart;
use Modules\Frontend\ModelViews\CartView;
use DateTime;

class CartController extends BaseController {
	public function initialize() {
		parent::initialize();
	}
	public function queryAction() {
		$aParam = $this->getParam();
		$keyCache = 'cart_query_'.sha1($aParam['where'].$aParam['group'].$aParam["order"].$aParam['offset'].$aParam['limit']);
		// $this->deleteCache(MEMCACHE,$keyCache);
		$resultCache = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($resultCache)){
			$sql = "WHERE 1";
			if(isset($aParam['where'])) $sql = "WHERE ".$aParam['where'];
			if(isset($aParam['group'])) $sql .= " GROUP BY ".$aParam['group'];
			if(isset($aParam['order'])) $sql .= " ORDER BY ".$aParam['order'];
			$result = $this->query(DATABASE,
				"SELECT ".CartView::$needGet['columns']." FROM cart ".$sql." LIMIT ".$aParam['offset'].",".$aParam['limit']
			);
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}else $result = $resultCache;
		
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Cart");
		}
			
	}

	public function findFirstAction() {
		$aParam = $this->getParam();
		
		$keyCache = 'cart_findfirst_'.$aParam['cartid'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$result = CartView::findFirst(array("cartid"=>$aParam['cartid']));
			if ($result == true) {
				$result = $result->toArray();
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Cart");
		}
			
	}
	public function findAllAction() {
		$aParam = $this->getParam();

		$keyCache = 'cart_findall';
		// $this->deleteCache(MEMCACHE,$keyCache);
		$resultCache = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($resultCache)){
			$result = CartView::find();
			if ($result == true) {
				$result = $result->toArray();
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}else $result = $resultCache;
		
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Cart");
		}
	}
	public function addAction(){
		//check permission
		
		$aParam = $this->getParam();
		$check = Cart::findFirst("cartname='".$aParam['cartname']."'");
		if ($check == false){
			$aParam["categoryid"]     		= $this->randomToken(10);
			// $aParam["editor"]     		= "";
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
		$check = Category::findFirst("categoryname='".$aParam['categoryname']."' AND categoryid<>'".$aParam['categoryid']."'");
		if ($check == false){
			// $aParam["editor"]     		= "";
			$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			$update = Category::findFirst("categoryid='".$aParam['categoryid']."'");
			if ($update->update($aParam)==true){
				return $this->showErrorJson(1);
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
			$this->execute(DATABASE,"DELETE FROM category WHERE parentid='".$aParam['categoryid']."'");
			$this->execute(DATABASE,"DELETE FROM product WHERE categoryid='".$aParam['categoryid']."'");
			$this->execute(DATABASE,"DELETE FROM comment WHERE categoryid='".$aParam['categoryid']."'");
			if ($check->delete()==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Category",$aParam);
			}
		}else{
			return $this->showErrorJson(7,"Category",$aParam);
		}
	}
}