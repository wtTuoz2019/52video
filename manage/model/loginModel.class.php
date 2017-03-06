<?php
class loginModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取用户信息
    public function user_info($user) {
        return $this->model->table('admin')->where('user="'.$user.'"')->find();
    }

    //获取用户信息ID
    public function user_info_id($uid) {
        return $this->model->table('admin')->where('id='.$uid)->find();
    }

    //更新用户信息
    public function edit($data,$id) {
    	$condition['id']=$id;
        $this->model->table('admin')->data($data)->where($condition)->update();
    }
	
  //检测登录状态
    public function check_login($userinfo)
    {
        if(empty($userinfo)){
            return false;
        }
        $array=explode('|',$userinfo);
        $uid=$array[0];
        $user=$array[1];
        $password=$array[2];
        if(empty($uid)||empty($user)||empty($password)){
            return false;
        }
	
        $info=$this->model->table('admin')->where('id='.$uid)->find();
			
        if(empty($info)){
            return false;
        }
        if($user<>sha1($info['user'])||$password<>sha1($info['password'])){
            return false;
        }
        return true;
    }



}

?>