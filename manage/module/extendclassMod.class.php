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
	public function scoretype(){
	
		$where['uid']= $this->user['id'];
		 $this->list=model('extendclass')->scoretype_list($where);
		 $this->show();
		
		}
	public function scoretype_add(){
		$this->actionname='添加';
		$this->action='scoretype_add';
		
		 $this->show('extendclass/scoretype_info');
		}
	public function scoretype_add_save(){
		
		$_POST['uid']= $this->user['id'];
		model('extendclass')->scoretype_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function scoretype_edit(){
		$this->actionname='编辑';
		$this->action='scoretype_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		
		 $this->info=model('extendclass')->scoretype_info(array('id'=>$id));
		
		 $this->show('extendclass/scoretype_info');
		}
	
	public function scoretype_edit_save(){
		
		model('extendclass')->scoretype_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function scoretype_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->scoretype_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
	public function teacher(){
	
		$where['uid']= $this->user['id'];
		 	   $url = __URL__ . '/teacher/page-{page}.html';
        $listRows = 20;
        $limit=$this->pagelimit($url,$listRows);
		
		 $this->list=model('extendclass')->teacher_list($where,$limit);
		 
		   $count=model('extendclass')->teacher_count($where);
		   $this->page = $this->page($url, $count, $listRows);
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
	public function student(){
		
		  $where['uid']= $this->user['id'];
		  $this->bj=$bj=model('extendclass')->classes_list($where);
		  	if($_FILES['file']['name']){
		$return=module('editor_upload')->upload();
			
			if($return['error'])$this->error($return['msg']);
			$data = new Spreadsheet_Excel_Reader();
			// 设置输入编码 UTF-8/GB2312/CP936等等
			$data->setOutputEncoding('UTF-8');
			$data->read('..'.$return['url']);
			$sheet=$data->sheets[0];
			$rows=$sheet['cells'];
			$temp=array();
		
			foreach($rows as  $key=>$val){
				if($key>1){
						$array=array('name'=>$val[1],'mobile'=>$val[2],'schoolcode'=>$val[5],'uid'=>$this->user['id']);
						foreach($bj as $k=>$v){
							if($v['grade']==intval($val['3'])&&$v['class']==intval($val['4'])){
								$array['bj_id']=$v['id'];
								$students[]=$array;;break;
								}
							}
						
						
						}
				
			
				}
			
					if($students)
				model('extendclass')->student_add_saveall($students);
			}
		  
		   $url = __URL__ . '/student/page-{page}.html';
        $listRows = 20;
        $limit=$this->pagelimit($url,$listRows);
		
		 $this->list=model('extendclass')->student_list($where,$limit);
		   $count=model('extendclass')->student_count($where);
		   $this->page = $this->page($url, $count, $listRows);
		 $this->show();
		
		}
	public function student_add(){
		$this->actionname='添加';
		$this->action='student_add';
		$where['uid']= $this->user['id'];
		$this->bj=model('extendclass')->classes_list($where);
		 $this->show('extendclass/student_info');
		}
	public function student_add_save(){
		
		$_POST['uid']= $this->user['id'];
		model('extendclass')->student_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function student_edit(){
		$this->actionname='编辑';
		$this->action='student_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$where['uid']= $this->user['id'];
		$this->bj=model('extendclass')->classes_list($where);
		 $this->info=model('extendclass')->student_info(array('id'=>$id));
		
		 $this->show('extendclass/student_info');
		}
	
	public function student_edit_save(){
		
		model('extendclass')->student_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function student_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->student_del($id,$fid);
      
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
		$this->bid=$bid=intval($_GET['bid']);
		 $this->teacher=model('extendclass')->teacher_list($where);
	
		  $this->bj=model('extendclass')->classes_list($where);
		  if($_GET['s']){
			 $where['name']=array('like',"'%".$_GET['s']."%'");
			  }
			 $where['bid']=$bid;
		   $list=model('extendclass')->course_list($where);
		   if($list){
			   foreach($list as $key=>$val){
				   $list[$key]['signupnum']=model('extendclass')->signup_num(array('cid'=>$val['id']));
				   
				   }
			   
			   }
		$this->list=$list;
		 $this->show();
		
		}
	public function course_add(){
		$this->actionname='添加';
		$this->action='course_add';
		$bid=intval($_GET['bid']);
		$info['bid']=$bid;
		$this->info=$info;
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
		  $this->bj=$bj=model('extendclass')->classes_list($where);
		 $cid=intval($_GET['cid']);
        $this->alert_str($cid,'int');
	 $info=model('extendclass')->course_info(array('id'=>$cid));
	   	if($_FILES['file']['name']){
		$return=module('editor_upload')->upload();
			
			if($return['error'])$this->error($return['msg']);
			$data = new Spreadsheet_Excel_Reader();
			// 设置输入编码 UTF-8/GB2312/CP936等等
			$data->setOutputEncoding('UTF-8');
			$data->read('..'.$return['url']);
			$sheet=$data->sheets[0];
			$rows=$sheet['cells'];
			$temp=array();
		
			foreach($rows as  $key=>$val){
				if($key>1){
						$array=array('name'=>$val[1],'mobile'=>$val[2],'schoolcode'=>$val[5],'uid'=>$this->user['id']);
						foreach($bj as $k=>$v){
							if($v['grade']==intval($val['3'])&&$v['class']==intval($val['4'])){
								$array['bj_id']=$v['id'];break;
								}
							}
					
					$sid=model('extendclass')->student($array);
							
						$temp=array('cid'=>$cid,'time'=>time(),'sid'=>$sid);
					
						model('extendclass')->signup_add_save($temp);
						}
				
			
				}
		
			}
	 
	  $this->list=model('extendclass')->signup_list('A.uid='.$this->user['id'].' and B.cid='.$cid);
			 $this->show();
		
		}
	public function signup_add(){
		$this->actionname='添加';
		$this->action='signup_add';
		$where['uid']= $this->user['id'];
		$this->bj=model('extendclass')->classes_list($where);
		 $this->show('extendclass/student_info');
		}
	public function signup_add_save(){
		
		$_POST['uid']= $this->user['id'];
		$sid=model('extendclass')->signup_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}

	public function signup_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->student_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
}
?>