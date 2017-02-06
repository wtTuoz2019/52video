<?php
class loginModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //检测登录状态
    public function check_login($userinfo)
    {
        if(empty($userinfo)){
            return false;
        }
        $array=explode('|',$userinfo);
        $uid=$array[0];
        $username=$array[1];
        $password=$array[2];
        if(empty($uid)||empty($username)||empty($password)){
            return false;
        }
        $info=$this->model->table('user')->where('uid='.$uid)->find();
        if(empty($info)){
            return false;
        }
        if($username<>sha1($info['username'])||$password<>sha1($info['password'])){
            return false;
        }
        return true;
    }

    //退出操作
    public function logout()
    {
        setcookie($this->config['SPOT'].'_wxuser','',-1,'/');
    }
	
	  public function gsetloginQrcode($url, $code){
        $filename = __ROOTDIR__.'/upload/login/'.$code.'.png';  
        if(!file_exists($filename)){
            $qercode = new Qrcodes();
            $qr = $qercode->_Qrcode($url,$filename);
        }
	
    }
}

?>