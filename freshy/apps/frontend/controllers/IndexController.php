<?php

namespace Modules\Frontend\Controllers;
use DateTime;
use SimpleXMLElement;
class IndexController extends BaseController {
	public function initialize() {
	}
	public function indexAction() {
		//Category
		$apiCategoryLayzyLoad = array(
			'url'=>"https://api.freshy.vn/category/lazyLoad",
			'parameters'=>array(
									"param1"=>"search_key=",
									"param2"=>"order=created_at DESC",
									"param3"=>"offset=0",
									"param4"=>"limit=20"
								),
			'message'=>'get list category with limit, offset and order',
			'help'=>'Bình thường thì "search_key=" nếu muốn search thì truyền key vào, order hiện tại dùng "created_at DESC" hoặc "created_at ASC"'
		);
		$apiCategoryfindFirst = array(
			'url'=>"https://api.freshy.vn/category/findFirst",
			'parameters'=>array("param1"=>"categoryid"),
			'message'=>'get category info by Id',
		);
		$apiCategoryFindAll = array(
			'url'=>"https://api.freshy.vn/category/findAll",
			
			'message'=>'get all List',
		);
		//Product
		$apiProductLayzyLoad = array(
			'url'=>"https://api.freshy.vn/product/lazyLoad",
			'parameters'=>array(
									"param1"=>"search_key=",
									"param2"=>"order=created_at DESC",
									"param3"=>"offset=0",
									"param4"=>"limit=20"
								),
			'message'=>'get list category with limit, offset and order',
			'help'=>'Bình thường thì "search_key=" nếu muốn search thì truyền key vào, order hiện tại dùng "created_at DESC" hoặc "created_at ASC"'
		);
		$apiProductfindFirst = array(
			'url'=>"https://api.freshy.vn/product/findFirst",
			'parameters'=>array("param1"=>"productid"),
			'message'=>'get category info by Id',
		);
		$apiProductFindAll = array(
			'url'=>"https://api.freshy.vn/product/findAll",
			
			'message'=>'get all List',
		);
		//Comment
		$apiCommentLayzyLoad = array(
			'url'=>"https://api.freshy.vn/comment/lazyLoad",
			'parameters'=>array(
									
									"param1"=>"order=created_at DESC",
									"param2"=>"offset=0",
									"param3"=>"limit=20"
								),
			'message'=>'get list category with limit, offset and order',
			'help'=>'Comment không cho search, order hiện tại dùng "created_at DESC" hoặc "created_at ASC"'
		);
		$apiCommentfindFirst = array(
			'url'=>"https://api.freshy.vn/comment/findFirst",
			'parameters'=>array("param1"=>"commentid"),
			'message'=>'get category info by Id',
		);
		$apiCommentFindAll = array(
			'url'=>"https://api.freshy.vn/comment/findAll",
			
			'message'=>'get all List',
		);
		$api = array(
			"Category"=>array(
				"LayzyLoad"=>$apiCategoryLayzyLoad,
				"FindFirst"=>$apiCategoryfindFirst,
				"FindAll"=>$apiCategoryFindAll,
			),
			"Product"=>array(
				"LayzyLoad"=>$apiProductLayzyLoad,
				"FindFirst"=>$apiProductfindFirst,
				"FindAll"=>$apiProductFindAll,
			),
			"Comment"=>array(
				"LayzyLoad"=>$apiCommentLayzyLoad,
				"FindFirst"=>$apiCommentfindFirst,
				"FindAll"=>$apiCommentFindAll,
			)
		);
		//print_r($api);exit;
		$result = $this->array_to_xml($api, new SimpleXMLElement('<root/>'))->asXML();
		$this->response->setContentType('text/xml', 'UTF-8');
		$this->response->setContent($result);
		return $this->response->send();
		
	}
	function array_to_xml(array $arr, SimpleXMLElement $xml){
		foreach ($arr as $k => $v) {
			is_array($v)? $this->array_to_xml($v, $xml->addChild($k)) : $xml->addChild($k, $v);
		}
		return $xml;
	}
}
