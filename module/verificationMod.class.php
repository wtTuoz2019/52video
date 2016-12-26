<?php
class verificationMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

    //验证码
    public function verify_img(){
        Image::buildImageVerify();
    }

    public function mail(){
    	@ignore_user_abort(true); 
    	@set_time_limit(0);
        if($_POST['key']<>$this->config['KEY']){
            $this->error404();
        }
    	$email=$_POST['email'];
    	$title=$_POST['title'];
    	$content=$_POST['content'];
        model('system')->email($email,$title,$content);
    }




}