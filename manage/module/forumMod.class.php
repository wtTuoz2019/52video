<?php
//论坛管理
class forumMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }
	public function index(){
		 $url = __URL__ . '/index/page-{page}.html';
        $listRows = 20;
        $limit=$this->pagelimit($url,$listRows);
		$list=model('forum')->topics_list(array('configuid'=>$this->user['id']),$limit);
		if($list){
			foreach($list as $key=>$val){
				$list[$key]['zannum']=model('forum')->topics_zan_count(array('tid'=>$val['id']));
				
				if(unserialize($val['photos']))
				$list[$key]['photosnum']=count(unserialize($val['photos']));
				else
				$list[$key]['photosnum']=0;
				}
			
			}
		$this->list=$list;
		$count=model('forum')->topics_count(array('configuid'=>$this->user['id']));
		$this->page = $this->page($url, $count, $listRows);
		$this->show();
		}
	public function config(){
		
		$this->info= model('forum')->forum_config(array('token'=>$this->user['token']));
		
		
		
		$this->show();
		}
	public function config_save(){
		
		$_POST['token']=$this->user['token'];
		$_POST['uid']=$this->user['id'];
        model('forum')->forum_config_save($_POST);
       
        /*hook end*/
        $this->msg('配置成功！',1);
		}
	public function comment(){
		 $url = __URL__ . '/comment/page-{page}.html';
        $listRows = 20;
        $limit=$this->pagelimit($url,$listRows);
		$list=model('forum')->topics_comment_list(array('configuid'=>$this->user['id']),$limit);
	
		$this->list=$list;
		$count=model('forum')->topics_comment_count(array('configuid'=>$this->user['id']));
		$this->page = $this->page($url, $count, $listRows);
		$this->show();
		}
	public function topics_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('forum')->topics_del($id);
      
        $this->msg('删除成功！',1);
		
		}
	public function comment_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('forum')->comment_del($id);
      
        $this->msg('删除成功！',1);
		
		}
	public function topics_top(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('forum')->topics_top($id);
      
        $this->msg('操作成功！',1);
		
		}
	 public function topics_batch(){
        if(empty($_POST['status'])||empty($_POST['id'])){
            $this->msg('请先选择内容！',0);
        }
        $id_array=substr($_POST['id'],0,-1);
        $id_array=explode(',', $id_array);
        switch ($_POST['status']) {
            case '1':
                //审核
                foreach ($id_array as $value) {
                    model('forum')->comment_status($value);
                }
				break;
			 case '2':
                //审核
                foreach ($id_array as $value) {
                    model('forum')->comment_del($value);
                }
                break;
           
        }
        $this->msg('操作执行完毕！',1);

    }
	 public function comment_batch(){
        if(empty($_POST['status'])||empty($_POST['id'])){
            $this->msg('请先选择内容！',0);
        }
        $id_array=substr($_POST['id'],0,-1);
        $id_array=explode(',', $id_array);
        switch ($_POST['status']) {
            case '1':
                //审核
                foreach ($id_array as $value) {
                    model('forum')->comment_status($value);
                }
				break;
			 case '2':
                //审核
                foreach ($id_array as $value) {
                    model('forum')->topics_del($value);
                }
                break;
           
        }
        $this->msg('操作执行完毕！',1);

    }
	
}
?>