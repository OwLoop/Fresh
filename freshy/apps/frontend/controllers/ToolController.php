<?php

namespace Modules\Frontend\Controllers;
use Modules\Frontend\Services;
use Modules\Frontend\Services\Utils;
use DateTime;

class ToolController extends BaseController {
	public function initialize() {
	}
	public function imageUploadAction() {
		//check permission
		$result = array(
			"result"	=>	false,
			"message"	=>	"Something error happens",
			"data"		=>	null
		);
		#check if there is any file
		$aParam = $this->getParam();
		if($this->request->hasFiles() == true){
			$uploads = $this->request->getUploadedFiles();
			$isUploaded = false;
			$reponseNameImage = array();
			#do a loop to handle each file individually
			foreach($uploads as $upload){
				#define a “unique” name and a path to where our file must go
				$path = '/home/media.freshy.vn';
				switch ($aParam['type_upload']) {
					case 'product_image':
						$path = '/home/media.freshy.vn/product_images/';
						break;
					default:
						$path = '/home/media.freshy.vn/temps/';
				}
				$imageName = md5(uniqid(rand(), true)).'-'.strtolower($upload->getname());
				array_push($reponseNameImage,$imageName);
				$fileName = $path.$imageName;
				#move the file and simultaneously check if everything was ok
				($upload->moveTo($fileName)) ? $isUploaded = true : $isUploaded = false;
			}
			#if any file couldn’t be moved, then throw an message
			if ($isUploaded){
				
				return $this->showErrorJson(11,null,$reponseNameImage);
			} else {
				return $this->showErrorJson(9,null,null);
			}
		}else{
			#if no files were sent, throw a message warning user
			return $this->showErrorJson(10,null,null);
		}
	}
}
