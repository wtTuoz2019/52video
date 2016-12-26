<?php
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
class registerMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
        $this->base_purview();
    }

    //判断基本权限
    public function base_purview() {
        if(!intval($this->user_config['reg_status'])){
       	$this->error(html_out($this->user_config['reg_off_reason']));
        }

	}

    //协议
	public function agreement() {
		$this->show();
	}

	//注册首页
	public function index() {
		$this->field_list=model('user_model')->field_list('reg=1');
		//hook
        module('common')->plus_hook('register','index');
        //hook end
		$this->show();
	}

	public function data() {
		//数据过滤
        $username=in($_POST['username']);
		$nicename=in($_POST['nicename']);
		//基本数据验证
		if(!$_POST['agreement']){
			$this->msg('您未同意注册条款，请先仔细阅读后再同意注册！',0);
		}
		//验证码
		if(!model('verification')->verify_image_data('reg',$_POST['checkcode'])){
			$this->msg('验证码输入错误请重新输入！',0);
		}
		//表单验证
		model('register')->verify_str('username',$username);
		model('register')->verify_str('nicename',$nicename);
		model('register')->verify_str('password',$_POST['password']);
		if($_POST['password']<>$_POST['password2']){
            $this->msg('两次密码输入不同！',0);
        }
		model('register')->verify_str('email',$_POST['email']);
		//附加字段过滤
		model('user_model')->user_check($_POST);
		//过滤用户
		$filter_array=explode(',', $this->user_config['reg_filter']);
		if(!empty($filter_array)){
			foreach ($filter_array as $value) {
				if(strstr($username,$value)){
					$this->msg('非常抱歉，该用户名含有限制字符，请更换后注册！',0);
				}
				if(strstr($nicename,$value)){
					$this->msg('非常抱歉，该昵称含有限制字符，请更换后注册！',0);
				}
			}
		}
		//判断重复用户
		if(model('user')->repeat($username)){
			$this->msg('该用户名已被注册，请更换用户名后重新注册！',0);
		}

		//hook
        module('common')->plus_hook('register','data_start');
        //hook end

		$_POST['verify_type']=intval($this->user_config['reg_audit']);
		$_POST['status']=0;
		//用户数据录入
		$uid=model('register')->add($_POST);
		
	
		
		//验证处理
		model('register')->reg_verify($uid);
		switch ($this->user_config['reg_audit']) {
			case 0:
				$url='success';
				break;
			case 1:
				$url='email_audit';
				break;
			case 2:
				$url='artificial_audit';
				break;
		}

		//hook
        module('common')->plus_hook('register','data_stop',$uid);
        //hook end

		$url='http://'.$_SERVER["HTTP_HOST"].__APP__.'/register/'.$url.'.html?code='.model('system')->str_encode(intval($uid));

		$this->msg($url,1);
	}

	public function email_audit() {
		$uid=model('system')->str_decode($_GET['code']);
		if(empty($uid)){
			$this->error('您访问的链接已失效！');
		}
		$this->info=model('user')->info($uid);

		if(!$this->info){
			$this->error('您访问的链接已失效！');
		}

		if($this->info['status']){
			$this->redirect(__URL__.'/success.html?code='.model('system')->str_encode(intval($uid)));
		}else{
			if($this->info['verify_type']==2){
				$this->redirect(__URL__.'/artificial_audit.html?code='.model('system')->str_encode(intval($uid)));
			}
		}

		//hook
        module('common')->plus_hook('register','email_audit',$uid);
        //hook end
		
		$this->show();
	}

	//重新发送邮件
	public function post_email() {
		$uid=model('system')->str_decode($_GET['code']);
		if(empty($uid)){
			$this->msg('参数获取失败，请尝试刷新页面后重新操作！',0);
		}
		$info=model('user')->info($uid);
		if(!$info){
			$this->msg('获取用户信息失败，请尝试刷新页面后重新操作！',0);
		}

		if($this->info['status']){
			$this->msg('您已经通过了审核，请直接返回会员中心登录！',0);
		}else{
			if($this->user_config['reg_audit']==2){
				$this->msg('您的验证方式为后台审核验证，无法进行邮箱验证！',0);
			}
		}
		
		$verify_info=model('verification')->get_verify('reg',$info['uid']);
		if(!$verify_info){
			$this->msg('验证数据获取失败，请尝试刷新页面后重新操作！',0);
		}
		$starttime=intval($verify_info['starttime'])+180;
		if($starttime>time()){
			$this->msg('您已经发送过验证邮件，3分钟之内不能重复发送！',0);
		}

		//hook
        module('common')->plus_hook('register','post_email',$uid);
        //hook end
		
		model('register')->email($info);
		$this->msg('您的验证邮件已重新下达，请进入邮箱进行查收！',1);

	}

	public function artificial_audit() {
		$uid=model('system')->str_decode($_GET['code']);
		if(empty($uid)){
			$this->error('您访问的链接已失效！');
		}
		$this->info=model('user')->info($uid);

		if(!$this->info){
			$this->error('您访问的链接已失效！');
		}

		if($this->info['status']){
			$this->redirect(__URL__.'/success.html?code='.model('system')->str_encode(intval($uid)));
		}else{
			if($this->info['verify_type']==1){
				$this->redirect(__URL__.'/email_audit.html?code='.model('system')->str_encode(intval($uid)));
			}
		}

		//hook
        module('common')->plus_hook('register','email_audit',$uid);
        //hook end
		
		$this->show();

	}

	public function success() {
		$uid=model('system')->str_decode($_GET['code']);
		if(empty($uid)){
			$this->error('您访问的链接已失效！');
		}
		$this->info=model('user')->info($uid);
		if(!$this->info){
			$this->error('您访问的链接已失效！');
		}

		if(!intval($this->info['status'])){
			switch ($this->info['verify_type']) {
				case 1:
					$this->redirect(__URL__.'/email_audit.html?code='.model('system')->str_encode(intval($uid)));
					break;
				case 2:
					$this->redirect(__URL__.'/artificial_audit.html?code='.model('system')->str_encode(intval($uid)));
					break;
			}
		}

		//hook
        module('common')->plus_hook('register','email_audit',$uid);
        //hook end

		$this->show();
	}

	//验证
	public function verify(){
		$uid=intval($_GET['uid']);
		$code=in($_GET['code']);
		if(empty($uid)||empty($code)){
			$this->error('您访问的链接已失效！');
		}
		$info=model('user')->info($uid);
		if($info['status']){
			$this->error('您已经通过审核，无需再次激活！');
		}
		if($info['verify_type']<>1){
			$this->error('您的注册验证方式不匹配！');
		}
		$verify_info=model('verification')->get_verify('reg',$uid);
		if($verify_info['code']<>$code){
			$this->error('您的验证链接已失效，请登录后重新获取！');
		}

		//hook
        module('common')->plus_hook('register','verify',$uid);
        //hook end
        
		//修改激活状态
		model('register')->audit($uid);
        $this->redirect(__URL__.'/success.html?code='.model('system')->str_encode($uid));
	}


}