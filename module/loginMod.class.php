<?php
class loginMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        //hook
        module('common')->plus_hook('index','index');
        //hook end
		$this->display('login_index.html');
	}
	
	public function login() {
		$code=$_COOKIE[$this->config['SPOT'].'_logincode'];
		if(!$code){
		$code=md5(get_client_ip().time());
         $cookie=$code;
        setcookie($this->config['SPOT'].'_logincode',$cookie);
		}
		$this->code=$code;
		$url = "http://".$this->config['MOBILE_DOMAIN']."/index.php/login/scope/logincode-".$code.".html";
		
		$this->tourl = 'http://'.$_SERVER["HTTP_HOST"].$_GET['url'];
		model('login')->gsetloginQrcode($url, $code);
        //hook end
		$this->display('login_login.html');
	}
	public function scope(){
		
		$this->getuserinfo();
<<<<<<< HEAD
			
=======
		$userinfo=model('user')->info($_SESSION['uid']);
>>>>>>> 826ee61d07648e9d38df7973b36faa01450d16a0
		$res['logincode']=$_GET['logincode'];
		$res['logintime']=time();
		$res['openid']=$userinfo['openid'];
	 
		model('comment')->wechat_add($res);	
		$this->display('login_sucess.html');
		}
	public function logincode(){
		$code=$_POST['code'];
		$uid=model('user')->getuidbylogincode($code);
		if($uid){
			$_SESSION['uid']=$uid;
		$this->msg($uid,1);	
			}else{
			$this->msg($uid,0);		
				}
		}
	public function data() {
		$_POST=in($_POST);
		if(empty($_POST['username'])||empty($_POST['password'])){
            $this->msg('请填写完整登录信息!',0);
        }

        //验证码
		if(!model('verification')->verify_image_data('login',$_POST['checkcode'])){
			$this->msg('验证码输入错误请重新输入！',0);
		}

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

        $url=__APP__.'/';
        $this->msg($url,1);
	}



}