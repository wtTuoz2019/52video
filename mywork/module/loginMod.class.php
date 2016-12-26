<?php
class loginMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		
		if($this->isMobile()){
			 if (!isset($_GET['code']) ) {
      
           $scope = 'snsapi_userinfo';
           $oauthUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->config['appid'] . '&redirect_uri=' . urlencode($customeUrl) . '&response_type=code&scope=' . $scope . '&state=oauth#wechat_redirect';
           header('Location:' . $oauthUrl);exit();
        }
        if (isset($_GET['code']) && isset($_GET['state']) && isset($_GET['state']) == 'oauth') 											      {
            $rt = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->config['appid'] . '&secret=' . $this->config['appsecret'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code');
			$jsonrt = json_decode($rt, 1);
          
			
				
           if($jsonrt['errcode'])
		{	$url=parse_url($_SERVER['REQUEST_URI']);
		$url='http://'.$this->config['MOBILE_DOMAIN'] . $url['path'].'?token='.$_GET['token'];
			if($_GET['aid']){
			$url.='&aid='.$_GET['aid'];
			}
			$this->redirect($url);die;
			}
		  $openid = $jsonrt['openid'];
			 $access_token = $jsonrt['access_token'];
			 $uid = model('comment')->getuid($openid);
			 
			
		if(!$uid){
				 
		$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		//转成对象
		$user_info = file_get_contents($user_info_url);
		if (isset($user_info->errcode)) {
			$this->msg($user_info->errmsg, 0);
		}
		$data = json_decode($user_info, true);
		
		$res['openid']=$data['openid'];
		$res['nicename']=$data['nickname'];
		$res['headimgurl']=$data['headimgurl'];
		$res['sex']=$data['sex'];
		$res['city']=$data['city'];
		$res['country']=$data['country'];
		$res['province']=$data['province'];
		$res['subscribe_time']=$data['subscribe_time'];
		$res['unionid']=$data['unionid'];
		$res['groupid']=$data['groupid'];
		$res['type']=$data['wechat'];
		$uid=model('comment')->wechat_add($res);
		
		
		}
		//设置登录信息
        $cookie=$uid.'|'.sha1(model('comment')->getname($uid));
        if($_POST['remember']){
        	$expire = time() + 604800;
        }else{
        	$expire = time() + 7200;
        }
        setcookie($this->config['SPOT'].'_duxuser',$cookie,$expire,'/');

		
	
	
       $this->redirect(__APP__);die;
         
       	 } 
	 } 
		
	

		
	
			
			
		
		$this->redirect('https://open.weixin.qq.com/connect/qrconnect?appid='.$this->config['loginappid'].'&redirect_uri='.urlencode('http://live.shanyueyun.com/mywork/index.php/weixinlogin/login').'&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect');
		
        //hook
        module('common')->plus_hook('index','index');
        $this->url = $_GET['url'];
        //hook end
		$this->display();
	}

	public function data() {
		$_POST=in($_POST);
		if(empty($_POST['username'])||empty($_POST['password'])){
            $this->msg('请填写完整登录信息!',0);
        }

        //验证码
		// if(!model('verification')->verify_image_data('login',$_POST['checkcode'])){
		// 	$this->msg('验证码输入错误请重新输入！',0);
		// }

        //获取帐号信息
        $info=model('user')->repeat($_POST['username']);
        //进行帐号验证
        if(empty($info)){
            $this->msg('登录失败! 您输入的帐号不存在!',0);
        }
        if($info['password']<>md5($_POST['password'].$info['salt'])){
            $this->msg('登录失败! 您输入的帐号或密码错误!',0);
        }
        if($info['gid']==2){
            $this->msg('登录失败! 您已被禁止登录!',0);
        }
        if(!$info['status']){
        	switch ($info['verify_type']) {
        		case 1:
        			$url=__APP__.'/register/email_audit.html?code='.model('system')->str_encode(intval($info['uid']));
        			break;
        		case 2:
        			$url=__APP__.'/register/artificial_audit.html?code='.model('system')->str_encode(intval($info['uid']));
        			break;
        	}
            $this->msg($url,1);
           
        }

        //hook
        module('common')->plus_hook('index','data_strat',$info);
        //hook end

        //更新帐号信息
        $data=array();
        $data['uid']=$info['uid'];
        $data['last_time']=time();
        $data['last_ip']=get_client_ip();
        model('user')->edit($data);

        //设置登录信息
        $cookie=$info['uid'].'|'.sha1($info['username']).'|'.sha1($info['password']);
        if($_POST['remember']){
        	$expire = time() + 604800;
        }else{
        	$expire = time() + 7200;
        }
        setcookie($this->config['SPOT'].'_duxuser',$cookie,$expire,'/');

        //hook
        module('common')->plus_hook('index','data_stop',$data);
        //hook end




        // $url = isset($_GET['url']) ? 'http://'.$_GET['url'] : __APP__.'/';    
        if(!$_GET['url']){
            $url = __APP__.'/';
        }else{
            $url = 'http://'.$_GET['url'];
        }
        $this->msg($url,1);
	}
	public function isMobile()
{
// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
{
return true;
}
// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
if (isset ($_SERVER['HTTP_VIA']))
{
// 找不到为flase,否则为true
return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
}
 
if (isset ($_SERVER['HTTP_USER_AGENT']))
{
$clientkeywords = array ('nokia',
'sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips',
'panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi',
'openwave','nexusone','cldc','midp','wap','mobile'
);
// 从HTTP_USER_AGENT中查找手机浏览器的关键字
if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
{
return true;
}
}
// 协议法，因为有可能不准确，放到最后判断
if (isset ($_SERVER['HTTP_ACCEPT']))
{
// 如果只支持wml并且不支持html那一定是移动设备
// 如果支持wml和html但是wml在html之前则是移动设备
if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
{
return true;
}
}
return false;
}
	


}