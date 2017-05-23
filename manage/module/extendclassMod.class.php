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
	public function batch(){
	
		$where['uid']= $this->user['id'];
		 $this->list=model('extendclass')->batch_list($where);
		 $this->show();
		
		}
	public function batch_add(){
		$this->actionname='添加';
		$this->action='batch_add';
		
		 $this->show('extendclass/batch_info');
		}
	public function batch_add_save(){
		
		$_POST['uid']= $this->user['id'];
		model('extendclass')->batch_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function batch_edit(){
		$this->actionname='编辑';
		$this->action='batch_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		
		 $this->info=model('extendclass')->batch_info(array('id'=>$id));
		
		 $this->show('extendclass/batch_info');
		}
	
	public function batch_edit_save(){
		
		model('extendclass')->batch_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function batch_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->batch_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
	public function course(){
			$where['uid']= $this->user['id'];
		
		 $this->teacher=model('extendclass')->teacher_list($where);
	
		  $this->bj=model('extendclass')->classes_list($where);
		  if($_GET['s']){
			 $where['name']=array('like',"'%".$_GET['s']."%'");
			  }
		   $this->list=model('extendclass')->course_list($where);
		 $this->show();
		
		}
	public function course_add(){
		$this->actionname='添加';
		$this->action='course_add';
		$where['uid']= $this->user['id'];
		 $this->bj=model('extendclass')->classes_list($where);
	   $this->teacher=model('extendclass')->teacher_list($where);
		 $this->show('extendclass/course_info');
		}
	public function course_add_save(){
		
		$_POST['uid']= $this->user['id'];
		$_POST['bj_ids']=serialize($_POST['bj_ids']);
		$_POST['starttime']=strtotime($_POST['starttime']);
		$_POST['endtime']=strtotime($_POST['endtime']);
		model('extendclass')->course_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function course_edit(){
		$this->actionname='编辑';
		$this->action='course_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$where['uid']= $this->user['id'];
		 $this->bj=model('extendclass')->classes_list($where);
		  $this->teacher=model('extendclass')->teacher_list($where);
		 $info=model('extendclass')->course_info(array('id'=>$id));
		$info['bj_ids']=unserialize($info['bj_ids']);
	
		$this->info=$info;
		 $this->show('extendclass/course_info');
		}
	
	public function course_edit_save(){
		$_POST['bj_ids']=serialize($_POST['bj_ids']);
		$_POST['starttime']=strtotime($_POST['starttime']);
		$_POST['endtime']=strtotime($_POST['endtime']);
		model('extendclass')->course_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function course_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->course_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
		
	public function signup(){
		$where['uid']= $this->user['id'];
		 $cid=intval($_GET['cid']);
        $this->alert_str($cid,'int');
	 $info=model('extendclass')->course_info(array('id'=>$cid));
			 $this->show();
		
		}
}
?>