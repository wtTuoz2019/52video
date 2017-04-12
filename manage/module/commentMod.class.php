<?php
//评论管理
class commentMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	 //评论列表
    public function index()
    {
        $url = __URL__ . '/index/page-{page}'.$url_type.$url_ext.$url_search; //分页基准网址
        $listRows = 10;
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        $limit_start = ($cur_page - 1) * $listRows;
        $limit = $limit_start . ',' . $listRows;
		
		 $user=model('user')->current_user();
		if($user['cid'])	
		$whereuid=" AND A.csid =".$user['cid'];
		$aids= model('content')->getaids($whereuid);
		$where=' fid in ('.$aids.')';
		
        
        $count=model('comment')->count($where);
        $this->assign('page', $this->page($url, $count, $listRows));
		
        $this->list=model('comment')->model_list($limit,$where);
        $this->subject=model('diyfield')->field_list_data(2);
        $this->grade=model('diyfield')->field_list_data(1);

        $this->show();
    }
	 //评论列表
    public function person()
    {	
		$this->actionname='评论列表';
	$this->id=$aid=isset($_GET['id']) ? intval($_GET['id']) : 0;
        $url = __URL__ . '/person/id-'.$aid.'page-{page}'; //分页基准网址
        $listRows = 10;
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        $limit_start = ($cur_page - 1) * $listRows;
        $limit = $limit_start . ',' . $listRows;
		
		
		$where=' fid ='.$aid;
		
        
        $count=model('comment')->count($where);
        $this->assign('page', $this->page($url, $count, $listRows));
		
        $this->list=model('comment')->model_list($limit,$where);
        $this->subject=model('diyfield')->field_list_data(2);
        $this->grade=model('diyfield')->field_list_data(1);
		
		
		if(isset($_GET['download'])){
				$list=model('comment')->get_list($where);
				foreach($list as $k=>$v){
					
					$list[$k]['name']=model('comment')->info_user($v['uid']);
					
					}
					
				
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=employee.xls");
		echo iconv('utf-8','gbk','评论')."\t";
		echo iconv('utf-8','gbk','评论人')."\t";
		
		echo iconv('utf-8','gbk','时间')."\n";
		foreach($list as $k=>$v){
		echo iconv('utf-8','gbk',str_replace("\n","",$v['message']))."\t";
		echo iconv('utf-8','gbk',$v['name'])."\t";
		
		echo iconv('utf-8','gbk',date("Y/m/d H:i",$v['time']))."\n";
		
		}
			die;
			}
		
        $this->show();
    }
	
	 //评论列表
    public function selfform()
    {	
		$this->actionname='评论调查';
	$this->id=$aid=isset($_GET['id']) ? intval($_GET['id']) : 0;
        $url = __URL__ . '/selfform/id-'.$aid.'page-{page}'; //分页基准网址
        $listRows = 10;
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        $limit_start = ($cur_page - 1) * $listRows;
        $limit = $limit_start . ',' . $listRows;
		
		
		$where=' fid ='.$aid;
		
        
        $count=model('comment')->count($where);
        $this->assign('page', $this->page($url, $count, $listRows));
		
        $this->list=model('comment')->model_list($limit,$where);
        $this->subject=model('diyfield')->field_list_data(2);
        $this->grade=model('diyfield')->field_list_data(1);
		
		
		if(isset($_GET['download'])){
				$list=model('comment')->get_list($where);
				foreach($list as $k=>$v){
					
					$list[$k]['name']=model('comment')->info_user($v['uid']);
					
					}
					
				
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=employee.xls");
		echo iconv('utf-8','gbk','评论')."\t";
		echo iconv('utf-8','gbk','评论人')."\t";
		
		echo iconv('utf-8','gbk','时间')."\n";
		foreach($list as $k=>$v){
		echo iconv('utf-8','gbk',str_replace("\n","",$v['message']))."\t";
		echo iconv('utf-8','gbk',$v['name'])."\t";
		
		echo iconv('utf-8','gbk',date("Y/m/d H:i",$v['time']))."\n";
		
		}
			die;
			}
		
        $this->show();
    }
	
	
    //评论添加
    public function add()
    {
        $this->action_name='添加';
        $this->action='add';
      $this->subject=model('diyfield')->field_list_data(2);
		$this->grade=model('diyfield')->field_list_data(1);

        $this->display('comment/info');
    }
  //评论保存
    public function add_save()
    {
        

        model('comment')->save($_POST);
       
        /*hook end*/
        $this->msg('评论添加成功！',1);
    }

     //评论添加
    public function edit()
    {   $this->id=$id=isset($_GET['id']) ? intval($_GET['id']) : 0;
		 $this->fid=$fid=isset($_GET['fid']) ? intval($_GET['fid']) : 0;
		 if(!$id||!$fid){
			 $this->error('不可操作');
			 }
        $this->action_name='转移';
        $this->action='edit';
     
        $this->info=model('comment')->info($id);
		$article=model('content')->info($fid);
		
		$this->list=model('content')->getlist($article['aids']);
        $this->display('comment/info');
    }
  //评论保存
    public function edit_save()
    {
        

     echo    model('comment')->edit_save($_POST);die;
       
        /*hook end*/
        $this->msg('评论编辑成功！',1);
    }
	

	//评论删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('comment')->del($_POST['id']);
        if($class_status)model('comment')->delres($_POST['id']);
        $this->msg('页面删除成功！',1);
    }

        //评论审核
    public function examine()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('comment')->examine($_POST['id']);
        
        $this->msg('评论审核成功！',1);
    }
	
	    //批量删除
    public function moredeldel()
    {
        $data = $_POST['id'];
        foreach ($data as $k => $v) {
            $class_status=model('comment')->del($data[$k]);
        }
        $this->msg('页面删除成功！',1);
    }
	
	  //批量操作
    public function batch(){
        if(empty($_POST['status'])||empty($_POST['id'])){
            $this->msg('请先选择内容！',0);
        }
        $id_array=substr($_POST['id'],0,-1);
        $id_array=explode(',', $id_array);
        switch ($_POST['status']) {
            case '1':
                //审核
                foreach ($id_array as $value) {
					model('comment')->examine($value);
                  
                }
                break;
            case '2':
                //草稿
                foreach ($id_array as $value) {
                  model('comment')->del($value);
                }
                break;
            case '3':
                //删除
                foreach ($id_array as $value) {
                    /*hook*/
                    $this->plus_hook('content','del',$value);
                    /*hook end*/
                    model('content')->del($value);
                    model('content')->del_content($value);
                }
                break;
            case '4':
                //转移栏目
                $cid=intval($_POST['cid']);
                if(empty($cid)){
                    $this->msg('请先选择目标栏目！',0);
                }
                foreach ($id_array as $value) {
                    model('content')->edit_cid($value,intval($_POST['cid']));
                }
                break;
        }
        $this->msg('操作执行完毕！',1);

    }


	
	



}