   <!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="nofollow">
<meta charset="utf-8">
<title>就诊卡充值</title>
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1, user-scalable=no">
<meta http-equiv="cleartype" content="on">

<?php 
ini_set('date.timezone','Asia/Shanghai');


//error_reporting(E_ERROR);
require_once "lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

$get=$_GET;
if(!$get['cliniccard']){
die;
}

$openId=$_GET['openid'];
$tools = new JsApiPay();
if(!$openId){
//①、获取用户openid

$openId = $tools->GetOpenid();
}

 $price=floatval ($_GET['price']);
 

 $client = new SoapClient("http://192.168.100.212:8080/WeiXinServices/services/Services?wsdl",array('encoding'=>'UTF-8','trace' => true));	
	$client->__setLocation('http://192.168.100.212:8080/WeiXinServices/services/Services.ServicesHttpSoap11Endpoint/"');

	try {
	
		
		$param["request"]='<root>
		<head>
		<actdate>'.date('Ymd').'</actdate>
		<trdate>'.date('Ymd').'</trdate>
		<trtime>'.date('His').'</trtime>
		<trcode>GETZHYE</trcode>
		<hisseq></hisseq>
		<bhpseq></bhpseq>
		<filenum>0</filenum>
		<chncode></chncode>
		<termno>微信</termno>
		<mac></mac>
	</head>
	<body>
		<jdseq></jdseq>
		<brid>'.$get['cliniccard'].'</brid>
	</body>
	</root>';
	
		$return = $client->MoneyAll($param);
		 
		
	 	 $xml = (string)$return->return;
			$xml=simplexml_load_string($xml);
		$result= get_object_vars($xml->body);
		
		}
		catch (SoapFault $f){
	
        echo "Error Message: {$f->getMessage()}";
		}
 	
if($result['brxm']!=$get['realname']){
	echo '<script>alert("该卡为'.$result['brxm'].'所有，如有疑问，请联系工作人员");history.go(-1)</script>';die;
	}
 
//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>


<link rel="stylesheet" href="/tpl/Wap/default/common/guahao/css/normalize.css">
<link rel="stylesheet" href="/tpl/Wap/default/common/guahao/css/style.min.css">
   <style>
	.field_caption, .field_value{
		display: inline-block;
	}
	.field_caption{width: 10%; margin-right: 0; text-align: left;}
	.field_value{width: 90%; text-align: right; color: #333;}
	.typo-blue{color: #2E9AFF;}
	.navbar{ text-align:center; background-color:#6666FF; color:#FFFFFF}
	.list{ margin-top:20px;}
	a.submit{  
    color: #333;
    display: block;
    font-size: 20px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    width: 100%;
	padding:0}
	.list h1{ color: #ff0000;
    font-size: 20px;
    text-align: center;}
	.list p{ color: #ff0000;
    font-size: 18px;
    text-align: center;
	 text-indent:0;}
	.text-input{ height:30px; width:95%;border-radius:10px; font-size:14px;}
	.list_ul{padding-left: 0px; height:100px;}
	.list_ul li{  border: 1px solid #dcdede;
    border-radius: 5px;
    box-shadow: 0 0 3px #000;
    float: left;
    height: 30px;
    line-height: 30px;
    list-style: outside none none;
    margin-right: 4%;
    margin-top: 10px;
    text-align: center;
    width: 20%;}
	.choice{ background-color:#CCFF33 }
	</style>


</head>
<body>
       <div class="gonggao" style="margin-top:40px;background-color: #FFF;    padding: 10px;    font-size: 14px;    line-height: 20px;    margin: 0;    position: relative;margin: 1em 6.25% 0 6.25%;border: 1px solid #dcdede;">
			<div class="hot" style="
    position: absolute;    top: 0;    left: 0;    width: 42px;    height: 42px;    overflow: hidden;    z-index: 2;
">
				<strong style="
    display: inline-block;    width: 60px;    background: #F00;    color: #fff;    font-size: 80%;    text-align: center;    padding: 16px 0 1px 0px;    -webkit-transform: rotate(-45deg) translate(-10px, -25px);    font-weight: normal;    /* font-size: 12px; */;
">公告</strong>
			</div>
			<div class="content" style="
    text-indent: 2em;    color: #333;
"> 
			  <div align="">温馨提示：您可以选择设定的金额进行充值，也可以在输入框中输入任意大于一元的金额，单次充值限额为1000元人民币</div>
			</div>
		</div>
	
	<div class="pbottom fullheight fullwidth">
	<div class="uc-wrap">
	<div class="list"><?php echo $get['realname'];?>，余额：<?php echo $result['zhye'];?><div>
	<div class="list">
 	<input type="text" name="number" class="text-input" id="number"  value="<?php echo $price?$price:'';?>" placeholder="请输入充值金额（单次最高不能超过1000）">
	</div>
	
		<ul class="list_ul">
			<li >20</li>
			<li>50</li>
			<li>100</li>
			<li>200</li>
			<li>300</li>
			<li>400</li>
			<li>500</li>
			<li>1000</li>
		</ul>
	<div class="list">
 	<a class="submit add-fam" onClick="pay()">充值</a>
	</div>
	
	</div>	
	</div>
	<script type="text/javascript" src="/tpl/Wap/default/common/guahao/js/jsfjquery_002.js"></script> 
	<script>
	var openid='<?php echo $openId;?>';
	var realname='<?php echo $get['realname'];?>';
	var cliniccard='<?php echo $get['cliniccard'];?>';
	$('.list_ul').find('li').bind('click',function(){
	$('.list_ul').find('li').removeClass('choice');
	 if ( !$(this).hasClass("choice") )
		$(this).addClass('choice')
	$('#number').val($(this).text())
  })
	
	function pay(){
		var price=0;
		
		if($('#number').val()!='')
		 price=parseFloat($('#number').val());
	//if(price>1000 || price <1){
//	tishi('充值金额不得于1或大于1000元');
//	return false;
//	}
	
		window.location.href="http://fuyou.linepoinfo.com/wxpay/jsapi.php?openid="+openid+"&price="+price+"&realname="+realname+"&cliniccard="+cliniccard;
		}
	   function tishi(string){
	    art.dialog.through({
		title: '提示',
		content: string,
		lock: true,
		button:[{name:'确定'}]
	});
	   }   
	</script>
<?php 


 if($price){
$price=$price*100;
//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("就诊卡充值(".$get['realname'].")");
$input->SetAttach($get['cliniccard']);
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee($price);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://fuyou.linepoinfo.com/wxpay/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);

$jsApiParameters = $tools->GetJsApiParameters($order);

  ?>
    <script type="text/javascript">
	//调用微信JS api 支付

	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				  if(res.err_msg == "get_brand_wcpay_request:ok"){
                   //alert(res.err_code+res.err_desc+res.err_msg);
                   	window.location.href="http://fuyou.linepoinfo.com/wxpay/jsapi.php?openid="+openid+"&realname="+realname+"&cliniccard="+cliniccard;
                   }
				
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	
	callpay();
	
	</script>
<?php }  ?>

<script src="/tpl/static/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/tpl/static/artDialog/plugins/iframeTools.js"></script>
</body>
</html>