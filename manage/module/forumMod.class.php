<?php
//论坛管理
class forumMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }
	public function index(){
		
		
		$this->show();
		}
	public function config(){
		
		$this->info= model('forum')->forum_config(array('token'=>$this->user['token']));
		$this->show();
		}
	public function config_save(){
		
		$_POST['token']=$this->user['token'];
		$_POST['uid']=$this->user['id'];
        model('forum')->forum_config_save($_POST);
       
        /*hook end*/
        $this->msg('配置成功！',1);
		}
	public function comment(){
		
		$this->show();
		}
	
}
?>