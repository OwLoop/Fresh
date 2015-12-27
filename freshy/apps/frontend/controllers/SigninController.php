<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Models\User;
use Modules\Frontend\ModelViews\UserView;
use DateTime;

class SigninController extends BaseController {
	public function initialize() {
	}
	public function indexAction() {
		$aParam = $this->getParam();
		$required = array("username","password");
		if($this->checkParams($required,$aParam)) {
			$checkUserSession = $this->session->get(USER_SESSION);
			if (isset($checkUserSession)&&($checkUserSession['username']==$aParam['username']||$checkUserSession['email']==$aParam['username'])){
				$this->session->set(USER_SESSION,$checkUserSession);
				return $this->showErrorJson(1,null,$checkUserSession);
			} else {
				$checkUser = UserView::findFirst(array("username='".$aParam['username']."' OR email='".$aParam['username']."'"));
				if ($checkUser == true){
					$checkUser = $checkUser->toArray();
					if ($checkUser['password'] == sha1($checkUser['hash'].$aParam['password'])){
						unset($checkUser['password']);
						unset($checkUser['hash']);
						$checkUser['token']=$this->randomToken(10);
						$this->session->set(USER_SESSION,$checkUser);
						return $this->showErrorJson(1,null,$checkUser);
					}
					return $this->showErrorJson(13,null);
				}
				return $this->showErrorJson(14,null);
			}
		}else{
			return $this->showErrorJson(19,null);
		}
	}
	public function resgisterAction() {
		$aParam = $this->getParam();
		$required = array("username","fullname","password","email","birthday","birthmonth","birthyear");
		if($this->checkParams($required,$aParam)) {
			$checkUsername = User::findFirst("username='".$aParam['username']."'");
			if ($checkUsername == false){
				$checkEmail = User::findFirst("email='".$aParam['email']."'");
				if ($checkEmail == false){
					$aParam['userid'] 		= $this->randomToken(10);
					$aParam['token']			= $this->randomToken(20);
					$aParam['hash']			= $this->randomToken(10);
					$aParam['password']		= sha1($aParam['hash'].$aParam['password']);
					$aParam['created_at'] 	= (new DateTime())->format('Y-m-d H:i:s');
					$aParam['updated_at']	= (new DateTime())->format('Y-m-d H:i:s');
					$userNew = new User();
					$result = $userNew->save($aParam);
					if ($result == true){
						unset($aParam['password']);
						unset($aParam['hash']);
						$this->session->set(USER_SESSION,$aParam);
						return $this->showErrorJson(1,null,$aParam);
						
					}else{
						return $this->showErrorJson(0,null);
					}
				} else {
					return $this->showErrorJson(15,null);
				}
			} else {
				return $this->showErrorJson(16,null);
			}
		}else{
			return $this->showErrorJson(19,null);
		}
	}
	public function checkUserAction() {
		$aParam = $this->getParam();
		$checkUsername = UserView::findFirst(array("username='".$aParam['username']."' OR email='".$aParam['username']."'"));
		if ($checkUsername){
			return $this->showErrorJson(20,null);
		}
		return $this->showErrorJson(21,null);
	}
}
