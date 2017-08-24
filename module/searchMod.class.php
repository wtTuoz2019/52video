<?php
class searchMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }
    
	public function index() {
       $keyword=urldecode($_GET['keyword']);
        if(!is_utf8($keyword))
        {
            $keyword=auto_charset($keyword,'gbk','utf-8');
        }
		$keyword = msubstr(in($keyword),0,10);
        $keywords = preg_replace ('/\s+/',' ',$keyword); 
        $keywords=explode(" ",$keywords);
        if(empty($keywords[0])){
        	$this->alert('没有关键词！');
        }

        /*hook*/
        $this->plus_hook('search','index',$keywords);
        /*hook end*/

        //获取栏目
//        $cid=model('category')->getcat(intval($_GET['cid']));
//        if($cid){
//            $where_cid=' B.cid in('.$cid.') AND ';
//        }

        //获取搜索模型
        $model=intval($_GET['model']);
        if(empty($model)){
        	$model=0;
        }
        //分页处理
        $url=__INDEX__.'/search/index/model-'.$model.'-keyword-'.urlencode($keyword).'-page-{page}.html';
        //$listrows = $this->config['TPL_SEARCH_PAGE'];
        if(empty($listrows)){
            $listrows=9;
        }
        $limit=$this->pagelimit($url,$listrows);

        //处理搜索字段
        $where='A.status=1 AND '.$where_cid;
        foreach ($keywords as $value) {
            switch ($model) {
                //标题+描述+关键词
                case '3':
                    $where2.= 'or A.mid= '.$value;
                    break;
                //标题+描述+关键词+全文
                case '2':
					$time=strtotime($value);$endtime=$time+24*60*60;
                    $where2.= 'or A.inputtime between '.$time.' and '.$endtime;
                    break;
                //标题
                default:
                    $where2.= 'or A.title like "%' . $value . '%" ';
                    break;
            }
        }
        $lang=model('lang')->langid();
        $where2='('.substr($where2,2).') AND B.lang='.$lang;
        //获取搜索列表
        $this->loop=model('search')->search_list($where.$where2,$limit,$model);
        //获取分页数
        $count=model('search')->search_count($where.$where2,$model);

        //导航
        $this->nav=array(
            0=>array('name'=>'搜索','url'=>''),
            1=>array('name'=>$keyword,'url'=>__INDEX__.'/search/index/model-'.$model.'-keyword-'.urlencode($keyword).'.html'),
            );

        //信息
        $info=array(
            'name'=>$keyword,
            'model'=>$model,
            );

        //MEDIA信息
        $this->common=model('pageinfo')->media($info['name'].' - 搜索',$keyword);

        $this->keyword=$keyword;
        $this->info=$info;
        $this->page=$this->page($url, $count, $listrows);
        //获取上一页代码
        $this->prepage=$this->page($url, $count, $listrows,'',1);
        //获取下一页代码
        $this->nextpage=$this->page($url, $count, $listrows,'',2);
        $this->count=$count;
        $this->display($this->config['TPL_SEARCH']);

	}


}