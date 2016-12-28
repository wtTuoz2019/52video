<?php
require_once CP_CORE_PATH . "/../ext/wxpay/log.php";
require_once CP_CORE_PATH . "/../lib/wxjm/wxBizMsgCrypt.php";
//评论管理
class wechatapiMod  extends commonMod {
	
	public $token;
	public $data;
	
	public function index(){
		
		if(isset($_GET['echostr'])){
			echo $_GET['echostr'];exit;}
			
			
		$token =htmlspecialchars($_GET['token']);
		$this->token=$token;
        if(!preg_match("/^[0-9a-zA-Z]{3,42}$/", $token)){
            exit('error token');
        }
		
		$data = file_get_contents("php://input");
		$xml=$data;
		$xml = new SimpleXMLElement($xml);
			$xml || exit;
			
	        foreach ($xml as $key => $value) {
	        	$this->data[$key] = strval($value);
	        }
			
		
			
			
		//$data=$WX->request();
		//$this->response($this->data['Event'], 'text');
		
		if($this->data['Event']=='subscribe'){
			
			
			
			$info=$this->model->table('areply')->where(array('token'=>$token))->find();
			if($info){
				if($info['home']==1){
					$pro=$this->model->table('content')->where(array('aid'=>$info['contentid']))->find();
					$url=$info['url'];
					$res=array(
							array(
								array(
									$pro['title'],
									strip_tags(htmlspecialchars_decode($pro['description'])) ,
									'http://'.$_SERVER['HTTP_HOST'].$pro['image'],
									$url
								)
							) ,
							'news'
						);
					
					}else{
						$res=array($info['content'],'text');
					}
				list($content, $type) = $res;
				$this->response($content, $type);
			}
			
			}
		
				
		//请求其他微信平台
		$wxuser=$this->model->table('wxuser')->where(array('token'=>$token))->find();
			
		$url=$wxuser['api'];
		
		echo $this->vpost($url,$data);
			
		}
		
	public function wechat_updata(){
		
		if(IS_GET){
			
			
			$access=$this->_getAccessToken();
			$count=$this->model->table('user')->count();
			$i=isset($_GET['i'])?intval($_GET['i']):0;
			
			
			$sql="SELECT * FROM  `dc_user` LIMIT ".$i." , 30";
			
			$openid_all=$this->model->query($sql);
			
			if($openid_all){
				foreach($openid_all as $k=>$v){
					$url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access.'&openid='.$v['openid'].'&lang=zh_CN';
					
					$json=$this->curlGet($url);
					
					$data=json_decode($json,true) ;
					if(!isset($data['errcode'])){
						$this->model->table('user')->data($data)->where(array('openid'=>$v['openid']))->update();
						}
					}
					$i+=30;
					echo 2;
					
					echo '<script type="text/javascript">';

echo 'window.location.href ="http://wap.urren.net/home/index.php/wechatapi/wechat_updata/i-'.$i.'"';

echo "</script>";  


					
					//header("Location: http://test.52video.net/home/index.php/wechatapi/wechat_updata/i-".$i); 
					exit;
					
			}else{
				exit;
				}
			
			
		}
		
		}	
	public function _getAccessToken(){
		
		$access=$_SESSION['access'];
		if(empty($access)||!($access['time']>time())){
			
			$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx02643b27f73cf5c7&secret=4c867cd778b5c38f2aaf0a230785fd94';
			$json=json_decode($this->curlGet($url_get));
			if (!$json->errmsg){
			}else {
				$error='获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg;
				return $error;
				//$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg);
			}
			$access=array('access_token'=>$json->access_token,'time'=>time()+600);
		
			$_SESSION['access']=$access;
			}
		
		return $access['access_token'];
		
		 
	}
	public function authorize(){
		
	
	
		$logHandler= new CLogFileHandler(__ROOTDIR__ . "/data/wechatapilog/".date('Y-m-d').'(authorize).log');
	$log = Log::Init($logHandler, 15);
	
	 Log::DEBUG("jieshou:\n");
$encryptMsg = file_get_contents('php://input');
	$timeStamp  = empty($_GET['timestamp'])     ? ""    : trim($_GET['timestamp']) ;
$nonce      = empty($_GET['nonce'])     ? ""    : trim($_GET['nonce']) ;
$msg_sign   = empty($_GET['msg_signature']) ? ""    : trim($_GET['msg_signature']) ;

$pc = new WXBizMsgCrypt($this->config['kftoken'], $this->config['kfkey'], $this->config['kfappid']);
$xml_tree = new DOMDocument();
$xml_tree->loadXML($encryptMsg);
$array_e = $xml_tree->getElementsByTagName('Encrypt');

$encrypt = $array_e->item(0)->nodeValue;
$format = "<xml><Encrypt><![CDATA[%s]]></Encrypt></xml>";
 $from_xml = sprintf($format, $encrypt);
//第三方收到公众号平台发送的消息
$msg = '';
$errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);

if ($errCode == 0) {
   Log::DEBUG("jiemihou: " . $msg . "\n");
  
    $xml = new DOMDocument();
    $xml->loadXML($msg);
    $array_e = $xml->getElementsByTagName('ComponentVerifyTicket');
    $component_verify_ticket = $array_e->item(0)->nodeValue;
    Log::DEBUG('component_verify_ticket is：'.$component_verify_ticket);
   
	
	S('component_verify_ticket',$component_verify_ticket); 
    

    
} else {
    Log::DEBUG( 'jiemi shibai：'.$errCode);
}
	
	
	echo 'success';
		}
	public function callback(){ 
	  
		$logHandler= new CLogFileHandler(__ROOTDIR__ . "/data/wechatapilog/".date('Y-m-d').'(callback).log');
	$log = Log::Init($logHandler, 15);
	Log::DEBUG('get:'.json_encode($_GET));
	$data = file_get_contents("php://input");
	Log::DEBUG('post:'.$data);
		}
	
	
		
		
	public function  vpost($url,$data=null){ // 模拟提交数据函数
	
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_URL, $url); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); // 模拟用户使用的浏览器
 
    curl_setopt($curl, CURLOPT_POST, 1); 
    curl_setopt($curl, CURLOPT_POSTFIELDS,$data); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
    curl_setopt($curl, CURLOPT_HEADER, 0); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    $tmpInfo = curl_exec($curl);
	
    if (curl_errno($curl)) {
       echo 'Errno'.curl_error($curl);
    }
    curl_close($curl); 
    return $tmpInfo; 
}	
/**
	 * 获取微信推送的数据
	 * @return array 转换为数组后的数据
	 */
	public function request(){
       	return $this->data;
	}

	/**
	 * * 响应微信发送的信息（自动回复）
	 * @param  string $to      接收用户名
	 * @param  string $from    发送者用户名
	 * @param  array  $content 回复信息，文本信息为string类型
	 * @param  string $type    消息类型
	 * @param  string $flag    是否新标刚接受到的信息
	 * @return string          XML字符串
	 */
	public function response($content, $type = 'text', $flag = 0){
		/* 基础数据 */
		$this->data = array(
			'ToUserName'   => $this->data['FromUserName'],
			'FromUserName' => $this->data['ToUserName'],
			'CreateTime'   => NOW_TIME,
			'MsgType'      => $type,
		);

		/* 添加类型数据 */
		$this->$type($content);

		/* 添加状态 */
		$this->data['FuncFlag'] = $flag;

		/* 转换数据为XML */
		$xml = new SimpleXMLElement('<xml></xml>');
		$this->data2xml($xml, $this->data);
		exit($xml->asXML());
	}

	/**
	 * 回复文本信息
	 * @param  string $content 要回复的信息
	 */
	private function text($content){
		$this->data['Content'] = $content;
	}

	/**
	 * 回复音乐信息
	 * @param  string $content 要回复的音乐
	 */
	private function music($music){
		list(
			$music['Title'], 
			$music['Description'], 
			$music['MusicUrl'], 
			$music['HQMusicUrl']
		) = $music;
		$this->data['Music'] = $music;
	}

	/**
	 * 回复图文信息
	 * @param  string $news 要回复的图文内容
	 */
	private function news($news){
		$articles = array();
		foreach ($news as $key => $value) {
			list(
				$articles[$key]['Title'],
				$articles[$key]['Description'],
				$articles[$key]['PicUrl'],
				$articles[$key]['Url']
			) = $value;
			if($key >= 9) { break; } //最多只允许10调新闻
		}
		$this->data['ArticleCount'] = count($articles);
		$this->data['Articles'] = $articles;
	}
	private function transfer_customer_service($content){
		$this->data['Content'] = '';
	}
	
    private function data2xml($xml, $data, $item = 'item') {
        foreach ($data as $key => $value) {
            /* 指定默认的数字key */
            is_numeric($key) && $key = $item;

            /* 添加子元素 */
            if(is_array($value) || is_object($value)){
                $child = $xml->addChild($key);
                $this->data2xml($child, $value, $item);
            } else {
            	if(is_numeric($value)){
            		$child = $xml->addChild($key, $value);
            	} else {
            		$child = $xml->addChild($key);
	                $node  = dom_import_simplexml($child);
				    $node->appendChild($node->ownerDocument->createCDATASection($value));
            	}
            }
        }
    }

   
	private function auth($token){
		/*
		$signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}*/
		return true;
	}

}
		

	

	
	



