<?php
class  selfformModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function add_save($data){
		return $this->model->table('selfform')->data($data)->insert();
		
		}
	public function form_list($where,$limit=null){
		
		return $this->model->table('selfform')->where($where)->limit($limit)->select();
		}
	public function count($where){
		
		return $this->model->table('selfform')->where($where)->count();
		}
	public function form_info($where){
		
		return $this->model->table('selfform')->where($where)->find();
		}
	public function edit_save($data){
		return $this->model->table('selfform')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function inputs_list($where){
		return $this->model->table('selfform_input')->where($where)->select();
		}
	public function inputs_add_save($data){
		return $this->model->table('selfform_input')->data($data)->insert();
		
		}
	public function form_inputs_info($where){
		
		return $this->model->table('selfform_input')->where($where)->find();
		}
	public function inputs_edit_save($data){
		return $this->model->table('selfform_input')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function form_value_list($where,$limit=null){
		return $this->model->table('selfform_value')->where($where)->limit($limit)->select();
		}
	public function form_value_count($where){
		return $this->model->table('selfform_value')->where($where)->count();
		}
	public function form_value_del($id,$fid){
		return $this->model->table('selfform_value')->where(array('id'=>$id,'fid'=>$fid))->delete();
		}
}
?>