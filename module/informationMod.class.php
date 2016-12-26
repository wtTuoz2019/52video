<?php
class informationMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

    public function base() {
    	$this->field_list=model('user_model')->field_list();
    	//hook
        module('common')->plus_hook('information','base');
        //hook end
		$this->show();
	}

	public function base_data() {
		$_POST=in($_POST);
		//数据验证
		model('register')->verify_str('nicename',$_POST['nicename']);
		model('register')->verify_str('email',$_POST['email']);
		model('user_model')->user_check($_POST);
		//用户数据保存
		$_POST['uid']=$this->user_info['uid'];
		$_POST['gid']=$this->user_info['gid'];
		//hook
        module('common')->plus_hook('information','base_data');
        //hook end
		model('information')->base_save($_POST);
		$this->msg('您的个人资料保存成功！');
		
	}

	public function password() {
		//hook
        module('common')->plus_hook('information','password');
        //hook end
		$this->show();
	}

	public function password_data() {
		
		if(md5($_POST['old_password'].$this->user_info['salt'])<>$this->user_info['password']){
			$this->msg('原始密码输入错误！',0);
		}
		model('register')->verify_str('password',$_POST['password']);
		if($_POST['password']<>$_POST['password2']){
            $this->msg('两次密码输入不同！',0);
        }
        $_POST['uid']=$this->user_info['uid'];
		$_POST['gid']=$this->user_info['gid'];
		$_POST['password']=md5($_POST['password'].$this->user_info['salt']);
		//hook
        module('common')->plus_hook('information','password_data');
        //hook end
        model('information')->base_save($_POST);
		$this->msg('您的密码修改成功！');
	}

	public function avatar() {
		//hook
        module('common')->plus_hook('information','avatar');
        //hook end
		$this->show();
	}

	public function avatar_data() {

		$dir=__USERDIR__.'/avatar/'.$this->user_info['uid'].'/';
		$url=__ROOT__.'/avatar/'.$this->user_info['uid'].'/';
		@mkdir($dir, 0777,true);
		switch($_GET['action']){
			case 'uploadtmp':
				$file=$dir.'original.jpg';
				@move_uploaded_file($_FILES['Filedata']['tmp_name'], $file);
				$status = 1;
			break;
			case 'uploadavatar':
				$input = file_get_contents('php://input');
				$data = explode('--------------------', $input);
				@file_put_contents($dir.'original.jpg', $data[1]);
				@file_put_contents($dir.'large.jpg', $data[0]);
				@Image::thumb($dir.'large.jpg', $dir.'moderate.jpg', '', 64, 64, true,false);
				@Image::thumb($dir.'large.jpg', $dir.'small.jpg', '', 32, 32, true,false);
				$status = 1;
			break;
		}
		//hook
		module('common')->plus_hook('information','avatar_data');
		//hook end
		$this->msg('头像保存成功！',$status);
	}

	public function logout() {
		//hook
		module('common')->plus_hook('information','logout');
		//hook end
		model('login')->logout();
		$this->redirect(__APP__.'/');
	}


}