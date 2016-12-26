<?php
class messageMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
        $this->purview();
    }

    //判断基本权限
    public function purview() {
        if(!model('menu')->model_power('message','visit')){
            $this->error('您暂时没有权限使用此功能！');
        }
    }

	public function index() {
		$action=in($_GET['action']);
		switch ($action) {
			case 'system':
				$where='A.uid='.$this->user_info['uid'].' AND A.system=1'.' AND A.to_del=0';
				break;
			case 'post':
				$where='A.uid='.$this->user_info['uid'].' AND A.del=0';
				break;
			default:
				$where='A.to_uid='.$this->user_info['uid'].' AND A.to_del=0';
				break;
		}
		//分页处理
        $url = __URL__ . '/index/page-{page}.html'; //分页基准网址
        $listRows = 20;
        $limit=$this->pagelimit($url,$listRows);
        //内容列表
        $this->list=model('message')->message_list($limit,$where);
        //统计总内容数量
        $count=model('message')->count($where);
        //分页处理
		$this->assign('page', $this->page($url, $count, $listRows));
		$this->show();  
	}

	public function info() {
		$mid=intval($_GET[0]);
		$this->alert_str($mid,'int');
		$info=model('message')->info($mid);
		if($info['uid']<>$this->user_info['uid']&&$info['to_uid']<>$this->user_info['uid']){
			$this->alert('您没有该信息的查看权利！');
		}
		if($info['view']<>1){
			model('message')->view($info['mid']);
		}
		$this->info=$info;
		$this->post_user=model('user')->info($info['uid']);

		$this->show(); 
	}

	
	public function post() {
		$this->show(); 
	}

	public function post_data() {
		$data=in($_POST);
		$this->alert_str($data['title'],'',true);
		$this->alert_str($data['content'],'',true);
		//查找用户信息
		$user=model('user')->repeat($data['username']);
		if(!$user){
			$this->msg('收件人填写错误，请重新填写！',0);
		}
		if($user['uid']==$this->user_info['uid']){
			$this->msg('您不能给自己发送信息！',0);
		}
		if(!model('message')->post_time($this->user_info['uid'])){
			$this->msg('您在一分钟内无法再次发送信息！',0);
		}
		$data['to_uid']=$user['uid'];
		$data['system']=0;
		model('message')->add($data);
		$this->msg(__URL__.'/index.html');


	}

	public function del() {
		$mid=intval($_POST['mid']);
		$this->alert_str($mid,'int',true);
		$info=model('message')->info($mid);
		if($info['uid']<>$this->user_info['uid']&&$info['to_uid']<>$this->user_info['uid']){
			$this->msg('您无法删除该条信息！',0);
		}

		if($_POST['action']=='post'){
			$action='del';
		}else{
			$action='to_del';
		}
		model('message')->del($mid,$action);
		$this->msg('删除该条信息成功！');
	}


	//批量操作
    public function batch_del() {
        $this->alert_str($_POST['mid'],'',true);
        $id_array=substr($_POST['mid'],0,-1);
        $id_array=explode(',', $id_array);

        if($_POST['action']=='post'){
			$action='del';
		}else{
			$action='to_del';
		}

        foreach ($id_array as $value) {
        	$info=model('message')->info($value);
        	if($info['uid']<>$this->user_info['uid']&&$info['to_uid']<>$this->user_info['uid']){
				$this->msg('您无法删除该条信息！',0);
			}
            model('message')->del($value,$action);
        }
        $this->msg('tag删除成功！',1);
        
    }

}