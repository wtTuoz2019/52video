<?php

ini_set('date.timezone','Asia/Shanghai');
		//error_reporting(E_ERROR);
		require_once CP_CORE_PATH . "/../ext/wxpay/lib/WxPay.Api.php";
		require_once CP_CORE_PATH . "/../ext/wxpay/WxPay.JsApiPay.php";
		require_once CP_CORE_PATH . "/../ext/wxpay/log.php";
		require_once CP_CORE_PATH . "/../ext/wxpay/lib/WxPay.Notify.php";
class wxpayMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		
    }

	public function index() {
	
		
		//初始化日志
	$logHandler= new CLogFileHandler(CP_CORE_PATH . "/../ext/wxpay/logs/".date('Y-m-d').'.log');
	$log = Log::Init($logHandler, 15);
	 $openId=$_GET['openid'];
	$tools = new JsApiPay();
	$this->price=$price=floatval ($_POST['price']);
	
		if($price){
$price=$price*100;
//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("微信打赏");
$input->SetAttach($_POST['id']);
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee($price);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://".$this->config['MOBILE_DOMAIN']."/wxpay/notify");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);

echo $this->jsApiParameters = $tools->GetJsApiParameters($order);die;

}
		//$this->display('wxpay.html');
		}
	public function notify(){
		



//初始化日志
$logHandler= new CLogFileHandler(CP_CORE_PATH . "/../ext/wxpay/logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);



Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
		}
		
		
	public function signuppay() {
	
		
		//初始化日志
	$logHandler= new CLogFileHandler(CP_CORE_PATH . "/../ext/wxpay/logs/".date('Y-m-d').'.log');
	$log = Log::Init($logHandler, 15);
	 $openId=$_GET['openid'];
	$tools = new JsApiPay();
	$this->price=$price=floatval ($_POST['price']);

		if($price){
$price=$price*100;
//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("微信报名");
$input->SetAttach($_POST['id']);
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee($price);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://".$this->config['MOBILE_DOMAIN']."/wxpay/notifysignup");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);

echo $this->jsApiParameters = $tools->GetJsApiParameters($order);die;

}
		//$this->display('wxpay.html');
		}
	public function notifysignup(){
		



//初始化日志
$logHandler= new CLogFileHandler(CP_CORE_PATH . "/../ext/wxpay/logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);



Log::DEBUG("begin notify");
$notify = new SignupPayNotifyCallBack();
$notify->Handle(false);
		}	
		/*发红包*/
	public function sendredpack(){
		
		 $openId=$_GET['openid'];
		 $price=floatval ($_GET['price']);
	$input = new WxSendredpack();

$input->SetMch_billno(WxPayConfig::MCHID.date("YmdHis").rand(1000,9999));
$input->SetSend_name("闪阅云红包测试");
$input->SetTotal_amount($price*100);
$input->SetTotal_num(1);
$input->SetWishing('恭喜发财');
$input->SetAct_name("闪阅云活动");
$input->SetRemark("猜越多得越多，快来抢！");
$input->SetOpenid($openId);
$result = WxPayApi::unsendredpack($input);
  @header("Content-type:text/html");
echo json_encode($result);
		
	}



	public function sendredlog($price,$openid,$title,$name='闪阅云道',$remark='快来关注‘闪阅云道’，参与活动领红包！'){
		
	$input = new WxSendredpack();

$input->SetMch_billno(WxPayConfig::MCHID.date("YmdHis").rand(1000,9999));
$input->SetSend_name($title);
$input->SetTotal_amount($price*100);
$input->SetTotal_num(1);
$input->SetWishing('恭喜发财');
$input->SetAct_name($name);
$input->SetRemark($remark);//快来关注‘闪阅云道’，参与活动领红包！
$input->SetOpenid($openid);
$result = WxPayApi::unsendredpack($input);
 
return $result;
		
	}

}
	
class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
	
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{	
		
			
		
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{	
	

	
		
	
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		Log::DEBUG("out_trade_no:".$data['out_trade_no']."commentid:".$data['attach'] );
		//$this->send($data);
	Log::DEBUG(file_get_contents("http://wap.shanyueyun.com/comment/editpay?id=".$data['attach']));
	
		
		Log::DEBUG("end");
		
		return true;
	}
	
	}
	
class SignupPayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
	
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{	
		
			
		
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{	
	

	
		
	
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		Log::DEBUG("out_trade_no:".$data['out_trade_no']."signupid:".$data['attach'] );
		//$this->send($data);
		
	Log::DEBUG(file_get_contents("http://wap.shanyueyun.com/content/editsign?id=".$data['attach'].'&price='.($data['total_fee']/100)));
	
		
		Log::DEBUG("end");
		
		return true;
	}
	
	}
	