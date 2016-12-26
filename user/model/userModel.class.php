<?php
class userModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取当前用户登录信息
    public function current() {
        $userinfo=$_COOKIE[$this->config['SPOT'].'_duxuser'];
        //读取登录信息
        if(empty($userinfo)){
            return ;
        }
        $user=model('login')->check_login($userinfo);
        if(!$user){
            return ;
        }
        $array=explode('|',$userinfo);
        return  $this->model
                ->field('A.*')
                ->table('user','A')
               
                ->where('A.uid='.$array[0])
                ->find();
    }

    //获取用户头像
    public function picture($uid,$size=1) {
        $info=$this->info($uid);

        switch ($size) {
            case 1:
                $name='large';
                break;
            case 2:
                $name='moderate';
                break;
            case 3:
                $name='small';
                break;
            case 4:
                $name='original';
            break;
        }

        $file=__USERDIR__.'/avatar/'.$uid.'/'.$name.'.jpg';
        $url=__ROOT__.'/avatar/'.$uid.'/'.$name.'.jpg';
        if(!file_exists($file)){
            $sex='male_';
            switch ($info['sex']) {
                case 1:
                    $sex='male_';
                    break;
                case 2:
                    $sex='female_';
                    break;
                case 3:
                    $sex='male_';
                    break;
            }
            $url=__PUBLIC__.'/images/avatar/'.$sex.$name.'.png';
        }
        return $url;


    }

    //获取用户组信息
    public function group_info($gid) {
        $where['gid']=$gid;
        return $this->model->table('user_group')->where($where)->find();
    }
	
	//获取用户组列表
    public function group_list() {
        $where['special']=1;
        return $this->model->table('user_group')->where($where)->select();
    }

    //获取用户信息
    public function info($uid) {
        return $this->model
                    ->field('*')
                    ->table('user','A')
                   
                    ->where('A.uid='.$uid)
                    ->find();
    }

    //通过用户名查找
    public function repeat($username) {
        $where['username']=$username;
        return $this->model->table('user')->where($where)->find();
    }

    //修改用户资料
    public function edit($data) {
        $where['uid']=$data['uid'];
        return $this->model->table('user')->data($data)->where($where)->update();
    }

    //
    public function getname($uid){
        $res = $this->model->table('user')->where('uid='.$uid)->find();
        return $res;
    }


}

?>