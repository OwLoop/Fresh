<?php
namespace Modules\Frontend\Controllers;
use Phalcon\Mvc\Controller;
use Modules\Frontend\Services\Utils;
use DateTime;

class BaseController extends Controller{

	public function initialize() {
		//$this->checkApiKey();
	}
	public function checkApiKey(){
		$apiKey = $this->request->getPost("api_key");
		if ($apiKey != API_KEY){
			return $this->showErrorJson('17','');
		}
	}
	public function checkPermission($permissions, $nameModule=null){
		$token = $this->request->getPost("token");
		$userInfo = $this->session->get(USER_SESSION);
		if ((!isset($userInfo))||(count($userInfo)<=0)){
			return $this->getMessage('5',$nameModule);
		}else{
			if ($token!=$userInfo['token']){
				return $this->getMessage('18',$nameModule);
			} else {
				if ($userInfo['role']==1){
					return $this->getMessage('1',$nameModule);
				} else if (in_array($userInfo['role'], $permissions)){
					return $this->getMessage('1',$nameModule);
				} else {
					return $this->getMessage('3',$nameModule);
				}
			}
		}
	}

	public function sendJson($json){
		$this->response->setContentType('application/json', 'UTF-8');
		$this->response->setContent($json);
		return $this->response->send();
	}
	
	public function getParam(){
		$aParam = array();
		
		$aParam["query"]    			= $this->request->getPost("query");
		$aParam["where"]    			= $this->request->getPost("where");
		$aParam["group"]    			= $this->request->getPost("group");
		$aParam["order"]    			= $this->request->getPost("order");
		$aParam["language"]  			= $this->request->getPost("language");
		$aParam["image"]     			= $this->request->getPost("image");
		$aParam["rated"]     			= $this->request->getPost("rated");
		
		
		$aParam["description_vi"]     	= $this->request->getPost("description_vi");
		$aParam["description_en"]     	= $this->request->getPost("description_en");
		
		$aParam["facilityid"]     		= $this->request->getPost("facilityid");
		$aParam["facilityname_vi"]  	= $this->request->getPost("facilityname_vi");
		$aParam["facilityname_en"]  	= $this->request->getPost("facilityname_en");

		$aParam["categoryid"]     		= $this->request->getPost("categoryid");
		$aParam["categoryname_vi"]  	= $this->request->getPost("categoryname_vi");
		$aParam["categoryname_en"]  	= $this->request->getPost("categoryname_en");

		$aParam["productid"]     		= $this->request->getPost("productid");
		$aParam["productname_vi"]     	= $this->request->getPost("productname_vi");
		$aParam["productname_en"]     	= $this->request->getPost("productname_en");
		$aParam["productname_key_vi"] 	= $this->request->getPost("productname_key_vi");
		$aParam["productname_key_en"] 	= $this->request->getPost("productname_key_en");
		
		$aParam["unit"]     			= $this->request->getPost("unit");

		$aParam["consignmentid"]     	= $this->request->getPost("consignmentid");
		$aParam["consignmentname_vi"]   = $this->request->getPost("consignmentname_vi");
		$aParam["consignmentname_en"]   = $this->request->getPost("consignmentname_en");
		$aParam["totalprice"]     		= $this->request->getPost("totalprice");
		
		$sale = $this->request->getPost("sale");
		$aParam["sale"]     			= ($sale <0)?0:$sale;
		$quantity = $this->request->getPost("quantity");
		$aParam["quantity"]     		= ($quantity <0)?0:$quantity;
		$price = $this->request->getPost("price");
		$aParam["price"]     			= ($price <0)?0:$price;
		$quantity_per_price = $this->request->getPost("quantity_per_price");
		$aParam["quantity_per_price"]   = ($quantity_per_price <0)?0:$quantity_per_price;
		
		$aParam["commentid"]     		= $this->request->getPost("commentid");
		$aParam["clientname"]     		= $this->request->getPost("clientname");
		$aParam["userid"]     			= $this->request->getPost("userid");
		$aParam["username"]     		= $this->request->getPost("username");
		$aParam["fullname"]    			= $this->request->getPost("fullname");
		$aParam["hash"]     			= $this->request->getPost("hash");
		$aParam["password"]     		= $this->request->getPost("password");
		$aParam["email"]     			= $this->request->getPost("email");
		$birthday = $this->request->getPost("birthday");
		$aParam["birthday"]     		= ($birthday >0&&$birthday<31)?$birthday:0;
		$birthmonth = $this->request->getPost("birthmonth");
		$aParam["birthmonth"]     		= ($birthmonth >0&&$birthmonth<13)?$birthmonth:0;
		$birthyear = $this->request->getPost("birthyear");
		$aParam["birthyear"]     		= ($birthyear > 1900&&($birthyear<(date('Y')-10)))?$birthyear:0;
		
		$aParam["phone"]     			= $this->request->getPost("phone");
		$aParam["address"]     			= $this->request->getPost("address");
		$aParam["note"]     			= $this->request->getPost("note");
		$aParam["confirm"]     			= $this->request->getPost("confirm");
		$aParam["deliverytime"]    		= $this->request->getPost("deliverytime");
		$aParam["paymentmethod"]    	= $this->request->getPost("paymentmethod");
		$aParam["video"]    			= $this->request->getPost("video");

		

		$aParam["type_upload"]  		= $this->request->getPost("type_upload");
		//////////
		$aParam							= array_filter($aParam);
		$search_key						= $this->request->getPost("search_key");
		$aParam["search_key"]			= Utils::toEnglish(($search_key == '')?'%':'%'.$search_key.'%');
		$offset			 				= $this->request->getPost("offset");
		$aParam["offset"]        		= ($offset == '' || $offset <= MIN_LIMIT) ? MIN_LIMIT : $offset;
		$limit							= $this->request->getPost("limit");
		$aParam["limit"] 				= ($limit == '' || ($limit < MIN_LIMIT || $limit > MAX_LIMIT)) ? MAX_LIMIT : $limit;
		return $aParam;
	}
	public function query($db, $sql){
		return $this->$db->query($sql)->fetchAll(\Phalcon\Db::FETCH_ASSOC);
	}
	public function execute($db, $sql){
		return $this->$db->execute($sql);
	}
	public function getCache($memcache, $keyCache){
		return $this->$memcache->get($keyCache);
	}
	public function saveCache($memcache, $keyCache, $data){
		$this->$memcache->save($keyCache,$data);
	}
	public function deleteCache($memcache, $keyCache){
		$this->$memcache->delete($keyCache);
	}
	public function randomToken($n){
		$string = implode(range('A','Z')).implode(range('a','z')).implode(range(0,9));
		for ($i = 0; $i < $n; $i++) {
			$randomKey = mt_rand(0, strlen($string)-1);
			$token .= $string[$randomKey];
		}
		return $token."!";
	}
	public function showErrorJson($staus, $nameModule=null,$data=null) {
		$this->response->setContentType('application/json', 'UTF-8');
		$this->response->setContent(json_encode($this->getMessage($staus, $nameModule,$data)));
		return $this->response->send();
	}
	public function getMessage($staus, $nameModule=null,$data=null){
		$result = array();
		switch ($staus) {
			case 0 : $result['status'] =false;$result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'Fail';break;
			case 1 : $result['status'] =true; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'Successful';break;
			case 2 : $result['status'] =false;$result['status_key'] =$staus;$result['data'] =$data;$result['message'] = $nameModule.' already exists!';break;
			case 3 : $result['status'] =false;$result['status_key'] =$staus;$result['data'] =$data;$result['message'] = "Permission denied to access ".$nameModule." controller!";break;
			case 4 : $result['status'] =false;$result['status_key'] =$staus;$result['data'] =$data;$result['message'] = "Please choose ".$nameModule." first!";break;
			case 5 : $result['status'] =false;$result['status_key'] =$staus;$result['data'] =$data;$result['message'] = "Please login!";break;
			case 6 : $result['status'] =false;$result['status_key'] =$staus;$result['data'] =$data;$result['message'] = "HTTPS 404 not nound error!";break;
			case 7 : $result['status'] =false;$result['status_key'] =$staus;$result['data'] =$data;$result['message'] = $nameModule.' not exists!';break;
			case 8 : $result['status'] =false;$result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'something error happens!';break;
			case 9 : $result['status'] =false;$result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'Some error ocurred!';break;
			case 10 : $result['status'] =false;$result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'You must choose at least one file to send. Please try again!';break;
			case 12 : $result['status'] =true; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'Files successfully uploaded!';break;
			case 13 : $result['status'] =false; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'Password Invalid!';break;
			case 14 : $result['status'] =false; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'Username Invalid!';break;
			case 15 : $result['status'] =false; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'Email already exists.';break;
			case 16 : $result['status'] =false; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'Username already exists.';break;
			case 17 : $result['status'] =false; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'API key Invalid!';break;
			case 18 : $result['status'] =false; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'Access Token Invalid!';break;
			case 19 : $result['status'] =false; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'Wrong parameters!';break;
			case 20 : $result['status'] =true; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'User already exists.';break;
			case 21 : $result['status'] =true; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'User not exists.';break;
			case 22 : $result['status'] =false; $result['status_key'] =$staus;$result['data'] =$data;$result['message'] = 'Quantity invalid.';break;
			default : $result['status'] =false;$result['status_key'] =404;$result['data'] =$data;$result['message'] = "Something error happens!";break;
		}
		return $result;
	}
	public function sendMail($mailTo,$template, $params){
		
		$this->mail_message->setTo(array($mailTo => 'Freshy.vn'));
 		$messageBody = $this->view->getRender('mail_templates', 'test',$params);		
		$this->mail_message->setBody($messageBody);
    	return $this->mail_mailer->send($this->mail_message, $error);
	}
	public function checkParams($required,$aParam){
		if(count(array_intersect_key(array_flip($required), $aParam)) === count($required)) {
			return true;
		} else {
			return false;
		}
	}
}
