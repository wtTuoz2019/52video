<?php
//老师管理
class channelMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	 //老师列表
    public function index()
    {	
	
		 $url = __URL__ . '/index/page-{page}'; //分页基准网址
        $listRows = 20;
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        $limit_start = ($cur_page - 1) * $listRows;
        $limit = $limit_start . ',' . $listRows;
        $this->list=model('channel')->model_list($where,$limit);
		 $count=model('channel')->count($where);
        $this->assign('page', $this->page($url, $count, $listRows));
		
		
		$users=model('user')->admin_list();
		$temp;
		foreach($users as $key=>$value){
			$temp[$value['id']]=$value['nicename'];
			}
		$this->users=$temp;
        $this->show();
    }
    //老师添加
    public function add()
    {
        $this->action_name='添加';
        $this->action='add';
      	 $this->users=model('user')->admin_list();
		
        $this->display('channel/info');
    }
  //老师保存
    public function add_save()
    {
        model('channel')->save($_POST);
        /*hook end*/
        $this->msg('直播通道添加成功！',1);
    }

     //老师添加
    public function edit()
    {    $id=intval($_GET['id']);
        $this->action_name='编辑';
        $this->action='edit';
    	  $this->users=model('user')->admin_list();
        $this->info=model('channel')->info($id);

        $this->display('channel/info');
    }
  //老师保存
    public function edit_save()
    {
        

        model('channel')->edit_save($_POST);
       
        /*hook end*/
        $this->msg('直播通道编辑成功！',1);
    }
	

	//老师删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('channel')->del($_POST['id']);
        
        $this->msg('页面删除成功！',1);
    }

}