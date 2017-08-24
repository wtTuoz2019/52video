<?php
class menulistMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $id = intval($_GET['id']);
        if (empty($id)) {
            $this->error404();
        }


        //读取栏目信息
        $this->info=model('web')->menu_info(array('id'=>$id));
		
        if (!is_array($this->info)){
            $this->error404();
        }
    
      	$whereuid=array('uid'=>$this->config['uid']);
        //位置导航
        $this->nav=array_reverse(model('web')->nav($this->info['id'],	$whereuid));

        //设置分页
        $size = intval($this->info['page']); 
        if (empty($size)) {
            $listrows = 10;
        } else {
            $listrows = $size;
        }
       
         $url=__INDEX__.'/menulis/index/id-'.$id.'-page-{page}.html'; 
        $limit=$this->pagelimit($url,$listrows);

        //设置栏目属性
        if ($this->info['pid'] == 0) {
            $son_id = model('web')->getcat($this->info['id'],	$whereuid);
			if($son_id)
            $where = 'A.status=1 AND A.mid in (' . $son_id . ') ';
			else
			 $where = 'A.status=1 AND A.mid=' . $this->info['id'];
        } else {
            $where = 'A.status=1 AND A.mid=' . $this->info['id'];
        }

		
        //执行查询
        $this->loop=model('web')->content_list($where,$limit);
		
		
	
        $count = model('web')->content_count($where);

        //查询上级栏目信息
        $this->parent_category = model('web')->menu_info(array('id'=>$this->info['pid']));
        if (!$this->parent_category) {
            $this->parent_category = array(
                "cid" => "0",
                "pid" => "0",
                "mid" => "0",
                "name" => "无上级栏目");
        }
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
		
		

        //MEDIA信息
        $this->common=model('pageinfo')->media($this->info['name']);
       
        //获取顶级栏目信息
        $this->top_category = model('web')->menu_info(array('id'=>$this->nav[0]['id']));
        $this->display('list.html');
    }
}