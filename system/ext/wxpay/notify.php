<?php
ini_set('date.timezone','Asia/Shanghai');
	error_reporting(E_ERROR);

require_once "lib/WxPay.Api.php";
require_once 'lib/WxPay.Notify.php';
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);


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
				Log::DEBUG("out_trade_no:".$data['out_trade_no'] );
		//$this->send($data);
	
		Log::DEBUG(file_get_contents('http://fuyou.linepoinfo.com/index.php?g=Wap&m=Guahao&a=sendcharge&token=dbfmjt1408528891f&attach='.$data['attach'].'&total_fee='.$data['total_fee'].'&out_trade_no='.$data['out_trade_no']));
		
		Log::DEBUG("end");
		
		return true;
	}
	public function send($data){
	
	$client = new SoapClient("http://192.168.100.212:8080/WeiXinServices/services/Services?wsdl",array('encoding'=>'UTF-8','trace' => true));	
	$client->__setLocation('http://192.168.100.212:8080/WeiXinServices/services/Services.ServicesHttpSoap11Endpoint/"');

	try {
	
		
		$param["request"]='<root>
		<head>
		<actdate>'.date('Ymd').'</actdate>
		<trdate>'.date('Ymd').'</trdate>
		<trtime>'.date('His').'</trtime>
		<trcode>WRITEZHYE</trcode>
		<hisseq></hisseq>
		<bhpseq></bhpseq>
		<filenum>0</filenum>
		<chncode></chncode>
		<termno>微信</termno>
		<mac></mac>
		</head>
		<body>
		<jdseq></jdseq>
		<brid>'.$data['attach'].'</brid>             
		<czje>'.($data['total_fee']/100).'</czje>       
		<jylsh>'.$data['out_trade_no'].'</jylsh>
		<jyrq>'.date('Y-m-d').'</jyrq>  
	</body>
	</root>';
	
		$return = $client->MoneyAll($param);
	  $xml = (string)$return->return;
	Log::DEBUG("begin return :".$xml);
		}
		catch (SoapFault $f){
	
        echo "Error Message: {$f->getMessage()}";
		}
	
	}
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
