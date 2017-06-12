<?php
//表单数据处理
class extendclassModel extends commonModel {

	public function __construct()
    {
        parent::__construct();
    }
	
	public function classes_list($where){
		$data=$this->model->table('classes')->where($where)->order('sequence asc')->select();
		if($data){
			foreach($data as $key=>$value){
				$temp[$value['id']]=$value;
				
				}
			return $temp;
			}
		}
	public function signup_num($where){
		return $this->model->table('course_signup')->where($where)->count();
		}
	public function classes_add_save($data){
		return $this->model->table('classes')->data($data)->insert();
		
		}
	public function classes_edit_save($data){
		return $this->model->table('classes')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function classes_info($where){
		return $this->model->table('classes')->where($where)->find();
		}
	public function classes_del($id){
		return $this->model->table('classes')->where(array('id'=>$id))->delete();
		}
	public function scoretype_list($where){
		$data=$this->model->table('scoretype')->where($where)->order('sequence asc')->select();
		if($data){
			foreach($data as $key=>$value){
				$temp[$value['id']]=$value;
				
				}
			return $temp;
			}
		}
	public function scoretype_add_save($data){
		return $this->model->table('scoretype')->data($data)->insert();
		
		}
	public function scoretype_edit_save($data){
		return $this->model->table('scoretype')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function scoretype_info($where){
		return $this->model->table('scoretype')->where($where)->find();
		}
	public function scoretype_del($id){
		return $this->model->table('scoretype')->where(array('id'=>$id))->delete();
		}
		
	public function teacher_list($where,$limit=null){
		$data=$this->model->table('teacher')->where($where)->limit($limit)->order('sequence asc')->select();
		if($data){
			foreach($data as $key=>$value){
				$temp[$value['id']]=$value;
				
				}
			return $temp;
			}
		}
	public function teacher_count($where){
		return $this->model->table('teacher')->where($where)->count();
	
		}
	public function teacher_add_save($data){
		return $this->model->table('teacher')->data($data)->insert();
		
		}
	public function teacher_edit_save($data){
		return $this->model->table('teacher')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function teacher_info($where){
		return $this->model->table('teacher')->where($where)->find();
		}
	public function teacher_del($id){
		return $this->model->table('teacher')->where(array('id'=>$id))->delete();
		}
		
	public function student_list($where,$limit=null){
		$data=$this->model->table('student')->where($where)->limit($limit)->order('id desc')->select();
		
		return $data;
			
		}
	public function student_count($where){
		return $this->model->table('student')->where($where)->count();
	
		}
	public function student_add_save($data){
		return $this->model->table('student')->data($data)->insert();
		
		}
	public function student($data){
		$student=$this->model->table('student')->where(array('name'=>$data['name'],'uid'=>$data['uid'],'mobile'=>$data['mobile']))->find();
		if($student)return $student['id'];
		else return $this->model->table('student')->data($data)->insert();
		
		}
	public function student_add_saveall($data){
			return $this->model->table('student')->insertAll($data);
		}
	public function student_edit_save($data){
		return $this->model->table('student')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function student_info($where){
		return $this->model->table('student')->where($where)->find();
		}
	public function student_del($id){
		return $this->model->table('student')->where(array('id'=>$id))->delete();
		}
		
	public function signup_list($where){
		$data=$this->model->table('student','A')->field('A.*')->add_table('course_signup','B','A.id=B.sid')->where($where)->select();
		
			return $data;
		}
	public function signup_course_list($where){
		$data=$this->model->table('student','A')->field('A.*,C.name as title,C.tid')->add_table('course_signup','B','A.id=B.sid')->add_table('course','C','B.cid=C.id')->where($where)->select();
		
			return $data;
		}
	public function signup_add_save($data){
		return $this->model->table('course_signup')->data($data)->insert();
		
		}
	public function batch_list($where){
		return $this->model->table('course_batch')->where($where)->select();
		}
	public function batch_add_save($data){
		return $this->model->table('course_batch')->data($data)->insert();
		
		}
	public function batch_edit_save($data){
		return $this->model->table('course_batch')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function batch_info($where){
		return $this->model->table('course_batch')->where($where)->find();
		}
	public function batch_del($id){
		return $this->model->table('course_batch')->where(array('id'=>$id))->delete();
		}
		
		
	public function course_list($where){
		return $this->model->table('course')->where($where)->order('sequence asc')->select();
		}
	public function course_add_save($data){
		return $this->model->table('course')->data($data)->insert();
		
		}
	public function course_edit_save($data){
		return $this->model->table('course')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function course_info($where){
		return $this->model->table('course')->where($where)->find();
		}
	public function course_del($id){
		return $this->model->table('course')->where(array('id'=>$id))->delete();
		}
	
}
?>