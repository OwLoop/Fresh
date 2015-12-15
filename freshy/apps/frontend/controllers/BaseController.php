<?php
namespace Modules\Frontend\Controllers;
use Phalcon\Mvc\Controller;
use DateTime;

class BaseController extends Controller{

	public function initialize() {
		
	}
	public function sendJson($json){
		$this->response->setContentType('application/json', 'UTF-8');
		$this->response->setContent($json);
		return $this->response->send();
	}
	public function getParam(){
		$aParam = array();
		$aParam["token"] 				= $this->request->getQuery ("token");
		$aParam["data"] 				= unserialize($this->request->getQuery ("data"));
		$aParam							= array_filter($aParam);
		if (!isset($aParam['token'])) {
			$this->response->redirect ( "" );
		}
		// print_r($aParam);exit;
		return $aParam;
	}
	public function randomToken($n){
		$string = implode(range('A','Z')).implode(range('a','z')).implode(range(0,9));
		for ($i = 0; $i < $n; $i++) {
			$randomKey = mt_rand(0, strlen($string)-1);
			$token .= $string[$randomKey];
		}
		return $token."!";
	}
	public function showMessage($staus, $nameModule) {
		switch ($staus) {
			case 0 : $this->setView('message', 'Faild');break;
			case 1 : $this->setView('message', 'Successful');break;
			case 2 : $this->setView('message', $nameModule.' already exists!');break;
			case 3 : $this->setView('message', "You don't have permission!");break;
			case 4 : $this->setView('message', "Please choose ".$nameModule." first!");break;
		}
	}
	public function getJsonQuery($url) {
		$curl = curl_init ();
		curl_setopt_array ( $curl, array (
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));
		$json = curl_exec ( $curl );
		curl_close ( $curl );
		return json_decode ( $json, true );
	}
	public function getJsonPost($url, $data_post) {
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_post);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec ($ch);
	    $response = json_decode($response,true);
	    curl_close ($ch);
	    return $response;
	}
}
