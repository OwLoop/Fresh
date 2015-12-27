<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Models\Comment;
use Modules\Frontend\ModelViews\CommentView;
use DateTime;

class CommentController extends BaseController {

	public function initialize() {
		// parent::initialize();
	}
	public function queryAction() {
		$aParam = $this->getParam();
		$keyCache = 'comment_query_'.sha1($aParam['where'].$aParam['group'].$aParam["order"].$aParam['offset'].$aParam['limit']);
		// $this->deleteCache(MEMCACHE,$keyCache);
		$resultCache = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($resultCache)){
			$sql = "WHERE 1";
			if(isset($aParam['where'])) $sql = "WHERE ".$aParam['where'];
			if(isset($aParam['group'])) $sql .= " GROUP BY ".$aParam['group'];
			if(isset($aParam['order'])) $sql .= " ORDER BY ".$aParam['order'];
			$result = $this->query(DATABASE,
				"SELECT ".CommentView::$needGet['columns']." FROM comment ".$sql." LIMIT ".$aParam['offset'].",".$aParam['limit']
			);
			if ($result == true) {
				$this->saveCache(MEMCACHE,$keyCache,$result);
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
			return $this->showErrorJson(7,"Comment");
		}
			
	}
	public function lazyloadAction() {
		$aParam = $this->getParam();
		$keyCache = 'comment_query_'.sha1($aParam["order"].$aParam['offset'].$aParam['limit']);
		$this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$sql = "WHERE 1";
			
			if(isset($aParam['order'])) $sql .= " ORDER BY ".$aParam['order'];
			$result = $this->query(DATABASE,
				"SELECT ".CommentView::$needGet['columns']." FROM comment ".$sql." LIMIT ".$aParam['offset'].",".$aParam['limit']
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
			return $this->showErrorJson(7,"Comment");
		}
			
	}
	public function findFirstAction() {
		$aParam = $this->getParam();
		
		$keyCache = 'comment_findfirst_'.$aParam['commentid'];
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$result = CommentView::findFirst("commentid='".$aParam['commentid']."'");
			if ($result == true) {
				$result = $result->toArray();
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Comment");
		}
			
	}
	public function findAllAction() {
		$aParam = $this->getParam();

		$keyCache = 'comment_findall';
		// $this->deleteCache(MEMCACHE,$keyCache);
		$result = $this->getCache(MEMCACHE,$keyCache);
		if (!isset($result)){
			$result = CommentView::find();
			if ($result == true) {
				$result = $result->toArray();
				$this->saveCache(MEMCACHE,$keyCache,$result);
			}
		}
		if ($result == true){
			return $this->sendJson(json_encode($result));
		}else{
			return $this->showErrorJson(7,"Comment");
		}
	}
	public function addAction(){
		$aParam = $this->getParam();
		$aParam["commentid"]     		= $this->randomToken(10);
		$aParam["created_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
		$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
		$new = new Comment();
		if ($new->save($aParam)==true){
			return $this->showErrorJson(1);
		}else{
			return $this->showErrorJson(0,"Comment",$aParam);
		}
	}
	public function editAction(){
		$aParam = $this->getParam();
		$update = Comment::findFirst("commentid='".$aParam['commentid']."'");
		if ($update == false){
			$aParam["updated_at"]     		= (new DateTime())->format('Y-m-d H:i:s');
			if ($update->update($aParam)==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Comment",$aParam);
			}
		}else{
			return $this->showErrorJson(2,"Comment",$aParam);
		}
	}
	public function deleteAction(){
		$aParam = $this->getParam();
		$check = Comment::findFirst("commentid='".$aParam['commentid']."'");
		if ($check == true){
			if ($check->delete()==true){
				return $this->showErrorJson(1);
			}else{
				return $this->showErrorJson(0,"Comment",$aParam);
			}
		}else{
			return $this->showErrorJson(7,"Comment",$aParam);
		}
	}
}