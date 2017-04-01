<?php
//管理
class schoolMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		$this->citys=model('diyfield')->field_list_data(6);
    }

	 //列表
    public function index()
    {	 $search=in(urldecode($_GET['search']));
        if(!empty($search)){
            $where=' name like "%' . $search . '%"';
            $url_search='-search-'.$search;
        }
        $url = __URL__ . '/index/page-{page}'.$url_search; //分页基准网址
        $listRows = 20;
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        $limit_start = ($cur_page - 1) * $listRows;
        $limit = $limit_start . ',' . $listRows;
        $this->list=model('school')->model_list($where,$limit);
		 $count=model('school')->count($where);
        $this->assign('page', $this->page($url, $count, $listRows));
        $this->show();
    }
    //添加
    public function add()
    {
        $this->action_name='添加';
        $this->action='add';
      

        $this->display('school/info');
    }
  //保存
    public function add_save()
    {
        

        model('school')->save($_POST);
       
        /*hook end*/
        $this->msg('添加成功！',1);
    }

     //添加
    public function edit()
    {    $id=intval($_GET['id']);
        $this->action_name='编辑';
        $this->action='edit';
       $this->subject=model('diyfield')->field_list_data(2);
		$this->grade=model('diyfield')->field_list_data(1);
		$this->school=model('diyfield')->field_list_data(4);
        $this->info=model('school')->info($id);

        $this->display('school/info');
    }
  //保存
    public function edit_save()
    {
        

     	model('school')->edit_save($_POST);
       
        /*hook end*/
        $this->msg('编辑成功！',1);
    }
	

	//删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('school')->del($_POST['id']);
        
        $this->msg('页面删除成功！',1);
    }

}