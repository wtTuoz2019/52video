<?php
class selfformMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function index(){
		  $listRows=9;
        $url = __URL__ . '/index/page-{page}.html'; //分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		if($this->user['gid']!=1)
		$where['uid']= $this->user['id'];
		 $this->list=model('selfform')->form_list($where,$limit);
        $count=model('selfform')->count($where);
        $this->page=$this->page($url, $count, $listRows);
		 $this->show();
		}
		
	public function add(){
		$this->actionname='表单添加';
		$this->action='add';
		 $this->show('selfform/info');
		}
	public function add_save(){
		
		$_POST['uid']= $this->user['id'];
		$_POST['token']= $this->user['token'];
		$_POST['time']=time();
		model('selfform')->add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function edit(){
		
		 $this->action_name='表单编辑';
         $this->action='edit';
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$this->info=model('selfform')->form_info(array('id'=>$id));	
		 $this->show('selfform/info');
		}
	public function edit_save(){
		
	
		$_POST['time']=time();
		model('selfform')->edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function inputs(){
		  $this->action='edit';
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$this->info=model('selfform')->form_info(array('id'=>$id));	
		 $this->list=model('selfform')->inputs_list(array('fid'=>$id));
		 $this->field_type=model('expand_model')->field_type();
		
		 $this->show();
		}
	public function inputs_add(){
		$this->actionname='添加';
		$this->action='inputs_add';
		 $fid=intval($_GET['fid']);
        $this->alert_str($fid,'int');
		$this->form=model('selfform')->form_info(array('id'=>$fid));	
		   $this->view()->assign(module('expand_model')->data_info());
		 $this->show('selfform/inputs_info');
		}
	public function inputs_add_save(){
		
		
		model('selfform')->inputs_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function inputs_edit(){
		$this->actionname='编辑';
		$this->action='inputs_edit';
		 $fid=intval($_GET['fid']);
        $this->alert_str($fid,'int');
		$this->form=model('selfform')->form_info(array('id'=>$fid));	
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		 $this->view()->assign(module('expand_model')->data_info());
		 $this->info=model('selfform')->form_inputs_info(array('id'=>$id));
		 $this->show('selfform/inputs_info');
		}
	public function inputs_edit_save(){
		
	
		$_POST['time']=time();
		model('selfform')->inputs_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
}