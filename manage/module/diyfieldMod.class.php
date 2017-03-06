<?php
//管理
class diyfieldMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	 //列表
    public function index()
    {
        $this->list=model('diyfield')->diyfield_list();
        $this->show();
    }
    //添加
    public function add()
    {
        $this->action_name='添加';
        $this->action='add';
        $this->diyfield_list=model('diyfield')->diyfield_list();

        $this->display('diyfield/info');
    }
  //保存
    public function add_save()
    {
        

        model('diyfield')->save($_POST);
       
        /*hook end*/
        $this->msg('添加成功！',1);
    }

     //添加
    public function edit()
    {    $id=intval($_GET['id']);
        $this->action_name='编辑';
        $this->action='edit';
        $this->diyfield_list=model('diyfield')->diyfield_list();
        $this->info=model('diyfield')->info($id);

        $this->display('diyfield/info');
    }
  //保存
    public function edit_save()
    {
        

        model('diyfield')->edit_save($_POST);
       
        /*hook end*/
        $this->msg('编辑成功！',1);
    }
	

	//删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('diyfield')->del($_POST['id']);
        
        $this->msg('页面删除成功！',1);
    }
	
	  //自定义字段
    public function field_list() {
        $id=$_GET['id'];
        $this->alert_str($id,'int');
        $this->info=model('diyfield')->info($id);
        $this->list=model('diyfield')->field_list($id);
		
        $this->show();
    }
	 //添加字段
    public function field_add(){
        $did=$_GET['id'];
        $this->alert_str($did,'int');
        $this->diyfieldinfo=model('diyfield')->info($did);
	       $this->list=model('diyfield')->field_list($did);
        $this->action_name='添加';
        $this->action='field_add';
        $this->show('diyfield/field_info'); 
    }
	 //添加字段
    public function field_add_save(){
        model('diyfield')->field_save($_POST);
       
        /*hook end*/
        $this->msg('编辑成功！',1);
    }
	
	    //修改字段
    public function field_edit()
    {
        $id=intval($_GET['id']);
        $this->alert_str($id,'int');
        $this->info=model('diyfield')->field_info($id);
		 $this->list=model('diyfield')->field_list($this->info['did']);
        $this->diyfieldinfo=model('diyfield')->info($this->info['did']);
    
        $this->action_name='编辑';
        $this->action='field_edit';
        $this->show('diyfield/field_info'); 
    }

    //字段数据修改
    public function field_edit_save()
    {
        $this->alert_str($_POST['did'],'int',true);
        $this->alert_str($_POST['id'],'int',true);
     
        //录入模型处理
        model('diyfield')->field_edit($_POST);
        $this->msg('字段修改成功！',1);
    }

    //字段删除
    public function field_del()
    {
        $this->alert_str($_POST['id'],'int',true);
        //录入模型处理
        model('diyfield')->field_del($_POST["id"]);
        $this->msg('字段删除成功！',1);
    }

}