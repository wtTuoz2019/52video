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
		return $this->model->table('teacher')->where($where)->order('sequence asc')->select();
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
}
?>