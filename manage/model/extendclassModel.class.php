<?php
//表单数据处理
class extendclassModel extends commonModel {

	public function __construct()
    {
        parent::__construct();
    }
	
	public function classes_list($where){
		return $this->model->table('classes')->where($where)->order('sequence asc')->select();
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
		
		
	public function teacher_list($where){
		$data=$this->model->table('teacher')->where($where)->order('sequence asc')->select();
		if($data){
			foreach($data as $key=>$value){
				$temp[$value['id']]=$value;
				
				}
			return $temp;
			}
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