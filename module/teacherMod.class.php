<?php
//老师管理
class teacherMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		$this->getuserinfo();
		
		$teacher=model('user')->teacher('A.uid='.$this->userinfo['uid'].' and  B.uid='.$this->config['uid']);

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
		$course=model('extendclass')->course_info('A.id='.$id.' and A.uid='.$this->config['uid']);
		
		if(!$course)$this->alert('无此课程');
		$this->course=$course;
		$this->signuplist=model('extendclass')->signup_list(array('cid'=>$course['id']));
		$this->classes=model('extendclass')->classes_list(array('uid'=>$this->config['uid']));
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
		

}