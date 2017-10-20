<?php
class indexMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		//$this->getuserinfo();
    }

	public function index() {
		
	
		/*hook*/
        $this->plus_hook('index','index');
        /*hook end*/
        //MEDIA信息
/*        $sid=isset($_GET['sid']) ? intval($_GET['sid']) : 0;
        $token=isset($_GET['token']) ? $_GET['token'] : 0;
        $wecha_id=isset($_GET['token']) ? $_GET['wecha_id'] : 0;
        session_start();
        if ($sid != 0) {
            $_SESSION['sid']= $sid;
        }else{
            $token = model('field')->get_id($token);
            if (isset($token) && $token > 0) {
                $_SESSION['sid']= $token;
                $_SESSION['wecha_id']=$wecha_id;
            }
        }*/
		if($this->webconfig){
			
		$info=model('web')->menu_info(array('uid'=>$this->config['uid'],'type'=>'index'));	
		$mids = model('web')->getmids(array('uid'=>$this->config['uid']));
		
		if(!$info)$this->alert('请先新建栏目');
		if(!$mids)$info['mids']=$info['id'];
		else $info['mids']=$mids;
		
		$this->info=$info;
		
		
			}
		
		
        $this->loops=model('field')->video(6,16);
        $this->common=model('pageinfo')->media();
		$this->display($this->config['TPL_INDEX']);
	}
	public function wenlai(){
		
		$this->display('wenlai.html');
		}
	public function teacher(){
		  $listrows=12;
        //分页处理
        $url=__INDEX__.'/index/teacher/pages-{page}.html'; 
        $limit=$this->pagelimit($url,$listrows);
		
		$where['image']=array('<>','""');
		if($this->config['uids']){
		$where['uid']=array('in',$this->config['uids']);
		}else{
			$where['uid']=$this->config['uid'];
			}
		 $title = trim($_GET['s']);
		  if($title){
      $where['name']=array('like',"'%".$title."%'");
      }
		$this->loop=model('teacher')->teacherlist($where,$limit);
		  //统计总内容数量
      	$count=model('teacher')->teachercount($where);
        //分页处理
        $this->page=$this->page($url, $count, $listrows);
        //获取上一页代码
        $this->prepage=$this->page($url, $count, $listrows,'',1);
        //获取下一页代码
        $this->nextpage=$this->page($url, $count, $listrows,'',2);
		$this->display('index_teacher.html');
		}
	public function school(){
		  $listrows=18;
        //分页处理
        $url=__INDEX__.'/index/school/pages-{page}.html'; 
        $limit=$this->pagelimit($url,$listrows);
		$where['image']=array('<>','""');
		$where['id']=array('in',$this->config['cids']);
		 $title = trim($_GET['s']);
		  if($title){
      $where['name']=array('like',"'%".$title."%'");
      }
		$this->loop=model('school')->model_list($where,$limit);
		  //统计总内容数量
      	$count=model('school')->count($where);
        //分页处理
        $this->page=$this->page($url, $count, $listrows);
        //获取上一页代码
        $this->prepage=$this->page($url, $count, $listrows,'',1);
        //获取下一页代码
        $this->nextpage=$this->page($url, $count, $listrows,'',2);
		$this->display('index_school.html');
		}
	public function  addvisit(){
		$this->getuserinfo();
		model('common')->addvisit($this->userinfo['uid']);
		
		}
	public function  addstreamvisit(){
		model('common')->addstreamvisit($this->userinfo['uid']);
		
		}
	public function login(){
		$this->display('login.html');
		
		}
	 //栏目导航
    public function nav($id,$where)
    {
        $data = $this->model->field('id,pid,name')->table('web_menu')->where($where)->select();
        $cat = new Category(array(
            'id',
            'pid',
            'name',
            'cname'));
        if(empty($data)){
             return;
        }
        $cat = $cat->getPath($data, $id);
        return $cat; 
    }


	}