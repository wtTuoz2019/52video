<?php
class userModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取当前用户登录信息
    public function current() {
        $userinfo=$_COOKIE[$this->config['SPOT'].'_wxuser'];
        //读取登录信息
        if(empty($userinfo)){
            return ;
        }
       
        $array=explode('|',$userinfo);
        return  $this->model->where('uid='.$array[0])
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
	 //获取用户组信息
    public function getuidbylogincode($logincode) {
        $where['logincode']=$logincode;
		 $where[]='logintime >'.(time()-10);
       $data=$this->model->table('user')->field('uid')->where($where)->find();
	   return $data['uid'];
    }

    //获取用户信息
    public function info($uid) {
		
        return $this->model
                    ->table('user')
                    ->where('uid='.$uid)
                    ->find();
    }

	  //获取用户信息
    public function adminuser($uid) {
		
        return $this->model
                    ->table('admin')
                    ->where('id='.$uid)
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
	
	  //获取用户内容
    public function admininfo($siteurl)
    {
        return $this->model->table('admin')->where("siteurl='".$siteurl."'")->find();
    }
	
	  //获取用户内容
    public function admininfobyid($id)
    {
        return $this->model->table('admin')->where("id='".$id."'")->find();
    }

  //获取用户内容
    public function admininfobytoken($token)
    {
        return $this->model->table('admin')->where("token='".$token."'")->find();
    }
	  //获取用户内容
    public function wxuser($token)
    {
		
        return $this->model->table('wxuser')->where(array('token'=>$token))->find();
    }
	
	  //添加直播预告
    public function addlive($data)
    {
        return $this->model->table('livenotice')->data($data)->insert();
    }
	
	 public function livelist($where)
    {
        return $this->model->table('livenotice')->where($where)->order('starttime desc')->select();
    }

	public function student($where){
		return $this->model->table('schooluser','A')->add_table('student','B','A.stid=B.id')->where($where)->find();
		
		}

}

?>