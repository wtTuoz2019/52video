<?php
class  weihomeModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function add_save($data){
		
		$menus=$data['menus'];unset($data['menus']);
		$id=$this->model->table('weihome')->data($data)->insert();
		if($menus){
			foreach($menus as $key=>$val){
				model('wechat')->info_save('diymen_class',array('id'=>$val,'indexid'=>$id));
				}
			}
			
			return $id;
		
		
		}
	public function index_list($where,$limit=null){
		
		return $this->model->table('weihome')->where($where)->limit($limit)->order('id desc')->select();
		}
	public function count($where){
		
		return $this->model->table('weihome')->where($where)->count();
		}
	public function index_info($where){
		
		return $this->model->table('weihome')->where($where)->find();
		}
	public function edit_save($data){
		
		if($data['menus']){
			foreach($data['menus'] as $key=>$val){
				model('wechat')->info_save('diymen_class',array('id'=>$val,'indexid'=>$data['id']));
				}
			}
			
			unset($data['menus']);
		return $this->model->table('weihome')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function del($id){
		return $this->model->table('weihome')->where(array('id'=>$id))->delete();
		}
}