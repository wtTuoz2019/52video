<?php
class schooluserModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function getclassesid($where){
		$data=$this->model->table('classes')->field('*')->where($where)->find();
		
		return $data['id'];
		
		}
	public function xueshiuserbyopenid($where){
		return $this->model->field('A.*')->table('schooluser','A')->add_table('user','B','A.uid=B.uid')->where($where)->find();
		
		}
	public function xueshiuser($where){
		
		
		return $this->model->table('schooluser')->where($where)->find();
		
		}
		public function xueshiuserdel($where){
		
		
		return $this->model->table('schooluser')->where($where)->delete();
		
		}
	public function xueshiuseradd($data){
		$user=$this->model->table('schooluser')->where(array('uid'=>$data['uid'],'byuid'=>$data['byuid'],'type'=>$data['type']))->find();
		if($user){
			
			return $this->model->table('schooluser')->where(array('uid'=>$data['uid'],'byuid'=>$data['byuid'],'type'=>$data['type']))->data($data)->update();
			
		}
		else
		return $this->model->table('schooluser')->data($data)->insert();
		
		}
	public function xueshihoursadd($data){
		$user=$this->model->table('xueshi_hours')->where(array('userid'=>$data['userid'],'startyear'=>$data['startyear'],'endyear'=>$data['endyear']))->find();
		if($user){
			return $this->model->table('xueshi_hours')->where(array('userid'=>$data['userid'],'startyear'=>$data['startyear'],'endyear'=>$data['endyear']))->data($data)->update();
			
		}
		else
		return $this->model->table('xueshi_hours')->data($data)->insert();
		
		}
		public function xueshihourslist($where){
		
		return $this->model->table('xueshi_hours')->where($where)->order('startyear desc')->select();
		
		}
		
		public function xueshiclasslist($where){
		
		return $this->model->table('xueshi_classes')->where($where)->order('id desc')->select();
		
		}
		
	public function xueshiclassadd($data){
		$user=$this->model->table('xueshi_classes')->where(array('userid'=>$data['userid'],'classname'=>$data['classname']))->find();
		if($user){
			//var_dump($data);
			return $this->model->table('xueshi_classes')->where(array('userid'=>$data['userid'],'classname'=>$data['classname']))->data($data)->update();
			
		}
		else
		return $this->model->table('xueshi_classes')->data($data)->insert();
		
		}

}
?>