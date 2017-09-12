<?php
//老师管理
class teacherMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		$this->getuserinfo();
		
		$teacher=model('user')->teacher('A.uid='.$this->userinfo['uid'].' and  B.uid='.$this->config['uid']." and  A.byuid=".$this->config['uid']);

		if(!$teacher){
		$this->redirect('/login/teacher?'.$this->urltoken);
			}	
			
		$this->teacher=$teacher;
				
    }

	public function index() {
        
		
		$this->display('teacher_usercenter.html');
	}
	
	public function kclist() {
		$course=model('extendclass')->new_course(array('uid'=>$this->config['uid']));
		if(!$course)$this->alert('暂无选课');
        $where=array('bid'=>$course['id'],'tid'=>$this->teacher['stid']);
		$kclist=model('extendclass')->course_list($where);	
		if($kclist){
			foreach($kclist as $key=>$value){
				$kclist[$key]['signupnum']=model('extendclass')->signup_num(array('cid'=>$value['id']));
				}
			
			}
		$this->kclist=$kclist;
		$this->display('teacher_class.html');
	}
	public function kcdetail(){
		
		$id=intval($_GET['id']);
		$course=model('extendclass')->course_info('A.id='.$id.' and A.uid='.$this->config['uid']);
		
		if(!$course)$this->alert('无此课程');
	
		$course['signnum']=0;
	
		$course['signnum']=model('extendclass')->signup_num(array('cid'=>$course['id']));
	
		$this->course=$course;
		
		$this->display('teacher_kcdetail.html');
		}
	public function scorelist(){
		$course=model('extendclass')->new_course(array('uid'=>$this->config['uid']));
		if(!$course)$this->alert('暂无选课');
        $where=array('bid'=>$course['id'],'tid'=>$this->teacher['stid']);
		$kclist=model('extendclass')->course_list($where);	
		if($kclist){
			foreach($kclist as $key=>$value){
				$kclist[$key]['signupnum']=model('extendclass')->signup_num(array('cid'=>$value['id']));
				}
			
			}
		$this->kclist=$kclist;
		$this->display('teacher_scorelist.html');
		
		}
	public function score(){
		$id=intval($_GET['id']);
		if($_POST){
			foreach($_POST['score'] as $key=>$value){
				$where=array('cid'=>$id,'sid'=>$key);
				
				model('extendclass')->signup_score($where,array('score'=>$value));
				}
			$this->msg('设置成功',1);
			}
		
		
		$course=model('extendclass')->course_info('A.id='.$id.' and A.uid='.$this->config['uid']);
		
		if(!$course)$this->alert('无此课程');
		$this->course=$course;
		$this->signuplist=model('extendclass')->signup_list(array('cid'=>$course['id']));
		$this->classes=model('extendclass')->classes_list(array('uid'=>$this->config['uid']));
		$this->scoretype_list=model('extendclass')->scoretype_list(array('uid'=>$this->config['uid']));
		$this->display('teacher_score.html');
		}
	public function attlist(){
		$course=model('extendclass')->new_course(array('uid'=>$this->config['uid']));
		if(!$course)$this->alert('暂无选课');
        $where=array('bid'=>$course['id'],'tid'=>$this->teacher['stid']);
		$kclist=model('extendclass')->course_list($where);	
		if($kclist){
			foreach($kclist as $key=>$value){
				$kclist[$key]['signupnum']=model('extendclass')->signup_num(array('cid'=>$value['id']));
				}
			
			}
		$this->kclist=$kclist;
		$this->display('teacher_attlist.html');
		
		}
	public function attendance(){
		
		$id=intval($_GET['id']);
		$day=date('Ymd',time());
		if($_POST){
			$data=array('cid'=>$id,'sids'=>serialize($_POST['sid']),'tid'=>$this->teacher['stid'],'day'=>$day);		model('extendclass')->attendance_save($data);
			$this->msg('设置成功',1);
			}
		
		
		$course=model('extendclass')->course_info('A.id='.$id.' and A.uid='.$this->config['uid']);
		
		if(!$course)$this->alert('无此课程');
		$this->course=$course;
		$attendance=model('extendclass')->attendance_info(array('cid'=>$course['id'],'day'=>$day));
		if($attendance)
		$attendance['sids']=unserialize($attendance['sids']);
		
		 $this->assign('attendance', $attendance);
		$this->signuplist=model('extendclass')->signup_list(array('cid'=>$course['id']));
		$this->classes=model('extendclass')->classes_list(array('uid'=>$this->config['uid']));
		$this->display('teacher_attendance.html');	
		}
	public function attendance_info(){
		
		$id=intval($_GET['id']);
		$sid=intval($_GET['sid']);
		$this->stduent=$stduent=model('extendclass')->student_info(array('id'=>$sid));
		
		$course=model('extendclass')->course_info('A.id='.$id.' and A.uid='.$this->config['uid']);
		
		if(!$course)$this->alert('无此课程');
		$this->course=$course;
		$attendance=model('extendclass')->attendance_list(array('cid'=>$course['id']));
	
		if($attendance){
			foreach($attendance as $key=>$val){
				
				$val['sids']=unserialize($val['sids']);
				if($val['sids']&&in_array($sid,$val['sids'])){
					$queqin[]='"'.$val['day'].'"';
					}else{
					$zaiqin[]='"'.$val['day'].'"';
					}
				
				}
				
				$this->assign('queqinnum', count($queqin));
			if($queqin)
			$this->assign('queqin', implode(",", $queqin));
				if($zaiqin)
		  $this->assign('zaiqin', implode(",", $zaiqin));
		  $this->assign('zaiqinnum', count($zaiqin));
			}
		
		 
		
		$this->display('teacher_attendance_info.html');	
		}
		public function schooluser_del(){
		
			 $data=array('uid'=>$this->userinfo['uid'],'type'=>'teacher','stid'=>$this->teacher['stid'],'byuid'=>$this->config['uid']);
			 model('extendclass')->schooluser_del($data);
			$this->msg('解除成功',1);
		}

}