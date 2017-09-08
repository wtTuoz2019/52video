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
	 	 $keyword=urldecode($_GET['s']);
		if($keyword){
			  $where.= ' and A.title like "%' . $keyword . '%" ';
			 $url=__INDEX__.'/menulis/index/id-'.$id.'-s-'.urlencode($keyword).'-page-{page}.html'; 
			}
		
	  if($this->webconfig['template']=='res'){
		$dataarray=model('diyfield')->field_list_data(7);
	  	$this->cat =$cat= new Category(array('id', 'pid', 'name', 'cname'));
		
	  
	  $data['xueduan']['values']=$cat->getChild(0,$dataarray);
	  $data['xueduan']['select']=intval($_GET['xueduan'])?intval($_GET['xueduan']):$data['xueduan']['values'][0]['id'];
	 if($_GET['xueduan']){
		 $where .=' and xueduan='.$data['xueduan']['select'];
		  $url.='?xueduan='.$data['xueduan']['select'];
		 }
	  $data['kemu']['values']=$cat->getChild($data['xueduan']['select'],$dataarray);
	  $data['kemu']['select']=intval($_GET['kemu'])?intval($_GET['kemu']):$data['kemu']['values'][0]['id'];
	  if($_GET['kemu']){
		  
		   $where .=' and kemu='.$data['xueduan']['select'];
		     $url.='&kemu='.$data['kemu']['select'];
	  }
	  $data['banben']['values']=$cat->getChild($data['kemu']['select'],$dataarray);
	  $data['banben']['select']=intval($_GET['banben'])?intval($_GET['banben']):$data['banben']['values'][0]['id'];
	 if($_GET['banben']){
		  $where .=' and banben='.$data['banben']['select'];
		    $url.='&banben='.$data['banben']['select'];
	 }
	 $data['nianji']['values']=$cat->getChild($data['banben']['select'],$dataarray);
	  $data['nianji']['select']=intval($_GET['nianji'])?intval($_GET['nianji']):0;
	 
	   if($_GET['nianji']){
		   
		    $where .=' and nianji='.$data['nianji']['select'];
		    $url.='&nianji='.$data['nianji']['select'];
	   }
		   if(!$_GET['xueduan']){
			$this->noselect=true;
			$nianji=0; 
			}
		     $this->data=$data;
		   }
		 $limit=$this->pagelimit($url,$listrows);

				
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