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
}
?>