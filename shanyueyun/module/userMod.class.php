<?php
//用户管理
class userMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		
		if($_GET['status']){
			$where=' AND A.status='.$_GET['status'];
			$url='-status-'.$_GET['status'];
			}
		 $search=in(urldecode($_GET['search']));
        if(!empty($search)){
            $where.=' and  ( nicename like "%' . $search . '%")';
            $url='-search-'.$search;
        }
		 $url = __URL__ . '/index/page-{page}'.$url; //分页基准网址
        $listRows = 20;
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        $limit_start = ($cur_page - 1) * $listRows;
        $limit = $limit_start . ',' . $listRows;

     
     
        $this->list=model('user')->admin_list($where,$limit);
		 $count=model('user')->admin_count($where);
        $this->assign('page', $this->page($url, $count, $listRows));
		$this->show();
	}

	//用户添加
	public function add() {
        $this->user_group=model('user_group')->admin_list();
		$this->user_dai=model('user')->admin_list(' AND gid=6');

		$this->action_name='添加';
        $this->action='add';
		$this->school=model('school')->school_list();
        $this->display('user/info');
	}

	public function add_save() {
        if($_POST['password']<>$_POST['password2']){
            $this->msg('两次密码输入不同！',0);
            return;
        }
        if(model('user')->count($_POST['user'])){
            $this->msg('帐号不能重复！',0);
            return;
        }
		if(($_POST['gid']==2||$_POST['gid']==4)&&!$_POST['cid']){
			 $this->msg('所属学校必选！',0);
            return;
			}
		
        $_POST['password']=md5($_POST['password']);
		if($_POST['overtime']){
			 $_POST['overtime']=strtotime($_POST['overtime']);
			
			}
        //录入模型处理
        model('user')->add($_POST);
        $this->msg('用户添加成功！',1);
	}

    //用户修改
    public function edit() {
        $id=$_GET['id'];
        $this->alert_str($id,'int');
        $this->user_group=model('user_group')->admin_list();
		$this->user_dai=model('user')->admin_list(' AND gid=6');
	
        $this->info=model('user')->info($id);
		$this->school=model('school')->school_list();
        $this->info_group=model('user_group')->info($this->info['gid']);
        $user=model('user')->current_user();
        if($this->info_group['grade']<$user['grade']){
            $this->msg('越权操作！',0);
        }
        $this->action_name='编辑';
        $this->action='edit';
        $this->display('user/info');
    }

    //修改资料
    public function edit_info() {
        $id=$_GET['id'];
        $this->alert_str($id,'int');
        $this->info=model('user')->info($id);
        $this->info_group=model('user_group')->info($this->info['gid']);
        $user=model('user')->current_user();
        if($this->info_group['grade']<$user['grade']){
            $this->msg('越权操作！',0);
        }
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('user/edit_info');
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
        if($_POST['overtime']){
			 $_POST['overtime']=strtotime($_POST['overtime']);
			
			}
		if($_POST['gid']==2){
			
			if(!$_POST['cid']){
				$this->msg('学校必选！',0);
				}
			}
		if(!$_POST['cid']){
			$_POST['cid']=0;
			}
		
        //录入模型处理
        model('user')->edit($_POST);
        $this->msg('用户修改成功! ',1);
    }

    //用户删除
    public function del() {
        $id=intval($_POST['id']);
        $this->alert_str($id,'int',true);
        $info=model('user')->info($id);
        if($info['keep']==1){
            $this->msg('内置管理员无法删除！',0);
        }
        $info_group=model('user_group')->info($info['gid']);
        $user=model('user')->current_user();
        if($info_group['grade']<$user['grade']){
            $this->msg('越权操作！',0);
        }
        //录入模型处理
        model('user')->del($id);
        $this->msg('用户删除成功！',1);
    }

}