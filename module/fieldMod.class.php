<?php
//field显示
class fieldMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

    public function index() {
       $title = trim($_GET['v']);
       $fieldlist;
	   $fieldlist['sid']=model('field')->field_array(2);
	   $fieldlist['gid']=model('field')->field_array(1);
	   $data['sid']=intval($_GET['sid']);
	  $data['gid']=intval($_GET['gid']);
	 
	  
	  $tid=intval($_GET['tid']);
	  $urltmp='?sid=%s&gid=%s';
	  $where=' status=1';
	  $wheretemp=' A.status=1';
	  if($tid){
	  	$where.=" AND tid=".$tid."";
		$wheretemp.=" AND tid=".$tid."";
	  }
      if($title){
        $where.=" AND title like '%".$title."%'";
		$wheretemp.=" AND title like '%".$title."%'";
      }else{
        $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 13;
      }
      if ($cid) {
          $where.=" AND cid=".$cid."";
		   $wheretemp.=" AND cid=".$cid."";
      }
	  foreach($data as $k=>$v){
	  	if($v){
		if($k=='sid'&&$v==66){
			$where.=" AND (";
			$wheretemp.=" AND (";
		$i=0;
		 if( $data['sid']!=66) $data['gid']=0;
		foreach($fieldlist[$k] as $key=>$value){
			if($value['pid']==66){
				if($i>0){
					$where.=" or ";
					$wheretemp.=" or ";
					}
				$where.=" sid=".$value['id'];
				$wheretemp.=" sid=".$value['id'];
				$i++;
				 $data['gid']=intval($_GET['gid']);
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
        $url=__INDEX__.'/field/index/pages-{page}.html?'.$url_; 
        $limit=$this->pagelimit($url,$listrows);
        $nav=array(
            0=>array('name'=>'field','url'=>__INDEX__.'/fields/index'),
        );

        $this->info=array('name'=>'重播列表');

        //MEDIA信息
        $this->common=model('pageinfo')->media('重播列表',$field);
	
        //内容列表
        $loop=model('field')->field_index_list($where,$limit);
	

        //统计总内容数量
       $this->count= $count=model('field')->field_index_count($where);
        //分页处理
        $this->page=$this->page($url, $count, $listrows);
        //获取上一页代码
        $this->prepage=$this->page($url, $count, $listrows,'',1);
        //获取下一页代码
        $this->nextpage=$this->page($url, $count, $listrows,'',2);

        $this->assign('loop',$loop);
        $this->assign('nav',$nav);
        $this->display('field.html');  
    }
	
	
	  public function school() {
       $title = trim($_GET['s']);
       $fieldlist;
	   $fieldlist['sid']=model('field')->field_array(2);
	   $fieldlist['gid']=model('field')->field_array(1);
	   $data['sid']=intval($_GET['sid']);
	  $data['gid']=intval($_GET['gid']);
	  if( $data['sid']!=66) $data['gid']=0;
	  $this->csid=$csid=intval($_GET['csid']);
	  $urltmp='?sid=%s&gid=%s&csid='.$csid;
	  $where=' status=1';
	  $wheretemp=' A.status=1';
	  if($csid){
	  	$where.=" AND csid=".$csid."";
		$wheretemp.=" AND csid=".$csid."";
		$url_.="&csid=".$csid;
	  }
	  if($title){
        $where.=" AND title like '%".$title."%'";
		$wheretemp.=" AND title like '%".$title."%'";
      }
	  
	  if(!$_GET['type']){
		  
		  $this->sninfo=$sninfo=model('data')->sn_info('B.cid='.$csid);
		 if($sninfo){
			$videourl='http://'.$this->config['ali'].$sninfo['sn'].'.m3u8';
			$this->flag=@fopen($videourl,'r'); 
			 }
		  }
	  
    	$this->allurl=sprintf($urltmp,$data['sid'],$data['gid']);
		$this->fieldurl=sprintf($urltmp,$data['sid'],$data['gid'])."&cid=13";
		$this->liveurl=sprintf($urltmp,$data['sid'],$data['gid'])."&cid=16";
	
      $this->cid=  $cid = intval($_GET['cid']) ;
     
      if ($cid) {
          $where.=" AND cid=".$cid."";
		   $wheretemp.=" AND cid=".$cid."";
		  $url_.="&cid=".$cid;
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
						 $url[$k][0]['url']=sprintf($urltmp,0,$data['gid']);
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
        $url=__INDEX__.'/field/school/type-video-pages-{page}.html?'.$url_; 
        $limit=$this->pagelimit($url,$listrows);
        $nav=array(
            0=>array('name'=>'field','url'=>__INDEX__.'/fields/index'),
        );
		//$this->school=$school=model('school')->info($csid);

        //MEDIA信息
        $this->common=model('pageinfo')->media($school['name'],$field);
	
        //内容列表
        $loop=model('field')->field_index_list($where,$limit);

        //统计总内容数量
       $this->count= $count=model('field')->field_index_count($where);
        //分页处理
        $this->page=$this->page($url, $count, $listrows);
        //获取上一页代码
        $this->prepage=$this->page($url, $count, $listrows,'',1);
        //获取下一页代码
        $this->nextpage=$this->page($url, $count, $listrows,'',2);

        $this->assign('loop',$loop);
        $this->assign('nav',$nav);
		
        $this->display('school'.$_GET['type'].'.html');  
    }
	 public function teacher() {
       $title = trim($_GET['s']);
   
	  $this->tid=$tid=intval($_GET['tid']);
	  $urltmp='?sid=%s&gid=%s';
	  $where=' status=1';
	  $wheretemp=' A.status=1';
	  if($tid){
	  	$where.=" AND tid=".$tid."";
		$wheretemp.=" AND tid=".$tid."";
		  $url_.="&tid=".$tid;
	  }
    
      $this->cid=$cid = intval($_GET['cid']);
      
      if ($cid) {
          $where.=" AND cid=".$cid."";
		   $wheretemp.=" AND cid=".$cid."";
		   $url_.="&cid=".$cid;
      }
	  
	     if($title){
        $where.=" AND title like '%".$title."%'";
		$wheretemp.=" AND title like '%".$title."%'";
      }
	  
	 
        $listrows=6;
        //分页处理
        $url=__INDEX__.'/field/teacher/pages-{page}.html?'.$url_; 
        $limit=$this->pagelimit($url,$listrows);
        $nav=array(
            0=>array('name'=>'field','url'=>__INDEX__.'/fields/index'),
        );

        $this->info=array('name'=>'老师主页');

        //MEDIA信息
        $this->common=model('pageinfo')->media('老师主页',$field);
	
        //内容列表
        $loop=model('field')->field_index_list($where,$limit);

        //统计总内容数量
       $this->count= $count=model('field')->field_index_count($where);
        //分页处理
        $this->page=$this->page($url, $count, $listrows);
        //获取上一页代码
        $this->prepage=$this->page($url, $count, $listrows,'',1);
        //获取下一页代码
        $this->nextpage=$this->page($url, $count, $listrows,'',2);

        $this->assign('loop',$loop);
        $this->assign('nav',$nav);
        $this->display('teacher.html');  
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
            $info=model('fields')->field_info($field);
        }else{
            $this->error404();
        }

        if(empty($info)){
            $this->error404();
        }

        //更新点击计数
        model('fields')->views_content($info['id'],$info['click']);

        /*hook*/
        $this->plus_hook('fields','index',$info);
        /*hook end*/

        //分页处理
        $url=__INDEX__.'/fields-'.$field.'/pages-{page}.html';

        $listrows = $this->config['TPL_fieldS_PAGE'];
        if(empty($listrows)){
            $listrows=20;
        }
        $limit=$this->pagelimit($url,$listrows);

        $nav=array(
            0=>array('name'=>'field','url'=>__INDEX__.'/fields/index'),
            1=>array('name'=>$field,'url'=>__INDEX__.'/fields-'.$field.'/'),
        );

        //MEDIA信息
        $this->common=model('pageinfo')->media($info['name'].' - fieldS',$field);

        //内容列表
        $loop=model('fields')->field_list($info['id'],$limit);

        //统计总内容数量
        $count=model('fields')->field_count($info['id']);
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
	
	public function getlist(){
		 $listrows=6;
        //分页处理
        $url=__INDEX__.'/field/getlist/pages-{page}.html'; 
        $limit=$this->pagelimit($url,$listrows);
		$where=' status=1';
		foreach($_POST as $key=>$val){
		$where.=" AND ".$key."=".$val;
			}
		$loop=model('field')->field_index_list($where,$limit);
		
		if($loop){
		foreach($loop as $key=>$val){
			$loop[$key]['time']=date('Y-m-d', $val['updatetime']);
			
			}
			
		$this->msg($loop,1);
		
		}
		else $this->msg($loop,0);
		
		
		}
	

}