<?php
class webModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function web_config($where=null){
		
		return $this->model->table('web_config')->where($where)->find();
		}
		
	public function web_config_save($data){
		
		if($this->model->table('web_config')->where(array('token'=>$data['token']))->find()){
		
		return $this->model->table('web_config')->where(array('token'=>$data['token']))->data($data)->update();
		
		}else{
			$this->model->table('web_config')->data($data)->insert();	
			}
		}
	public function menu_list($where){
		$data=$this->model->table('web_menu')->where($where)->order('sequence desc,id asc')->select();
		
		  $cat = new Category(array('id', 'pid', 'name', 'cname'));
        return $cat->getTree($data, 0);
		}
	public function menu_add_save($data){
		return $this->model->table('web_menu')->data($data)->insert();
		
		}
	public function menu_info($where){
		
		return $this->model->table('web_menu')->where($where)->find();
		}
	public function menu_edit_save($data){
		return $this->model->table('web_menu')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function menu_del($id){
		$this->model->table('web_menu')->where(array('pid'=>$id))->delete();
		return $this->model->table('web_menu')->where(array('id'=>$id))->delete();
		}
	public function pics_list($where){
		return $this->model->table('web_menu_pics')->where($where)->order('sequence desc,id asc')->select();
		
		}
	public function pics_add_save($data){
		return $this->model->table('web_menu_pics')->data($data)->insert();
		
		}
	public function pics_info($where){
		
		return $this->model->table('web_menu_pics')->where($where)->find();
		}
	public function pics_edit_save($data){
		return $this->model->table('web_menu_pics')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function pics_del($id){
		return $this->model->table('web_menu_pics')->where(array('id'=>$id))->delete();
		}
	public function contact_list($where){
		$data=$this->model->table('web_contact')->where($where)->order('sequence desc,id asc')->select();
		
		
        return $data;
		}
	public function contact_add_save($data){
		return $this->model->table('web_contact')->data($data)->insert();
		
		}
	public function contact_info($where){
		
		return $this->model->table('web_contact')->where($where)->find();
		}
	public function contact_edit_save($data){
		return $this->model->table('web_contact')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function contact_del($id){
		return $this->model->table('web_contact')->where(array('id'=>$id))->delete();
		}
}
?>