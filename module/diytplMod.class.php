<?php
//field显示
class diytplMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

    public function index() {
		
		
		
      
		
		$tid=intval($_GET['tid']);
		
		
		
		
		//分页
		 $url=__URL__.'/index/tid-'.$tid.'-pages-{page}.html';//分页基准网址
		 $page=new Page();
		 $listRows=20;//每页显示的信息条数 
		 
		 //if(isset($_GET['page'])){
		//	 $cur_page=intval($_GET['page']);
		 //}else{
		 $cur_page=$page->getCurPage($url);  
			 //}
		 $limit_start=($cur_page-1)*$listRows;         
		 $limit=$limit_start.','.$listRows; 
		
		
		
		//自定义模板id
		$info=$this->model->table('diytpl')->where(array('id'=>$tid))->find();
		$aid=unserialize($info['aid']);
		if(!$aid){
			$where=' cid='.$info['cid'].' AND status=1';
			if($this->config['csid']){
			$where.=' AND csid='.$this->config['csid'];
			}
				
		
			$aid_all=$this->model->table('content')->field('aid')->where($where)->select();
				if(is_array($aid_all)){
			foreach($aid_all as $k=>$v){
				
				$aid[$k]=$v['aid'];
				
				}
				}
			}
			
		if(is_array($aid)){
			$in_str="aid in (";
			foreach ($aid as $k=>$v){
				$in_str.=$v.",";
				}
				$in_str= substr($in_str,0,-1);
				$in_str.=")";
				if(!empty($_POST['search_data'])){
					
					$in_str.="AND title like '%".$_POST['search_data']."%'";
					
					
					}
				if(!empty($_GET['gid'])){
					$gid=intval($_GET['gid']);
					$in_str.="AND gid = ".$gid;
					}	
				
				
				
			$count=$this->model->table('content')->where($in_str)->count();	
			$list=$this->model->table('content')->where($in_str)->limit($limit)->order('updatetime desc')->select();
			if(is_array($list)){
				foreach($list as $k=>$v){
					$list[$k]['teacher']=$this->model->table('teacher')->where(array('id'=>$v['tid']))->find();
					
					}
			}
			
			
		}
		
		
		
		$this->assign('page',$page->show($url,$count,$listRows,5)); 
		
		
		$dapartment=$this->model->table('diyfield_value')->where(array('did'=>1))->select();
		$this->assign('dlist',$dapartment);
		
		
		
		
		$this->assign('info',$info);
		$this->assign('list',$list);
		
		if(!$info['tplid']){
			$tpl='diytpl_101.html';
		}else{
			$tpl='diytpl_'.$info['tplid'].'.html';
			}
		
			$this->display($tpl);  
    	}
	
	public function dapartment(){
		
		
		
		
		
		
		
		$this->display('dapartment.html');
		
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
	

	

}