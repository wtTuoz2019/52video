<?php
class friendsMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
        $this->purview();
    }

    //判断基本权限
    public function purview() {
        if(!model('menu')->model_power('friends','visit')){
            $this->error('您暂时没有权限使用此功能！');
        }
    }

	public function index() {
		//分页处理
        $url = __URL__ . '/index/page-{page}.html'; //分页基准网址
        $listRows = 20;
        $limit=$this->pagelimit($url,$listRows);
        //内容列表
        $this->list=model('friends')->friends_list($limit,$this->user_info['uid']);
        //统计总内容数量
        $count=model('friends')->count($this->user_info['uid']);
        //分页处理
		$this->assign('page', $this->page($url, $count, $listRows));
		$this->show();  
	}

    public function add() {
        $fid=intval($_GET[0]);
        $this->alert_str($fid,'int');
        $info=model('user')->info($fid);
        
        if($info['uid']==$this->user_info['uid']){
            $this->alert('您无法将自己添加为好友！');
        }
        
        //重复好友
        if(model('friends')->info($this->user_info['uid'],$fid)){
            $this->alert('您已经添加过该好友，无法重复添加！');
        }
        
        $this->info=$info;
        $this->show(); 
    }

    public function add_data() {
        $data=in($_POST);
        $this->alert_str($data['fid'],'int',true);

        if($data['fid']==$this->user_info['uid']){
            $this->msg('您无法将自己添加为好友！',0);
        }
        
        //重复好友
        if(model('friends')->info($this->user_info['uid'],$data['fid'])){
            $this->msg('您已经添加过该好友，无法重复添加！',0);
        }

        model('friends')->add($data);
        $this->msg(__URL__.'/index.html');
    }

    public function edit() {
        $fid=intval($_GET[0]);
        $this->alert_str($fid,'int');
        if(!model('friends')->info($this->user_info['uid'],$fid)){
            $this->alert('该用户不是您的好友！');
        }
        $this->info=model('user')->info($fid);
        $this->show(); 

    }

    public function edit_data() {
        $data=in($_POST);
        $this->alert_str($data['fid'],'int',true);

        if(!model('friends')->info($this->user_info['uid'],$data['fid'])){
            $this->msg('该用户不是您的好友！',0);
        }

        model('friends')->edit($data);
        $this->msg(__URL__.'/index.html');
    }

    public function del() {
        $data=in($_POST);
        $this->alert_str($data['fid'],'int',true);
        model('friends')->del($this->user_info['uid'],$data['fid']);
        $this->msg('好友关系解除成功！');
    }




}