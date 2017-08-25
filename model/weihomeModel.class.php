<?php
class weihomeModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function info($where){
		
		return $this->model->table('weihome')->where($where)->find();
		}
	public function menus($where){
		
		return $this->model->table('diymen_class')->where($where)->order('sort desc, id desc')->select();
		}
	
}
?>