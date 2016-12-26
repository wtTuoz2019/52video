<?php
class homeMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		$uid=intval($_GET[0]);
		if(empty($uid)){
			$this->error404();
		}
		
        $this->info=model('user')->info($uid);

        //设置分页
        $size = 10; 
        if (empty($size)) {
            $listrows = 10;
        } else {
            $listrows = $size;
        }
       
        $limit=$this->pagelimit($url,$listrows);

       
           $where = 'A.status=1 AND A.uid='.$uid;
       

        //执行查询
        $this->loop=model('category')->content_list($where,$limit);
        $count = model('category')->content_count($where);

        //获取分页
        $this->page=$this->page($url, $count, $listrows);
		
        //获取上一页代码
        $this->prepage=$this->page($url, $count, $listrows,'',1);
        //获取下一页代码
        $this->nextpage=$this->page($url, $count, $listrows,'',2);
        $page = new Page();
		$page->show($url, $totalRows, $listRows, $rollPage);
		
	
		$this->pagenum=$this->pagenum($url);
        $this->count=$count;
	   	$this->pagenumber=(int)($count/$listrows)+1;
		
		

		$this->show();
	}


}