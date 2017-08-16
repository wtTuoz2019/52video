<?php
class parentMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		$this->getuserinfo();
		$student=model('user')->student('A.uid='.$this->userinfo['uid']." and type='student' and  B.uid=".$this->config['uid']." and  A.byuid=".$this->config['uid']);
	
		if(!$student){
		$this->redirect('/login/parent?'.$this->urltoken);
			}else{
		if(!$student['mobile']||!$student['relation']){
		$this->redirect('/login/relation?'.$this->urltoken);
			}	
		if(!$student['bj_id']){
		 $student['bj_id']=model('schooluser')->getclassesid(array('class'=>$student['class'],'grade'=>$student['grade']));
			model('extendclass')->student_edit_save(array('id'=>$student['stid'],'bj_id'=>$student['bj_id']));
			}	
		$this->student=$student;
				}
    }

	public function index() {
        
		
		$this->display('parents_usercenter.html');
	}
	public function center() {
        
		$this->classes=model('extendclass')->classes_list(array('uid'=>$this->config['uid']));
		
		$wheretemp['uid']= $this->config['uid'];
		$wheretemp['endtime']=array('>',time());
		
		 $num=model('extendclass')->batch_list_count($wheretemp);
		if($num>1){
		$batchlist=model('extendclass')->batch_list($wheretemp);
		foreach($batchlist as $key=>$value){
			
			$bids.=$bids?','.$value['id']:$value['id'];
			}		
		$where=array('0'=>'A.bid in (' .$bids.')','sid'=>$this->student['stid']);	
		}else{
		$course=model('extendclass')->new_course(array('uid'=>$this->config['uid']));
		$where=array('0'=>'A.bid='.$course['id'],'sid'=>$this->student['stid']);
		}
		$this->kclist=model('extendclass')->my_course_list($where);	
		$this->display('parent_center.html');
	}
	public function batchlist(){
		$where['uid']= $this->config['uid'];
		$where['endtime']=array('>',time());
		 $num=model('extendclass')->batch_list_count($where);
		if( $num>1){
		 $this->list=model('extendclass')->batch_list($where);	
			
		}else{
			
		 header('Location:' .__URL__.'/kclist?'.$this->urltoken);exit();
			}
		
			$this->display('parents_batchlist.html');
		}
	public function kclist(){
		if($_GET['bid']){
			$course=model('extendclass')->batch_info(array('uid'=>$this->config['uid'],'id'=>intval($_GET['bid'])));
			}else{
		$course=model('extendclass')->new_course(array('uid'=>$this->config['uid']));
			}
		if(!$course)$this->alert('暂无选课');
		
	
		$this->group=model('extendclass')->group_list(array('uid'=>$this->config['uid'],'bid'=>$course['id']));		
		$where=array('bid'=>$course['id']);
		if($_POST['group'])$this->groupname=$where['group']=$_POST['group'];
		$this->kclist=model('extendclass')->course_list($where);	
		
		$this->display('parents_kclist.html');
		}
	public function kcdetail(){
		
		$id=intval($_GET['id']);
		$course=model('extendclass')->course_info('A.id='.$id.' and A.uid='.$this->config['uid']);
		
		if(!$course)$this->alert('无此课程');
	
		
		$course['signnum']=0;
		if($course['starttime']<time()){
			
			
		$course['bj_ids']=unserialize($course['bj_ids']);
		if($course['bj_ids'][0]||count($course['bj_ids'])>1){
			if(!in_array($this->student['bj_id'],$course['bj_ids'])){
				$this->alert('您所在的班级不能报名该课程');
				}
			}
			$bjsignnum=model('extendclass')->signup_bj_num(array('cid'=>$course['id'],'bj_id'=>$this->student['bj_id']));;
			if($bjsignnum>=$course['limitnum'])$this->alert('报名已满');
			
			$course['signnum']=model('extendclass')->signup_num(array('cid'=>$course['id']));
			if($course['signnum']>=$course['number'])$this->alert('报名已满');
			
			
			}
		$this->signupdetail=model('extendclass')->signup_info(array('cid'=>$course['id'],'sid'=>$this->student['stid']));
		$this->course=$course;
		
		$this->display('parents_kcdetail.html');
		}
	public function signup(){
		
		$batch=model('extendclass')->new_course(array('uid'=>$this->config['uid']));
		if($batch['limitnum']){
			if($batch['limitnum']==model('extendclass')->signup_num(array('bid'=>$batch['id'],'sid'=>$this->student['stid']))){
				$this->msg('每位学生最多只能报'.$batch['limitnum'].'课程',0);
				}
			
			}
		$id=intval($_POST['id']);
		$course=model('extendclass')->course_info('A.id='.$id.' and A.uid='.$this->config['uid']);
		if(!$course)$this->msg('无此课程',0);
		$course['bj_ids']=unserialize($course['bj_ids']);
		if($course['bj_ids'][0]||count($course['bj_ids'])>1){
			if(!in_array($this->student['bj_id'],$course['bj_ids'])){
				$this->msg('您所在的班级不能报名该课程',0);
				}
			}
			$bjsignnum=model('extendclass')->signup_bj_num(array('cid'=>$course['id'],'bj_id'=>$this->student['bj_id']));;
			if($bjsignnum>=$course['limitnum'])$this->msg('报名已满',0);
			
			$course['signnum']=model('extendclass')->signup_num(array('cid'=>$course['id']));
			if($course['signnum']>=$course['number'])$this->msg('报名已满',0);
			
			$data=array('cid'=>$course['id'],'bid'=>$batch['id'],'sid'=>$this->student['stid'],'time'=>time());
			model('extendclass')->signup_add_save($data);
			$this->msg('报名成功',1);
		}
	public function cansesignup(){
		$id=intval($_POST['id']);
		$data=array('cid'=>$id,'sid'=>$this->student['stid']);
		model('extendclass')->signup_del($data);
		$this->msg('取消报名成功',1);
		}
	public function schooluser_del(){
		
			 $data=array('uid'=>$this->userinfo['uid'],'type'=>'student','stid'=>$this->student['stid'],'byuid'=>$this->config['uid']);
			 model('extendclass')->schooluser_del($data);
			$this->msg('解除成功',1);
		}
}
?>