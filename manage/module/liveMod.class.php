<?php
class liveMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
		
		$this->webconfig= model('web')->web_config(array('token'=>$this->user['token']));
		if($this->webconfig['isopen']){
		$this->menulist=model('web')->menu_list(array('uid'=>$this->user['id'],'type'=>'cid'));	
		if($this->user['gid']==6){
		if($_POST['mid']){
			$_POST['gmid']=$_POST['mid'];
			unset($_POST['mid']);
			}
		}
			}
    }

    //公共列表信息
    public function common_list_where()
    {
        //排序
        $sequence=intval($_GET['sequence']);
        switch ($sequence) {
            case '1':
                $order='A.updatetime DESC';
                $where_url='1';
                break;
            case '2':
                $order='A.updatetime ASC';
                $where_url='2';
                break;
            case '3':
                $order='A.aid DESC';
                $where_url='3';
                break;
            case '4':
                $order='A.aid ASC';
                $where_url='4';
                break;
            case '5':
                $order='A.inputtime DESC';
                $where_url='5';
                break;
            case '6':
                $order='A.inputtime ASC';
                $where_url='6';
                break;
            case '7':
                $order='A.views DESC';
                $where_url='7';
                break;
            case '8':
                $order='A.views ASC';
                $where_url='8';
                break;
        }
        if(!empty($order)){
        $order=$order.',';
        $where_url='-sequence-'.$where_url;
        }

        //状态
        $status=intval($_GET['status']);
        switch ($status) {
            case '1':
                $where=' AND A.status=1';
                $where_url='1';
                break;
            case '2':
                $where=' AND A.status=0';
                $where_url='2';
                break;
        }
        if(!empty($status)){
        $where_url='-status-'.$where_url;
        }

        //搜索
        $search=in(urldecode($_GET['search']));
        if(!is_utf8($search)){
            $search=auto_charset($search);
        }
        if(!empty($search)){
        $where=' AND (A.title like "%' . $search . '%") or (A.aid ="'.$search.'")';
        $where_url='-search-'.urlencode($search);
        }

        //推荐位
        $position=intval($_GET['position']);
        if(!empty($position)){
            $where=' AND D.pid='.$position;
            $where_url='-position-'.$position;
        }
        return array(
            'order'=>$order,
            'where'=>$where,
            'url'=>$where_url
            );

    }
    public function common_list()
    {
        $position_list=model('position')->position_list();
        $category_list=model('category')->category_list();
        $model_info=module('live_category')->get_model();
        $data['position_list']=$position_list;
        $data['category_list']=$category_list;
        $data['model_info']=$model_info;
        //权限部分
        if(model('user_group')->model_power('live','past')){
            $data['past_power']=true;
        }
        if(model('user_group')->model_power('live','cancel')){
            $data['cancel_power']=true;
        }
        if(model('user_group')->model_power('live','del')){
            $data['del_power']=true;
        }
        if(model('user_group')->model_power('live','edit')){
            $data['edit_power']=true;
        }
        return $data;
    }

    //公共调用信息
    public function common_info($id,$status=false)
    {
        if($status){
            $info=model('live')->info($id);
			if($this->user['gid']==6){
		$info['mid']=$info['gmid'];
		}
            $info_data=model('live')->info_content($id);
            $position_array=model('position')->relation_array($id);
            $file_id=model('upload')->get_relation('content',$id);
			$functions=model('content')->functions_list(array('aid'=>$id));
            $cid=$info['cid'];
            $action_name='编辑';
            $action='edit';
        }else{
            $cid=$id;
            $action_name='添加';
            $action='add';
        }
        $class_info = model('category')->info($cid);
        $category_list=model('category')->category_list();
        $subject=model('diyfield')->field_list(2);
		$grade=model('diyfield')->field_list(1);
		$user=$this->user;
		$uid=$user['id'];
		if($user['gid']==6){
			$temp;
			$temp[]=1;
			if($user['cid']){
				$temp[]=$user['id'];
				}
			$nextuser=model('user')->admin_list(' AND pid='.$user['id']);
			if($nextuser){
			foreach($nextuser as $key=>$val){
				$temp[]=$val['id'];
				}
			}
			
			if($temp){
			 	$where='B.id in ('.implode(',',$temp).') ';	
				}
			}else{
		
			
			if($user['cid']){
			$where='B.id='.$uid;	
				}
		
     
			}
		$teacher=model('teacher')->model_list();
		
		   $channel = model('device')->channel_list($where);
	
		
        $position_list=model('position')->position_list();
        $tpl_list=model('category')->tpl_list();

        $data['info']=$info;
        $data['info_data']=$info_data;
		$data['zidingyi']=unserialize($info_data['zidingyi']);
		$data['info']['field_lists']=unserialize($info['field_lists']);
		if(!is_array($data['info']['field_lists'])){
			$data['info']['field_lists']=array();
			}
		$data['info']['auditfield_lists']=unserialize($info['auditfield_lists']);
		if(!is_array($data['info']['auditfield_lists'])){
			$data['info']['auditfield_lists']=array();
			}
		 $data['functions']=$functions;
        $data['position_array']=$position_array;
        $data['file_id']=$file_id;
        $data['action_name']=$action_name;
        $data['action']=$action;
        $data['class_info']=$class_info;
        $data['category_list']=$category_list;
        $data['subject']=$subject;
		$data['grade']=$grade;
		$data['school']=$school;
		$data['teacher']=$teacher;
        $data['channel']=$channel;
        $data['position_list']=$position_list;
        $data['tpl_list']=$tpl_list;

        return $data;
    }

    //公共保存检测信息
    public function common_data_check($data)
    {
        model('expand_model')->content_check($data);
    }


    //获取关键词
    public function get_keyword(){
        
        $title=$_POST['title'];
        $content=$_POST['live'];
        $keyword=model('live')->get_keyword($title,$content);
        if(!empty($keyword)){
            $this->msg($keyword);
        }else{
            $this->msg('暂时无法获取到关键词！',0);
        }
    }

    // 内容列表
    public function index()
    {
		
		
		if(!$_GET['id']){
			
			$id=16;
		
		}else{
			$id=intval($_GET['id']);
		
		}
		if($id==16){
				$this->actionname='直播列表';
			}else{
					$this->actionname='回看列表';
				}
		$this->assign('cateid',$id);
		$this->school=model('school')->school_list();
		//所有栏目信息
		
		$cate=$this->model->table('category')->where(array('show'=>1))->select();
		
		$this->assign('cate',$cate);
        $this->alert_str($id,'int');
        //获取公共信息条件
        $where=$this->common_list_where();
        $this->view()->assign($this->common_list());
        //栏目信息
        $this->class_info = model('category')->info($id);
        //分页信息
        $listRows=9;
        $url = __URL__ . '/index/id-' . $id . '-page-{page}'.$where['url'].'.html'; //分页基准网址
        $limit=$this->pagelimit($url,$listRows);
        //内容列表
        $this->list=model('live')->content_list($id,$limit,$where['where'],$where['order']);
		
        $count=model('live')->count($id,$where['where']);
        $this->page=$this->page($url, $count, $listRows);
		
        $this->show();
		
    }
	public function teacher(){
		$data=$_POST;
		if($data['sid']){
			$where['sid']=$data['sid'];
			}
		if($data['gid']){
			$where['gid']=$data['gid'];
			}
		   $list=model('teacher')->model_list($where);
		; 

		echo  json_encode($list);
		}
    public function common_data_info()
    {
        $model_info=module('content_category')->get_model();
        if(!empty($model_info['befrom'])){
            $befrom=explode("\n",$model_info['befrom']);
            foreach ($befrom as $value) {
                $befrom_list[]=$value;
            }
        }
        $data['model_info']=$model_info;
        $data['befrom_list']=$befrom_list;
		
        return $data;
    }

    //内容添加
    public function add()
    {	$this->actionname='直播发布';
        $cid=intval($_GET['cid']);
		$user=model('user')->current_user();
		
		if($user['overtime']&&$user['overtime']<time()){
         $this->alert('直播服务已到期! 请联系管理员!');
       		 }
        $this->alert_str($cid,'int');
        $this->view()->assign($this->common_info($cid));
        $this->view()->assign($this->common_data_info($cid));
		$this->field_list=model('form')->field_list(4);
        $this->show('live/info');
    }

    //内容保存
    public function add_save()
    {	
        /*hook*/
        $_POST=$this->plus_hook_replace('live','add_replace',$_POST);
        /*hook end*/
        $this->common_data_check($_POST);
		$user=model('user')->current_user();
		$_POST['uid']=$user['id'];
		if($_POST['channel']){
			$_POST['csid']=model('device')->getcsid($_POST['channel']);
			}
	if(!$_POST['csid']){
			$this->msg('学校通道必选！',0);
			}
			$_POST['field_lists']=serialize($_POST['field_lists']);
			$_POST['auditfield_lists']=serialize($_POST['auditfield_lists']);
    	//保存内容信息
		
		
    	$_POST['aid']=model('live')->add_save($_POST);
    	model('live')->add_content_save($_POST);
        /*hook*/
        $this->plus_hook('live','add',$_POST);
        /*hook end*/
    	$this->msg('内容添加成功！',1);
    }

    //内容编辑
    public function edit()
    {	$this->actionname='直播编辑';
        $id=intval($_GET['id']);
        $this->alert_str($id,'int');
        $this->view()->assign($this->common_info($id,true));
        $this->view()->assign($this->common_data_info($cid));
		$this->field_list=model('form')->field_list(4);
		
        $this->show('live/info');
    }

    //内容保存
    public function edit_save()
    {
        /*hook*/
        $_POST=$this->plus_hook_replace('live','edit_replace',$_POST);
        /*hook end*/
        $this->common_data_check($_POST);
        //保存内容信息
		$_POST['field_lists']=serialize($_POST['field_lists']);
		$_POST['auditfield_lists']=serialize($_POST['auditfield_lists']);
		if($_POST['channel']){
			$_POST['csid']=model('device')->getcsid($_POST['channel']);
			}
		if(!$_POST['csid']){
			$this->msg('学校通道必选！',0);
			}
	
        $status=model('live')->edit_save($_POST);
        model('live')->edit_content_save($_POST);
		$time= $_POST['starttime'];
		$aid = $_POST['aid'];
		$time = strtotime($_POST['starttime']);
        $t = $_POST['time'];
		
		model('live')->edit_starttime($t, $time, $aid);
        /*hook*/
        $this->plus_hook('live','edit');
        /*hook end*/
        $this->msg('内容编辑成功！',1);
    }

    //内容删除
    public function del()
    {
        $id=intval($_POST['aid']);
        $this->alert_str($id,'int',true);
        /*hook*/
        $this->plus_hook('content','del',$id);
        /*hook end*/
        $status=model('live')->del($id);
        model('live')->del_content($id);
        $this->msg('内容删除成功！',1);
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
                    model('live')->status($value,1);
                }
                break;
            case '2':
                //草稿
                foreach ($id_array as $value) {
                    model('live')->status($value,0);
                }
                break;
            case '3':
                //删除
                foreach ($id_array as $value) {
                    /*hook*/
                    $this->plus_hook('live','del',$value);
                    /*hook end*/
                    model('live')->del($value);
                    model('live')->del_content($value);
                }
                break;
            case '4':
                //转移栏目
                $cid=intval($_POST['cid']);
                if(empty($cid)){
                    $this->msg('请先选择目标栏目！',0);
                }
                foreach ($id_array as $value) {
                    model('live')->edit_cid($value,intval($_POST['cid']));
                }
                break;
        }
        $this->msg('操作执行完毕！',1);

    }
	
	
	
	
	public function downpic(){
	 	$fileurl=$_GET['pic'];
		
header('Content-type:  image/png'); 
header("Content-Disposition: attachment; filename=erweima.png"); 
readfile('../'.$fileurl);
exit;
		}
    




}

?>