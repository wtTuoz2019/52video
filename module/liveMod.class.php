<?php
//field显示
class liveMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		//$this->getuserinfo();
    } 

    public function index() {
		
       $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 16;
       $title = trim($_GET['v']);
       $fieldlist;
	   $fieldlist['sid']=model('field')->field_array(2);
	   $fieldlist['gid']=model('field')->field_array(1);
	   $data['sid']=intval($_GET['sid']);
	  $data['gid']=intval($_GET['gid']);
	    if( $data['sid']!=66) $data['gid']=0;
	  $tid=intval($_GET['tid']);
	  $urltmp='?sid=%s&gid=%s';
	  $where=' status=1';
	   $wheretemp=' A.status=1';
	  if($tid){
	  	$where.=" AND tid=".$tid."";
			$wheretemp.=" AND A.tid=".$tid."";
	  }
      if($title){
        $where.=" AND title like '%".$title."%'";
		 $wheretemp.=" AND A.title like '%".$title."%'";
      }
      if ($cid) {
          $where.=" AND cid=".$cid."";
		   $wheretemp.=" AND A.cid=".$cid."";
      }
	
	  foreach($data as $k=>$v){
	  	if($v){
		if($k=='sid'&&$v==66){
			$where.=" AND (";
			$wheretemp.=" AND (";
		$i=0;
		foreach($fieldlist[$k] as $key=>$value){
			if($value['pid']==66){
				if($i>0){
					$where.=" or ";
					$wheretemp.=" or ";
					}
				$where.=" sid=".$value['id'];
					$wheretemp.=" sid=".$value['id'];
				$i++;
				}
				
			}
			$where.=" )";
			$wheretemp.=")";
			}else{
	  	$where.=" AND ".$k."=".$v."";
		$wheretemp.=" AND ".$k."=".$v."";
			}
			
        $url_.="&".$k."=".$v;
		}
			
			$url[$k][0]['name']='不限';
			$url[$k][0]['class']=$v==0?'cur':'';
			$i=1;
			
			if(is_array($fieldlist[$k])){	
			foreach($fieldlist[$k] as $key=>$value){
				$i++;	
				switch ($k) {
					case 'sid':
						 $url[$k][0]['url']=sprintf($urltmp,0,0);
						 $url[$k][$i]['url']=sprintf($urltmp,$value['id'],$data['gid']);
						$url[$k][$i]['name']=$value['name'];
						$url[$k][$i]['class']=$v==$value['id']?'cur':'';
						$url[$k][$i]['pid']=$value['pid'];
						$url[$k][$i]['id']=$value['id'];
						break;
					case 'gid':
						 $url[$k][0]['url']=sprintf($urltmp,$data['sid'],0);
						 $url[$k][$i]['url']=sprintf($urltmp,$data['sid'],$value['id']);
						$url[$k][$i]['name']=$value['name'];
						$url[$k][$i]['class']=$v==$value['id']?'cur':'';
						$url[$k][$i]['pid']=$value['pid'];
							$url[$k][$i]['id']=$value['id'];
						break;
				}}
				
			}
	  }
       $this->urlarray=$url;
	   
	 
            $listrows=6;
        //分页处理
        $url=__INDEX__.'/live/index/pages-{page}.html';
        $limit=$this->pagelimit($url,$listrows);

        $nav=array(
            0=>array('name'=>'field','url'=>__INDEX__.'/field/index'),
        );

        $this->info=array('name'=>'直播列表');

        //MEDIA信息
        $this->common=model('pageinfo')->media('直播列表',$field);
	
        //内容列表
        $loop=model('field')->video_list($wheretemp,$limit);
		
        //统计总内容数量
        $this->count=$count=model('field')->field_index_count($where);
        //分页处理
        $this->page=$this->page($url, $count, $listrows);
        //获取上一页代码
        $this->prepage=$this->page($url, $count, $listrows,'',1);
        //获取下一页代码
        $this->nextpage=$this->page($url, $count, $listrows,'',2);
        $this->assign('loop',$loop);
        $this->assign('nav',$nav);
        $this->display('list.html');  
    }


	public function info() {
        $field=urldecode($_GET['field']);
        if(!is_utf8($field))
        {
            $field=auto_charset($field,'gbk','utf-8');
        }
        
        $field = msubstr(in($field),0,20);
        //查找field信息
        if(!empty($field)){
            $info=model('field')->field_info($field);
        }else{
            $this->error404();
        }

        if(empty($info)){
            $this->error404();
        }

        //更新点击计数
        model('field')->views_content($info['id'],$info['click']);

        /*hook*/
        $this->plus_hook('field','index',$info);
        /*hook end*/

        //分页处理
        $url=__INDEX__.'/field-'.$field.'/pages-{page}.html';

        $listrows = $this->config['TPL_field_PAGE'];
        if(empty($listrows)){
            $listrows=20;
        }
        $limit=$this->pagelimit($url,$listrows);

        $nav=array(
            0=>array('name'=>'field','url'=>__INDEX__.'/field/index'),
            1=>array('name'=>$field,'url'=>__INDEX__.'/field-'.$field.'/'),
            );

        //MEDIA信息
        $this->common=model('pageinfo')->media($info['name'].' - field',$field);

        //内容列表
        $loop=model('field')->field_list($info['id'],$limit);

        //统计总内容数量
        $count=model('field')->field_count($info['id']);
        //分页处理
        $this->page=$this->page($url, $count, $listrows);
        //获取上一页代码
        $this->prepage=$this->page($url, $count, $listrows,'',1);
        //获取下一页代码
        $this->nextpage=$this->page($url, $count, $listrows,'',2);

		$this->assign('loop',$loop);
        $this->assign('nav',$nav);
        $this->assign('info', $info);
		$this->display();  
	}
}