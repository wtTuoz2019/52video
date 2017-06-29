<?php
class weihomeMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function index(){
		  $listRows=9;
        $url = __URL__ . '/index/page-{page}.html'; //分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		if($_GET['s']){
			$where['name']=array('like',"'%".$_GET['s']."%'");
			} 
				 
		 if($this->user['gid']==6){
				$temp;$temp[]=0;
		
				$temp[]=$this->user['id'];
				
			$nextuser=model('user')->admin_list(' AND pid='.$this->user['id']);
			if($nextuser){
			foreach($nextuser as $key=>$val){
				$temp[]=$val['id'];
				}
			} 
		 	$where[]=" uid  in (".implode(',',$temp).") ";
			 }else{
		if($this->user['cid'])	
	 	$where[]=" uid =".$this->user['cid'];
			 }
			
		 $this->list=model('weihome')->index_list($where,$limit);
        $count=model('weihome')->count($where);
        $this->page=$this->page($url, $count, $listRows);
		 $this->show();
		}
		
	public function add(){
		$this->actionname='微首页添加';
		$this->action='add';
		$where=array('uid'=>$this->user['id'],'indexid'=>0);
		$p_menu=model('wechat')->get_p_menu($where);
		$this->assign('p_menu',$p_menu);
		 $this->show('weihome/info');
		}
	public function add_save(){
		
		$_POST['uid']= $this->user['id'];
		$_POST['token']= $this->user['token'];
		$_POST['time']=time();
		model('weihome')->add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function edit(){
		
		 $this->action_name='微首页编辑';
         $this->action='edit';
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$where=array('uid'=>$this->user['id'],'indexid'=>$id);
		$p_menu=model('wechat')->get_p_menu($where);
		$this->assign('p_menu',$p_menu);
		
		$this->info=model('weihome')->index_info(array('id'=>$id));	
		 $this->show('weihome/info');
		}
	public function edit_save(){
		
	
		$_POST['time']=time();
		model('weihome')->edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function del(){
		 $id=$_POST['id'];
      
        $this->alert_str($_POST['id'],'int',true);
        model('weihome')->del($id);
      
        $this->msg('删除成功！',1);
		
		}
	
}
?>