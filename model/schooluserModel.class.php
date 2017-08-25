<?php
class schooluserModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function getclassesid($where){
		$data=$this->model->table('classes')->where($where)->find();
		
		return $data['id'];
		
		}
}
?>