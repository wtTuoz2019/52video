<?php
class weixinloginMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		
    }

	
		function login()
		{
	
		$access_token=json_decode(file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->config['loginappid'].'&secret='.$this->config['loginappsecret'].'&code='.$_GET['code'].'&grant_type=authorization_code'), true);
		
		if($access_token['openid']&&$access_token['access_token']){
			
			$openid=$access_token["openid"];
			 $uid = model('comment')->getuid($openid);
			
			 
			  $info=json_decode(file_get_contents('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token['access_token'].'&openid='.$access_token['openid']), true);
			 
			 
			 $res['openid']=$info['openid'];
		$res['nicename']=$info['nickname'];
		$res['headimgurl']=$info['headimgurl'];
		$res['sex']=$info['sex'];
		$res['city']=$info['city'];
		$res['country']=$info['country'];
		$res['province']=$info['province'];
		
		$res['unionid']=$info['unionid'];
	
		$res['from']='pc';
		
		if($uid){
		model('comment')->wechat_add($res);	
			}else{
		$uid=model('comment')->wechat_add($res);
		
		}
		
		
		$_SESSION['uid'] = $uid; 
			$_SESSION['headpic']=model('comment')->get_pic($_SESSION['uid']);
	$_SESSION['nickname']=model('comment')->getname($_SESSION['uid']);
			};
	
	$url = 'http://'.$_GET['url'];
       $this->redirect($url);die;
      
}

	function get_openid()
	{
    $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" 
        . $_SESSION['access_token'];

    $str  = file_get_contents($graph_url);
    if (strpos($str, "callback") !== false)
    {
        $lpos = strpos($str, "(");
        $rpos = strrpos($str, ")");
        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
    }

    $user = json_decode($str);
    if (isset($user->error))
    {
        echo "<h3>error:</h3>" . $user->error;
        echo "<h3>msg  :</h3>" . $user->error_description;
        exit;
    }

    //debug
    //echo("Hello " . $user->openid);

    //set openid to session
    $_SESSION["openid"] = $user->openid;
	$info=model('user')->qq($_SESSION["openid"]);
	if($info){
		
		 $data=array();
       
		$data['uid']=$info['uid'];
        $data['last_time']=time();
        $data['last_ip']=get_client_ip();
		$data[$_POST['logintype']]=$_POST[$_POST['logintype']];
		
		
		
        model('user')->edit($data);

        //设置登录信息
        $cookie=$info['uid'].'|'.sha1($info['username']).'|'.sha1($info['password']);
        if($_POST['remember']){
        	$expire = time() + 604800;
        }else{
        	$expire = time() + 7200;
        }
        setcookie($this->config['SPOT'].'_wxuser',$cookie,$expire,'/');

        //hook
        module('common')->plus_hook('index','data_stop',$data);
        //hook end

        $url=__APP__.'/index/functions';
		
		 $this->redirect( $url);
		
		}
	
	
}
	public function logout(){
		
		session_unset();
		session_destroy();
		model('login')->logout();
		 $url=__APP__;
		$this->alert('退出成功');
		 $this->redirect( $url);
		}
}