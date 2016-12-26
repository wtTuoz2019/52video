<?php
//会员组管理
class user_center_groupMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		$this->list=model('user_center_group')->admin_list();
		$this->show();  
	}

	//会员组添加
	public function add() {
        $this->menu_list=model('user_center_group')->menu_list();
        $this->action_name='添加';
        $this->action='add';
        $this->show('user_center_group/info');
	}


    public function data_save($data) {
        $data['model_power']=serialize($data['model_power']);
        $data['menu_power']=serialize($data['menu_power']);
        return $data;
    }

	public function add_save() {
        $data=$this->data_save($_POST);
        //录入模型处理
        model('user_center_group')->add($data);
        $this->msg('会员组添加成功！',1);
	}

    //会员组修改
    public function edit() {
        $gid=$_GET['gid'];
        $this->alert_str($gid,'int');
        //会员组信息
        $this->info=model('user_center_group')->info($gid);
        //获取模块权限
        $this->menu_list=model('user_center_group')->menu_list();
        $this->model_power=unserialize($this->info['model_power']);
        $this->menu_power=unserialize($this->info['menu_power']);
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('user_center_group/info');
    }

    //会员组修改
    public function edit_save() {
        $this->alert_str($_POST['gid'],'int',true);
        $data=$this->data_save($_POST);
        //录入模型处理
        model('user_center_group')->edit($data);
        $this->msg('会员组修改成功! ',1);
    }

    //会员组删除
    public function del() {
        $gid=intval($_POST['gid']);
        $this->alert_str($gid,'int',true);
        $info=model('user_center_group')->info($gid);
        if($info['special']==1){
            $this->msg('系统会员组无法删除！',0);
        }
        //录入模型处理
        model('user_center_group')->del($gid);
        $this->msg('会员组删除成功！',1);
    }
	

	

}