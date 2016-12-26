<?php
//老师管理
class teacherMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	 //老师列表
    public function index()
    {
        $search=in(urldecode($_GET['search']));
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
        $this->list=model('teacher')->model_list($where,$limit);
		 $count=model('teacher')->count($where);
        $this->assign('page', $this->page($url, $count, $listRows));
		$this->subject=model('diyfield')->field_list_data(2);
		$this->grade=model('diyfield')->field_list_data(1);
		$this->school=model('school')->school_list();
		
        $this->show();
    }
    //老师添加
    public function add()
    {
        $this->action_name='添加';
        $this->action='add';
      $this->subject=model('diyfield')->field_list_data(2);
		$this->grade=model('diyfield')->field_list_data(1);
		$this->school=model('school')->school_list();

        $this->display('teacher/info');
    }
  //老师保存
    public function add_save()
    {
        

        model('teacher')->save($_POST);
       
        /*hook end*/
        $this->msg('老师添加成功！',1);
    }

     //老师添加
    public function edit()
    {    $id=intval($_GET['id']);
        $this->action_name='编辑';
        $this->action='edit';
       $this->subject=model('diyfield')->field_list_data(2);
		$this->grade=model('diyfield')->field_list_data(1);
		$this->school=model('school')->school_list();
        $this->info=model('teacher')->info($id);

        $this->display('teacher/info');
    }
  //老师保存
    public function edit_save()
    {
        

     	model('teacher')->edit_save($_POST);
       
        /*hook end*/
        $this->msg('老师编辑成功！',1);
    }
	

	//老师删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('teacher')->del($_POST['id']);
        
        $this->msg('页面删除成功！',1);
    }

}