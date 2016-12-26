<?php
//用户管理
class userMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		$this->password();
	}




	  public function password() {
    	 $this->actionname='密码修改';
          $this->action='password';
        $this->info=model('user')->current_user();
      
     
      
        $this->show('user/edit_info');
    }
	public function school(){
		 $this->actionname='学校信息';
          $this->action='school';
		 $this->school=model('school')->info($this->user['cid']);
		 $this->show('user/school');
		}
	public function school_save(){
		$this->alert_str($_POST['id'],'int',true);
     
       
        model('diyfield')->field_edit($_POST);
		  $this->msg('信息修改成功! ',1);
		}
    //用户修改
    public function edit_save() {

        if (!empty($_POST['password']))
        {
            if (empty($_POST['password2']))
            {
               $this->msg('未填写确认密码！',0);
               return;
            }
            if($_POST['password']<>$_POST['password2']){
                $this->msg('两次密码输入不同！',0);
                return;
            }
            if(model('user')->count($_POST['user'],$_POST['id'])){
                $this->msg('帐号不能重复！',0);
                return;
            }
            $_POST['password']=md5($_POST['password']);
        }else{
            unset($_POST['password']);
        }
        
        //录入模型处理
        model('user')->edit($_POST);
        $this->msg('密码修改成功! ',1);
    }
	public function device(){
		 $this->actionname='设备列表';
          $this->action='device';
		  $this->names=model('diyfield')->field_list_data(5);
		  
		$this->show();
		}
	public function peoples(){
		
		 $this->actionname='监看人员';
          $this->action='peoples';
		  $this->alert_str($_GET['id'],'int',true);
		  $this->id=$id=$_GET['id'];
		  $device=model('device')->info($id);
		  if($this->user['id']!=$device['cid']){
			   $this->msg('没有该设备管理权! ');
			  }
		$this->peoples=model('device')->peoples($id);
		
		$this->show();
		}
	public function addpeople(){
		 $this->action_name='添加';
            $this->action='addpeople';
			  $this->did=intval($_GET['id']);
		 $this->display('');
		
		}
	public function getpeople(){
		$nicename=$_POST['nicename'];	
		$peoples=model('user')->getpeoplebynicename($nicename);
		if($peoples){
			 $this->msg($peoples,1);
			}else{
			 $this->msg('没有该用户',0);	
				}
		}
	public function addpeople_save(){
		$nicename=$_POST['nicename'];	
		$uid=model('user')->getuserbynicename($nicename);
		if(!$uid){$this->msg('该用户不存在',0);}
		$data=array(
		'did'=>intval($_POST['did']),
		'nicename'=>$nicename,
		'uid'=>intval($_POST['uid'])
		);
		$id=model('device')->addpeople_save($data);
		 $this->msg('添加成功',1);
		
		}
	public function delpeople(){
		$this->alert_str($_POST['id'],'int',true);
		model('device')->delpeople($_POST['id']);
		  $this->msg('删除成功! ',1);
		}
	public function baoxiu(){
		$this->alert_str($_POST['id'],'int',true);
		model('device')->baoxiu($_POST['id']);
		  $this->msg('报修成功! ',1);
		}
}