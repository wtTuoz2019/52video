<?php

/**
 * 微信公众平台操作类
 */
class Wechat {

	private $_appid;
	private $_appsecret;

	//表示QRCode的类型
	const QRCODE_TYPE_TEMP = 1;
	const QRCODE_TYPE_LIMIT = 2;
	const QRCODE_TYPE_LIMIT_STR = 3;
	public function __construct($id, $secret) {
		$this->_appid = $id;
		$this->_appsecret = $secret;
	}

	/**
	 * 获取 access_tonken
	 * @param string $token_file 用来存储token的临时文件
	 */
	public function getAccessToken($token_file='./access_token') {
		// 考虑过期问题，将获取的access_token存储到某个文件中
		$life_time = 7200;
		if (file_exists($token_file) && time()-filemtime($token_file)<$life_time) {
			// 存在有效的access_token
			return file_get_contents($token_file);
		}
		// 目标URL：
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->_appid}&secret={$this->_appsecret}";
		//向该URL，发送GET请求
		$result = $this->_requestGet($url);
		if (!$result) {
			return false;
		}
		// 存在返回响应结果
		$result_obj = json_decode($result);
		// 写入
		file_put_contents($token_file, $result_obj->access_token);
		return $result_obj->access_token;
	}

	/**
	 * [getQRCodeTicket description]
	 * @param $content 内容
	 * @param $type qr码类型
	 * @param $expire 有效期，如果是临时的类型则需要该参数
	 * @return string ticket
	 */
	private function _getQRCodeTicket($content, $type=2, $expire=604800) {
		$access_token = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
		$type_list = array(
			self::QRCODE_TYPE_TEMP => 'QR_SCENE',
			self::QRCODE_TYPE_LIMIT => 'QR_LIMIT_SCENE',
			self::QRCODE_TYPE_LIMIT_STR => 'QR_LIMIT_STR_SCENE',
			);
		$action_name = $type_list[$type];
		switch ($type) {
			case self::QRCODE_TYPE_TEMP:
			// {"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
				$data_arr['expire_seconds'] = $expire;
				$data_arr['action_name'] = $action_name;
				$data_arr['action_info']['scene']['scene_id'] = $content;
				break;
			case self::QRCODE_TYPE_LIMIT:
			case self::QRCODE_TYPE_LIMIT_STR:
				$data_arr['action_name'] = $action_name;
				$data_arr['action_info']['scene']['scene_id'] = $content;
				break;
		}
		$data = json_encode($data_arr);
		$result = $this->_requestPost($url, $data);
		if (!$result) {
			return false;
		}
		//处理响应数据
		$result_obj = json_decode($result);
		return $result_obj->ticket;
	}
	/**
	 * [getQRCode description]
	 * @param  int|string  $content qrcode内容标识
	 * @param  [type]  $file    存储为文件的地址，如果为NULL表示直接输出
	 * @param  integer $type    类型
	 * @param  integer $expire  如果是临时，表示其有效期
	 * @return [type]           [description]
	 */
	public function getQRCode($content, $file=NULL, $type=2, $expire=604800) {
		// 获取ticket
		$ticket = $this->_getQRCodeTicket($content, $type=2, $expire=604800);
		$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
		$result = $this->_requestGet($url);//此时result就是图像内容
		if ($file) {
			file_put_contents($file, $result);
		} else {
			header('Content-Type: image/jpeg');
			echo $result;
		}
	}
	private function _requestPost($url, $data, $ssl=true) {
		// curl完成
		$curl = curl_init();

		//设置curl选项
		curl_setopt($curl, CURLOPT_URL, $url);//URL
		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '
Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
		curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);//user_agent，请求代理信息
		curl_setopt($curl, CURLOPT_AUTOREFERER, true);//referer头，请求来源
		//SSL相关
		if ($ssl) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//禁用后cURL将终止从服务端进行验证
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);//检查服务器SSL证书中是否存在一个公用名(common name)。
		}
		// 处理post相关选项
		curl_setopt($curl, CURLOPT_POST, true);// 是否为POST请求
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);// 处理请求数据
		// 处理响应结果
		curl_setopt($curl, CURLOPT_HEADER, false);//是否处理响应头
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//curl_exec()是否返回响应结果

		// 发出请求
		$response = curl_exec($curl);
		if (false === $response) {


			echo '<br>', curl_error($curl), '<br>';
			return false;
		}
		return $response;
	}

	/**
	 * 发送GET请求的方法
	 * @param string $url URL
	 * @param bool $ssl 是否为https协议
	 * @return string 响应主体Content
	 */
	private function _requestGet($url, $ssl=true) {
		// curl完成
		$curl = curl_init();

		//设置curl选项
		curl_setopt($curl, CURLOPT_URL, $url);//URL

		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '
Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
		curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);//user_agent，请求代理信息
		curl_setopt($curl, CURLOPT_AUTOREFERER, true);//referer头，请求来源
		//SSL相关
		if ($ssl) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//禁用后cURL将终止从服务端进行验证
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);//检查服务器SSL证书中是否存在一个公用名(common name)。
		}
		curl_setopt($curl, CURLOPT_HEADER, false);//是否处理响应头
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//curl_exec()是否返回响应结果

		// 发出请求
		$response = curl_exec($curl);
		if (false === $response) {
			echo '<br>', curl_error($curl), '<br>';
			return false;
		}
		return $response;
	}

	/**
	 * [getQRCode description]
	 * @param  int|string  $content qrcode内容标识
	 * @param  [type]  $file    存储为文件的地址，如果为NULL表示直接输出
	 * @param  integer $type    类型
	 * @param  integer $expire  如果是临时，表示其有效期
	 * @return [type]           [description]
	 */
	public function getTicket() {
		$access_token = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
		$result = $this->_requestGet($url);
		$result_obj = json_decode($result);
		return $result_obj->ticket;
	}
	
	public function getCode($redirect_uri) {
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
		$result = $this->_requestGet($url);
		//$result_obj = json_decode($result);
		return $result;
	}
	
	public function getAppid($code) {
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}&secret={$this->_appsecret}&code=$code&grant_type=authorization_code";
		$result = $this->_requestGet($url);
		$result_obj = json_decode($result);
		return $result_obj->openid;
	}

}