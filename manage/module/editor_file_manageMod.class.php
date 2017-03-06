<?php
class editor_file_manageMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    //KE文件JSON管理
    public function index() {
        
        
//        $video='/opt/adobe/ams/webroot/vod/';
//        $handle = opendir($video);
//        
//        $file_list = array();
//        $key=0;
//         while (false !== ($file = readdir($handle))) {
//        if ($file != "." && $file != "..") {
//          
//                $file_list[$key]['is_dir']=false;
//                $file_list[$key]['has_file']=false;
//                $file_list[$key]['filesize']=filesize($video.$file);
//                $file_list[$key]['dir_path']='';
//                $file_list[$key]['is_photo']=true;
//                $file_list[$key]['filetype'] = get_extension($video.$file);
//                $file_list[$key]['filename'] = iconv('GB2312', 'UTF-8', $file);
//                $file_list[$key]['filedir'] = '/opt/adobe/ams/webroot/vod/'.iconv('GB2312', 'UTF-8', $file);
//                $file_list[$key]['datetime'] = fileatime($video.$file);
//                $key++;
//             }
//         }
//   closedir($handle);
    //var_dump($file_list);
//      die;
		if($this->user['gid']!=1)
			$where['uid']= $this->user['id'];
		$file_list=model('content')->video_list($where);
        $order=in($_GET['order']);
        if(!empty($order)){
            $where='type="'.$order.'"';
        }

        $search=in(urldecode($_GET['search']));
        if(!empty($search)){
            $where=' title like "%' . $search . '%"';
            $url_search='-search-'.$search;
        }
        

    $url = __URL__ . '/index.html?order='.$order.'&page={page}&search='.$url_search; //分页基准网址
//
    $cur_page=$_GET['page'];
//
        $count=count($file_list);;
        $listRows = 20;
//
       $totalPage=ceil($count/$listRows);
        if($cur_page>=$totalPage){
            $cur_page=$totalPage;
        }
        if($cur_page<=1){
            $cur_page=1;
        }
      $limit=$this->pagelimit($url,$listRows);
        

        $result = array();
        //相对于根目录的上一级目录
        $result['moveup_dir_path'] = '';
        //相对于根目录的当前目录
        $result['current_dir_path'] = '';
        //当前目录的URL
        $result['current_url'] =    '';
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