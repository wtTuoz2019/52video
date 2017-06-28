<?php
class wechatModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	
	
	public function save($table,$data){
		$save=$this->model->table($table)->where(array('uid'=>$data['uid']))->find();
		if($save){
			return $this->model->table($table)->data($data)->where(array('uid'=>$data['uid']))->update(); 
			}else{
		 	return $this->model->table($table)->data($data)->insert(); 
			}
	 }
	
	public function wechat_info($table,$uid){
		
		return $this->model->table($table)->where(array('uid'=>$uid))->find();
		
		}
		//图文或文本信息 
	public function insert($table,$data){
		
		return $this->model->table($table)->data($data)->insert(); 
		}
	public 	function info_list($table,$token){
		return $this->model->table($table)->where(array('token'=>$token))->select();
		}
	public function re_info($table,$id){
		
		return $this->model->table($table)->where(array('id'=>$id))->find();
		}	
	//更新 信息
	public function info_save($table,$data){
		
		return $this->model->table($table)->data($data)->where(array('id'=>$data['id']))->update(); 
			
			
		}	
	//菜单设置_获取父菜单	
	public function get_p_menu($where){
		return $this->model->table('diymen_class')->where($where)->order('sort desc,id desc')->select();
		
		}
	//获取子菜单	
	public function get_c_menu($pid){
		return $this->model->table('diymen_class')->where(array('token'=>$_SESSION['token'],'pid'=>$pid))->order('sort desc,id desc')->select();
		}	
	public function del($table,$where){
		
		return $this->model->table($table)->where($where)->delete();
		}
	
}
?>