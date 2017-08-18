<?php
class smsMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		$this->appkey='23365622';
		$this->secret='423a30b9c2f0ecf48c3613bd5ba143f4';
    }

	public function index() {
		
		
		if(!$this->userinfo){echo 0;die;}
		
		
$mobile =$_POST['mobile'];
$rand=rand(1000,9999);
  $expire = time() + 7200;
   setcookie('mobilecode',$rand,$expire,'/');
require(CP_CORE_PATH . '/../ext/TopSdk.php');
 $c = new TopClient;
$c ->appkey = $this->appkey ;
$c ->secretKey = $this->secret ;
$req = new AlibabaAliqinFcSmsNumSendRequest;
$req ->setSmsType( "normal" );
$req ->setSmsFreeSignName( "闪阅云" );
$req ->setSmsParam( "{code:'".$rand."'}" );
$req ->setRecNum( $mobile );
$req ->setSmsTemplateCode( "SMS_9545056" );
$resp = $c ->execute( $req );
if($resp->code){
	echo 0;	
	}else{ 
	model('common')->smslog(array('uid'=>$this->userinfo['uid'],'mobile'=>$mobile,'content'=>'SMS_9545056','time'=>date('Ymd H:i:s')));	
echo 1;	
		}


	}


	}