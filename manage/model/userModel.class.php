<?php
class userModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //当前用户信息
    public function current_user($cache=true,$del=false) {
		
         $userinfo=$_COOKIE[$this->config['SPOT'].'_adminuser'];
       //读取登录信息
        if(empty($userinfo)){
            return ;
        }
        $user=model('login')->check_login($userinfo);
        if(!$user){
            return ;
        }
        $array=explode('|',$userinfo);
		$uid=$array[0];
        if($del){
            $this->cache->del('current_user_'.$uid);
            return ;
        }
	
        $user=$this->cache->get('current_user_'.$uid);
        if(empty($user)||$cache==false){
            $user=$this->model->table('admin')->where('id='.$uid)->find();
            $user_group=$this->model->table('admin_group')->where('id='.$user['gid'])->find();
            $user['gname']=$user_group['name'];
            $user['model_power']=$user_group['model_power'];
            $user['class_power']=$user_group['class_power'];
            $user['menu_power']=$user_group['menu_power'];
            $user['form_power']=$user_group['form_power'];
			 $user['home_power']=$user_group['home_power'];
            $user['grade']=$user_group['grade'];
            $user['keep']=$user_group['keep'];
		
            $this->cache->set('current_user_'.$uid, $user);
        }
        return $user;
    }

    //获取用户列表
    public function admin_list($where=null)
    {
        $user=$this->current_user();
      
        $data=$this->model->field('A.*,B.name as gname')
                ->table('admin','A')
                ->add_table('admin_group','B','A.gid = B.id')
                ->where('B.grade>='.$user['grade'].$where)
                ->order('id asc')
                ->select();
        return $data;

    }


	    //获取用户列表
    public function user_list($where=null)
    {
        $user=$this->current_user();
       
        $data=$this->model->field('A.*,B.name as gname')
                ->table('admin','A')
                ->add_table('admin_group','B','A.gid = B.id')
                ->where('B.grade>='.$user['grade'].$where)
                ->order('id asc')
                ->select();
					foreach($data as $key=>$val){
			$temp[$val['id']]=$val;
			}
        return $temp;
       

    }

    //获取用户内容
    public function info($id)
    {
        return $this->model->table('admin')->where('id='.$id)->find();
    }
	 //获取用户内容
    public function getinfo($user,$mobile)
    {
			$where['user']=$user;
			$where['mobile']=$mobile;
        return $this->model->table('admin')->where($where)->find();
    }

    //检测重复用户
    public function count($user,$id=null)
    {
        if(!empty($id)){
            $where=' AND id<>'.$id;
        }
        return $this->model->table('admin')->where('user="'.$user.'"'.$where)->count(); 
    }
	
   public function getuserbynicename($nicename){
	 $where['nicename']=$nicename;
	   
	 $user=$this->model->table('user')->where($where)->field('uid')->find();  
	   return  $user['uid'];
	   }
	  public function getpeoplebynicename($nicename){
	 $where['nicename']=$nicename;
	   
	 $user=$this->model->table('user')->where($where)->field('uid,nicename,headimgurl')->select();  
	   return  $user;
	   }
     //添加用户内容
    public function add($data)
    {
        $_POST['regtime']=time();
        return $this->model->table('admin')->data($data)->insert();
    }
    //编辑用户内容
    public function edit($data)
    {
        $condition['id']=intval($data['id']);
        $id=$this->model->table('admin')->data($data)->where($condition)->update();
        return $id;
    }

    //获取用户内容
    public function admininfo($siteurl)
    {
        return $this->model->table('admin')->where("siteurl='".$siteurl."'")->find();
    }

}

?>