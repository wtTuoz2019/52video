<?php
class indexMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		
		$this->list= model('work')->model_list();
		$this->display();
	}
	public function add(){
		$this->action='add';
		$this->display('index/info');
		}
	public function add_save(){
		$_POST['uid']=$this->user_info['uid'];
		$_POST['dateline']=time();
		 model('work')->save($_POST);
       
        /*hook end*/
        $this->msg('添加成功！',1);
		
		
	}
	  //老师添加
    public function edit()
    {    $id=intval($_GET['id']);
        $this->action_name='编辑';
        $this->action='edit';
    
        $this->info=model('work')->info($id);

        $this->display('index/info');
    }
  //保存
    public function edit_save()
    {
        
		$_POST['uid']=$this->user_info['uid'];
		$_POST['dateline']=time();
     	model('work')->edit_save($_POST);
       
        /*hook end*/
        $this->msg('编辑成功！',1);
    }
	 //保存
    public function betch()
    {
        
		$_POST['wuid']=$this->user_info['uid'];
		$_POST['overtime']=time();
     	model('work')->edit_save($_POST);
       
        /*hook end*/
        $this->msg('设置成功！',1);
    }
	

	//删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('work')->del($_POST['id']);
        
        $this->msg('删除成功！',1);
    }
	
	

}