<?php
class webMod extends commonMod
{
	  public function __construct()
    {
        parent::__construct();
    }
	
	
	public function config(){
		
		$this->info= model('web')->web_config(array('token'=>$this->user['token']));
		
		$this->show();
	}
	
	public function config_save(){
		if(model('web')->web_config(array('uid'=>array('<>',$this->user['id']),'site'=>$_POST['site']))){
		  $this->msg('域名重复，请更改！',0);
		}
		$_POST['token']=$this->user['token'];
		$_POST['uid']=$this->user['id'];
        model('web')->web_config_save($_POST);
       
        /*hook end*/
        $this->msg('配置成功！',1);
		}
	public function  menu(){
		
		$this->list=model('web')->menu_list(array('uid'=>$this->user['id']));
		
	
		$this->show();
		
		
		}
		
	public function menu_add(){
		$this->actionname='添加';
		$this->action='menu_add';
		$this->list=model('web')->menu_list(array('uid'=>$this->user['id']));
		 $this->show('web/menu_info');
		}
	public function menu_add_save(){
		
			$_POST['uid']=$this->user['id'];
		model('web')->menu_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function menu_edit(){
		$this->actionname='编辑';
		$this->action='menu_edit';
		$this->list=model('web')->menu_list(array('uid'=>$this->user['id']));
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		 $this->info=model('web')->menu_info(array('id'=>$id));
		 $this->show('web/menu_info');
		}
	public function menu_del(){
		 $id=$_POST['id'];
        $fid=$_POST['fid'];
       
        $this->alert_str($_POST['id'],'int',true);
        model('web')->menu_del($id);
      
        $this->msg('删除成功！',1);
		
		}
	public function menu_edit_save(){
		
	
		model('web')->menu_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function  pics(){
		 $mid=intval($_GET['mid']);
        $this->alert_str($mid,'int');
		$this->menu=model('web')->menu_info(array('id'=>$mid));
		$this->list=model('web')->pics_list(array('uid'=>$this->user['id'],'mid'=>$mid));
		$this->show();
		}
		
	public function pics_add(){
		$this->actionname='添加';
		$this->action='pics_add';
		 $mid=intval($_GET['mid']);
		 $this->menu=model('web')->menu_info(array('id'=>$mid));
		
        $this->alert_str($mid,'int');
		 $this->show('web/pics_info');
		}
	public function pics_add_save(){
		
			$_POST['uid']=$this->user['id'];
		model('web')->pics_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function pics_edit(){
		$this->actionname='编辑';
		$this->action='pics_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		 $this->info=$info=model('web')->pics_info(array('id'=>$id));
		 $this->menu=model('web')->menu_info(array('id'=>$info['mid']));
		 $this->show('web/pics_info');
		}
	public function pics_del(){
		 $id=$_POST['id'];
      
       
        $this->alert_str($_POST['id'],'int',true);
        model('web')->pics_del($id);
      
        $this->msg('删除成功！',1);
		
		}
	public function pics_edit_save(){
		
	
		model('web')->pics_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
		
	public function  contact(){
		
		$this->list=model('web')->contact_list(array('uid'=>$this->user['id']));
		
	
		$this->show();
		
		
		}
		
	public function contact_add(){
		$this->actionname='添加';
		$this->action='contact_add';
		$this->list=model('web')->contact_list(array('uid'=>$this->user['id']));
		 $this->show('web/contact_info');
		}
	public function contact_add_save(){
		
			$_POST['uid']=$this->user['id'];
		model('web')->contact_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function contact_edit(){
		$this->actionname='编辑';
		$this->action='contact_edit';
		$this->list=model('web')->contact_list(array('uid'=>$this->user['id']));
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		 $this->info=model('web')->contact_info(array('id'=>$id));
		 $this->show('web/contact_info');
		}
	public function contact_del(){
		 $id=$_POST['id'];
        $fid=$_POST['fid'];
       
        $this->alert_str($_POST['id'],'int',true);
        model('web')->contact_del($id);
      
        $this->msg('删除成功！',1);
		
		}
	public function contact_edit_save(){
		
	
		model('web')->contact_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	
}

?>