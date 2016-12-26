<?php
//设备管理
class deviceMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		
		$this->names=model('diyfield')->field_list_data(5);
    }

	 //设备列表
    public function index()
    {		
		
		 $search=in(urldecode($_GET['search']));
        if(!empty($search)){
            $where[]=' ( sn like "%' . $search . '%" or note like "%' . $search . '%")';
            $url_search='-search-'.$search;
        }
		 $uid=intval($_GET['uid']);
		 if($uid){
            $where['cid']=$uid;
            $url_search.='-uid-'.$uid;
        }
        $url = __URL__ . '/index/page-{page}'.$url_search; //分页基准网址
       
        $listRows = 20;
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        $limit_start = ($cur_page - 1) * $listRows;
        $limit = $limit_start . ',' . $listRows;
        $this->list=model('device')->model_list($where,$limit);
		 $count=model('device')->count($where);
        $this->assign('page', $this->page($url, $count, $listRows));
		$users=model('user')->admin_list();
		$temp;
		foreach($users as $key=>$value){
			$temp[$value['id']]=$value['nicename'];
			}
		$this->users=$temp;
        $this->show();
    }
    //设备添加
    public function add()
    {
        $this->action_name='添加';
        $this->action='add';
		 $this->users=model('user')->admin_list();
        $this->display('device/info');
    }
  //设备保存
    public function add_save()
    {
		$where=array('nid'=>$_POST['nid']);
		$number=model('device')->count($where);	
		$type=array('141'=>'a','142'=>'b','143'=>'c');
		
		 $_POST['sn']=$type[$_POST['nid']].date('y').($number+1);
			
		
        model('device')->save($_POST);
        /*hook end*/
        $this->msg('直播通道添加成功！',1);
    }

     //设备添加
    public function edit()
    {    $id=intval($_GET['id']);
        $this->action_name='编辑';
        $this->action='edit';
    	  $this->users=model('user')->admin_list();
        $this->info=model('device')->info($id);

        $this->display('device/info');
    }
  //设备保存
    public function edit_save()
    {
        
		
        model('device')->edit_save($_POST);
       
        /*hook end*/
        $this->msg('直播通道编辑成功！',1);
    }
	

	//设备删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('device')->del($_POST['id']);
        
        $this->msg('页面删除成功！',1);
    }

}