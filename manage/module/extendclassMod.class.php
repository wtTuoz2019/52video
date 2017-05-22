<?php

class extendclassMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	 
    public function index(){
		
		 $this->show();
		
		}
		
	public function classes(){
	
		$where['uid']= $this->user['id'];
		 $this->list=model('extendclass')->classes_list($where);
		 $this->show();
		
		}
	public function classes_add(){
		$this->actionname='添加';
		$this->action='classes_add';
		
		 $this->show('extendclass/classes_info');
		}
	public function classes_add_save(){
		
		$_POST['uid']= $this->user['id'];
		model('extendclass')->classes_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function classes_edit(){
		$this->actionname='编辑';
		$this->action='classes_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		
		 $this->info=model('extendclass')->classes_info(array('id'=>$id));
		
		 $this->show('extendclass/classes_info');
		}
	
	public function classes_edit_save(){
		
		model('extendclass')->classes_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function classes_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->classes_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
	public function teacher(){
	
		$where['uid']= $this->user['id'];
		 $this->list=model('extendclass')->teacher_list($where);
		 $this->show();
		
		}
	public function teacher_add(){
		$this->actionname='添加';
		$this->action='teacher_add';
		
		 $this->show('extendclass/teacher_info');
		}
	public function teacher_add_save(){
		
		$_POST['uid']= $this->user['id'];
		model('extendclass')->teacher_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function teacher_edit(){
		$this->actionname='编辑';
		$this->action='teacher_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		
		 $this->info=model('extendclass')->teacher_info(array('id'=>$id));
		
		 $this->show('extendclass/teacher_info');
		}
	
	public function teacher_edit_save(){
		
		model('extendclass')->teacher_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function teacher_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->teacher_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
}
?>