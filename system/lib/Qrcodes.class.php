<?php

class Qrcodes{
	
	
	/**
	 * 构造函数
	 * 
	 * @return void
	 */
	public function __construct(){
		
	}
	
	public function _Qrcode($url,$filename)
	{		
		 require_once(dirname(__FILE__).'/phpqrcode/phpqrcode.php');
		 $value = $url; //二维码内容   
		$errorCorrectionLevel = 'L';//容错级别   
			$matrixPointSize = 6;//生成图片大小 
			
				//生成二维码图片  
			QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 
	}
	
}
?>