<?php
class forgetMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	//找回密码
	public function index() {
		$this->show();
	}

	public function data() {
		//验证码
		if(!model('verification')->verify_image_data('forget',$_POST['checkcode'])){
			$this->msg('验证码输入错误请重新输入！',0);
		}
		//表单验证
		model('register')->verify_str('username',$_POST['username']);
		model('register')->verify_str('email',$_POST['email']);
		
		$username=in($_POST['username']);
		$info=model('user')->repeat($username);

		//判断重复用户
		if(empty($info)){
			$this->msg('用户名或邮箱地址输入不正确！',0);
		}

		if($info['email']<>$_POST['email']){
			$this->msg('用户名或邮箱地址输入不正确！',0);
		}

		//获取验证码
		$verify_info=model('verification')->get_verify('forget',$info['uid']);
		if(!empty($verify_info)){
			$starttime=intval($verify_info['starttime']);
			if($starttime>time()){
				$this->msg('您已经发送过找回邮件，3分钟之内不能重复发送！',0);
			}
		}

		
		//发送找回密码邮件
		model('forget')->email($info);

		$url='http://'.$_SERVER["HTTP_HOST"].__APP__.'/forget/audit.html?code='.model('system')->str_encode(intval($info['uid']));
		$this->msg($url,1);
	}



    //链接过期处理
    public function verify_time($uid,$code=null,$ajax=false){
    	$verify_info=model('verification')->get_verify('forget',$uid);

		if(empty($verify_info)){
			if ($ajax) {
				$this->msg('您访问的链接已失效！',0);
			}else{
				$this->error('您访问的链接已失效！');
			}
		}
		if(isset($code)){
			if($verify_info['code']<>$code){
				if ($ajax) {
					$this->msg('您访问的链接已失效！',0);
				}else{
					$this->error('您访问的链接已失效！');
				}
			}
		}
		if(!model('verification')->verify_time($verify_info['vid'])){
        	model('verification')->del_verify('forget',$uid);
        	if ($ajax) {
				$this->msg('您访问的链接已失效！',0);
			}else{
				$this->error('您访问的链接已过期，请重新进行找回操作！');
			}
			
		}
        
    }

	public function audit() {
		$uid=model('system')->str_decode($_GET['code']);
		if(empty($uid)){
			$this->error('您访问的链接不存在！');
		}
		$this->info=model('user')->info($uid);
		if(!$this->info){
			$this->error('您访问的链接不存在！');
		}

		//时间验证处理
		$this->verify_time($uid);
		
		$this->show();
	}

	//验证
	public function verify(){
		$uid=intval($_GET['uid']);
		$code=in($_GET['code']);
		if(empty($uid)||empty($code)){
			$this->error('您访问的链接不存在！');
		}

		$this->info=model('user')->info($uid);
		if(!$this->info){
			$this->error('您访问的链接不存在！');
		}

		//时间验证处理
		$this->verify_time($uid,$code);
		$this->code=$code;
        $this->show();
	}

	//修改
	public function edit_data(){
		$uid=intval($_POST['uid']);
		$code=in($_POST['code']);
		if(empty($uid)||empty($code)){
			$this->msg('参数传递有误，请勿非法提交！');
		}

		//验证
		if(!model('verification')->verify_image_data('forget',$_POST['checkcode'])){
			$this->msg('验证码输入错误请重新输入！',0);
		}

		model('register')->verify_str('password',$_POST['password']);
		if($_POST['password']<>$_POST['password2']){
            $this->msg('两次密码输入不同！',0);
        }

		$info=model('user')->info($uid);
		if(!$info){
			$this->msg('未获取到用户信息！');
		}

		//时间验证处理
		$this->verify_time($uid,$code,true);

        //修改信息
        $data=array();
        $data['uid']=$uid;
        $data['password']=md5($_POST['password'].$info['salt']);
		model('forget')->edit($data);

		//删除验证状态
        model('verification')->del_verify('forget',$uid);

		$url='http://'.$_SERVER["HTTP_HOST"].__APP__.'/forget/success.html?code='.model('system')->str_encode(intval($uid));
		$this->msg($url,1);
	}

	public function success() {
		$uid=model('system')->str_decode($_GET['code']);
		if(empty($uid)){
			$this->error('您访问的链接不存在！');
		}

		$this->info=model('user')->info($uid);
		if(!$this->info){
			$this->error('您访问的链接不存在！');
		}
        $this->show();
	}


}