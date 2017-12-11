<?php
//监看显示
error_reporting(E_ALL^E_NOTICE^E_WARNING);
use Cdn\Request\V20141111 as Cdn; 
class watchMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		
    } 
	
	public function index(){
		 $this->common=model('pageinfo')->media('云监播');
		 
		 	require(CP_CORE_PATH . '/../ext/aliyunsdk/aliyun-php-sdk-core/Config.php');
					
$iClientProfile = DefaultProfile::getProfile("cn-shanghai",$this->config['newAccessKeyId'], $this->config['newAccessSecret']);
			$client = new DefaultAcsClient($iClientProfile); 
			$request = new Cdn\DescribeLiveStreamsOnlineListRequest();  
			$request->setMethod("GET");
			$request->setDomainName($this->config['DomainName']);  
			$request->setAppName($this->config['AppName']);
			$response = $client->getAcsResponse($request); 
			$livelist=model('field')->getlaterlive();
			$playlist=array();
				$school=model('school')->school_list();
			if($response->OnlineInfo->LiveStreamOnlineInfo){
				foreach($response->OnlineInfo->LiveStreamOnlineInfo as $key=>$val){
					foreach($livelist as $k=>$v){
						if($val->StreamName==$v['sn']&&!$playlist[$val->StreamName]){
							$v['status']=$v['starttime']>time()?'未开始':'正在直播';
							$v['school']=$school[$v['csid']]['name'];
							$playlist[$val->StreamName]=$v;
							
							}
						}
					
					}
				}
			$this->playlist=$playlist;
			$this->display('watch.html');
		}
	public function getlivelist(){
		
		 	require(CP_CORE_PATH . '/../ext/aliyunsdk/aliyun-php-sdk-core/Config.php');
					
$iClientProfile = DefaultProfile::getProfile("cn-shanghai",$this->config['newAccessKeyId'], $this->config['newAccessSecret']);
			$client = new DefaultAcsClient($iClientProfile); 
			$request = new Cdn\DescribeLiveStreamsOnlineListRequest();  
			$request->setMethod("GET");
			$request->setDomainName($this->config['DomainName']);  
			$request->setAppName($this->config['AppName']);
			$response = $client->getAcsResponse($request); 
			$livelist=model('field')->getlaterlive();
			$playlist=array();
			$school=model('school')->school_list();
			if($response->OnlineInfo->LiveStreamOnlineInfo){
				foreach($response->OnlineInfo->LiveStreamOnlineInfo as $key=>$val){
					foreach($livelist as $k=>$v){
						if($val->StreamName==$v['sn']&&!$playlist[$val->StreamName]){
							$v['status']=$v['starttime']>time()?'未开始':'正在直播';
							$v['school']=$school[$v['csid']]['name'];
							$playlist[$val->StreamName]=$v;
							
							}
						}
					
					}
				}
		
			$this->msg($playlist,1);
		}

		public function liveonline(){
		
		 	require(CP_CORE_PATH . '/../ext/aliyunsdk/aliyun-php-sdk-core/Config.php');
					
$iClientProfile = DefaultProfile::getProfile("cn-shanghai",$this->config['newAccessKeyId'], $this->config['newAccessSecret']);
			$client = new DefaultAcsClient($iClientProfile); 
			$request = new Cdn\DescribeLiveStreamsOnlineListRequest();  
			$request->setMethod("GET");
			$request->setDomainName($this->config['DomainName']);  
			$request->setAppName($this->config['AppName']);
			$response = $client->getAcsResponse($request); 
			$sns=array();
		
			if($response->OnlineInfo->LiveStreamOnlineInfo){
				foreach($response->OnlineInfo->LiveStreamOnlineInfo as $key=>$val){
					$sns[$val->StreamName]=1;
					
					}
				}
		
		
			$this->msg($sns,1);
		}
}