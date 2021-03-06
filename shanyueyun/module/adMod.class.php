<?php
//广告位管理
class adMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	 //广告位列表
    public function index()
    {	 //分页信息
        $listRows=20;
        $url = __URL__ . '/index/' .'page-{page}'.'.html'; //分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		
        $this->list=model('ad')->position_list($limit);
		$count=model('ad')->position_list_count();
        $this->page=$this->page($url, $count, $listRows);
        $this->show();
    }
    //广告位添加
    public function addposition()
    {
        $this->action_name='添加';
        $this->action='addposition';
       

        $this->display('ad/info');
    }
	 //广告添加
    public function add()
    {
        $this->action_name='添加';
        $this->action='add';
       
		 $pid=intval($_GET['pid']);
		$info['pid']=$pid;
		$this->info=$info;
		
        $this->display('ad/adinfo');
    }
	
	//广告位保存
    public function addposition_save()
    {
        

        model('ad')->saveposition($_POST);
       
        /*hook end*/
        $this->msg('广告位添加成功！',1);
    }
	 //广告列表
    public function adlist()
    {	
		 $this->pid=$pid=intval($_GET['pid']);
		 $listRows=20;
        $url = __URL__ . '/adlist/pid-' . $pid . '-page-{page}'.'.html'; //分页基准网址
        $limit=$this->pagelimit($url,$listRows);
        $this->list=model('ad')->ad_list($pid,$limit);
		$count=model('ad')->ad_list_count($pid);
        $this->page=$this->page($url, $count, $listRows);
        $this->show();
    }
	
  //广告位保存
    public function add_save()
    {	
        $_POST['starttime']=strtotime($_POST['starttime']);
		 $_POST['endtime']=strtotime($_POST['endtime']);
		  $_POST['time']=time();

        model('ad')->save($_POST);
       
        /*hook end*/
        $this->msg('广告位添加成功！',1);
    }

     //广告位添加
    public function edit()
    {    $id=intval($_GET['id']);
        $this->action_name='编辑';
        $this->action='edit';
       // $this->ad_list=model('ad')->ad_list();
        $this->info=model('ad')->info($id);

        $this->display('ad/adinfo');
    }
	
	//广告位添加
    public function editposition()
    {    $id=intval($_GET['id']);
        $this->action_name='编辑';
        $this->action='editposition';
       
        $this->info=model('ad')->infoposition($id);

        $this->display('ad/info');
    }
	 //广告保存
    public function edit_save()
    { $_POST['starttime']=strtotime($_POST['starttime']);
		 $_POST['endtime']=strtotime($_POST['endtime']);
		  $_POST['time']=time();
        model('ad')->edit_save($_POST);
       
        /*hook end*/
        $this->msg('广告编辑成功！',1);
    }
	
  //广告位保存
    public function editposition_save()
    {
        

        model('ad')->editposition_save($_POST);
       
        /*hook end*/
        $this->msg('广告位编辑成功！',1);
    }
	
	
	
	//广告位删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('ad')->del($_POST['id']);
        
        $this->msg('删除成功！',1);
    }
	
	//广告位删除
    public function delposition()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('ad')->delposition($_POST['id']);
        
        $this->msg('删除成功！',1);
    }

}