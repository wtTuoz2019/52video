<?php
class collectionMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
        $this->purview();
    }

    //判断基本权限
    public function purview() {
        if(!model('menu')->model_power('collection','visit')){
            $this->error('您暂时没有权限使用此功能！');
        }
    }

	public function index() {
		//分页处理
        $url = __URL__ . '/index/page-{page}.html'; //分页基准网址
        $listRows = 6;
        $limit=$this->pagelimit($url,$listRows);
        //内容列表
        $this->list=model('collection')->friends_list($limit,$this->user_info['uid']);
        //统计总内容数量
        $count=model('collection')->count($this->user_info['uid']);
        //分页处理
		$this->assign('page', $this->page($url, $count, $listRows));
        $this->about = model('content')->about();
		$this->show();
	}

    public function content() {
        $this->redirect(ROOTAPP.'/label/admin_aurl.html?aid='.$_GET[0]);
    }

    public function add() {
        $aid=intval($_GET[0]);
        $this->alert_str($aid,'int');
        $info=model('user')->info($aid);
        
        //重复收藏
        if(model('collection')->info($this->user_info['uid'],$aid)){
            $this->alert('您已经收藏过该内容，无法重复收藏！');
        }
        
        $this->info=model('collection')->content($aid);
        if(!$this->info){
            $this->alert('没有发现该内容信息！');
        }
        $this->show(); 
    }

    public function add_data() {
        $data=in($_POST);
        $this->alert_str($data['aid'],'int');
        
        //重复收藏
        if(model('collection')->info($this->user_info['uid'],$data['aid'])){
            $this->msg('您已经收藏过该内容，无法重复收藏！',0);
        }

        $info=model('collection')->content($data['aid']);
        if(!$this->info){
            $this->msg('没有发现该内容信息！',0);
        }

        model('collection')->add($data);
        $this->msg(__URL__.'/index.html');
    }


    public function del() {
        $data=in($_POST);
        $this->alert_str($data['aid'],'int',true);
        model('collection')->del($this->user_info['uid'],$data['aid']);
        $this->msg('已删除该收藏！');
    }




}