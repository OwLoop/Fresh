<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Models\User;
use DateTime;

class SigninController extends BaseController {
	public function initialize() {
	}
	public function indexAction() {
		$aParam = $this->getParam();
		if ($aParam['token'] == TOKEN){
			$data = $aParam['data'];
			$checkUser = User::findFirst("username='".$data['username']."' OR email='".$data['username']."'");
			if ($checkUser == true){
				$checkUser = $checkUser->toArray();
				if ($checkUser['password'] == sha1($checkUser['hash'].$data['password'])){
					unset($checkUser['hash']);unset($checkUser['password']);
					return $this->sendJson(json_encode(array("result"=>1,"data"=>$checkUser,"message"=>"Successful")));
				}
				return $this->sendJson(json_encode(array("result"=>0,"message"=>"Password Invalid!")));
			}
			return $this->sendJson(json_encode(array("result"=>0,"message"=>"Username Invalid!")));
		}
		return $this->response->redirect ( "" );
	}
	public function resgisterAction() {
		$aParam = $this->getParam();
		if ($aParam['token'] == TOKEN){
			$data = $aParam['data'];
			$checkUsername = User::findFirst("username='".$data['username']."'");
			if ($checkUsername == false){
				$checkEmail = User::findFirst("email='".$data['email']."'");
				if ($checkEmail == false){
					$data['userid'] 		= $this->randomToken(10);
					$data['token']			= $this->randomToken(20);
					$data['hash']			= $this->randomToken(10);
					$data['password']		= sha1($data['hash'].$data['password']);
					$data['date_created'] 	= (new DateTime())->format('Y-m-d H:i:s');
					$data['date_updated']	= (new DateTime())->format('Y-m-d H:i:s');
					$userNew = new User();
					$result = $userNew->save($data);
					if ($result == true){
						return $this->sendJson(json_encode(array("result"=>$result,"message"=>"Successful")));
					}else{
						return $this->sendJson(json_encode(array("result"=>0,"message"=>"Faild")));
					}
				}
				unset($data['email']);
				return $this->sendJson(json_encode(array("result"=>0,"data"=>$data,"message"=>"Email already exists.")));
			}
			unset($data['username']);
			return $this->sendJson(json_encode(array("result"=>0,"data"=>$data,"message"=>"Username already exists.")));
		}
		return $this->response->redirect ( "" );
	}
	public function checkUsernameAction() {
		$aParam = $this->getParam();
		if ($aParam['token'] == TOKEN){
			$data = $aParam['data'];
			$checkUsername = User::findFirst("username='".$data['username']."'");
			if ($checkUsername == false){
				return $this->sendJson(json_encode(array("result"=>1)));
			}
			return $this->sendJson(json_encode(array("result"=>0,"message"=>"Username already exists.")));
		}
		return $this->response->redirect ( "" );
	}
	public function checkEmailAction() {
		$aParam = $this->getParam();
		if ($aParam['token'] == TOKEN){
			$data = $aParam['data'];
			$checkEmail = User::findFirst("email='".$data['email']."'");
			if ($checkEmail == false){
				return $this->sendJson(json_encode(array("result"=>1)));
			}
			return $this->sendJson(json_encode(array("result"=>0,"message"=>"Email already exists.")));
		}
		return $this->response->redirect ( "" );
	}
}
