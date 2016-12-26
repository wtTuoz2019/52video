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
       
        if(empty($uid)||empty($username)){
            return false;
        }
        $info=$this->model->table('user')->where('uid='.$uid)->find();
	
        if(empty($info)){
            return false;
        }
        if($username<>sha1($info['nicename'])){
            return false;
        }
        return true;
    }

    //退出操作
    public function logout()
    {
        setcookie($this->config['SPOT'].'_duxuser','',-1,'/');
    }

}

?>