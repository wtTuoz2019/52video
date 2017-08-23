<?php
class editor_file_manageMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    //KE文件JSON管理
    public function index() {
        



        $search=in(urldecode($_GET['search']));
        if(!empty($search)){
        $where[]=' name like "%' . $search . '%"';
        $where_url='search-'.urlencode($search);
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
		 	$where=" uid  in (".implode(',',$temp).") ";
			 }else{
		if($this->user['cid'])	
	 	$where=" uid =".$this->user['cid'];
			 }
			
	
			
		 $listRows=20;
        $url = __URL__ . '/video/'.$where_url.'-page-{page}.html'; //分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		$file_list=model('content')->video_list($where,$limit);
      	$count=model('content')->video_count($where);
		 $cur_page=$_GET['page'];
		 $totalPage=ceil($count/$listRows);
        if($cur_page>=$totalPage){
            $cur_page=$totalPage;
        }
        if($cur_page<=1){
            $cur_page=1;
        }
			
        $result = array();
        //相对于根目录的上一级目录
      
        //文件数
        $result['total_count'] = count($file_list);
        //文件列表数组
        $result['file_list'] = $file_list;

        //总页数
        $result['cur_page'] = $cur_page;

        //总页数
        $result['totalPage'] = $totalPage;



        $result['search'] = $search;
        
        
        @header("Content-type:text/html");
        echo json_encode($result);


    }

}

?>