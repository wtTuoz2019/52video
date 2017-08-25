<?php
class forumMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		if($_POST['data']['configuid']){
			$this->config['uid']=$_POST['data']['configuid'];
			}
			
		  $this->forum=$forum= model('forum')->forum_config(array('uid'=>$this->config['uid']));
	  if(!$forum['isopen'])$this->alert('该论坛未开放');
		$this->getuserinfo();
		
	  if($forum['auth']){
		  if(!($this->userinfo['name']&&$this->userinfo['peopletype'])){
			 $this->redirect('/login/parent?'.$this->urltoken); 
			  }
		  }
		  
		
		$this->myname=$this->userinfo['nickname'];
		if($this->userinfo['name']){
						if($this->userinfo['peopletype']=='student')
						 $this->myname=$this->userinfo['name'].$this->userinfo['relation'];
						elseif($this->userinfo['peopletype']=='teacher')
						 $this->myname=$this->userinfo['name'].'老师';
						} 
		  
    }

	public function index(){
		
	  if(!$this->userinfo){
		  	$code=$_COOKIE[$this->config['SPOT'].'_logincode'];
		if(!$code){
		$code=md5(get_client_ip().time());
         $cookie=$code;
        setcookie($this->config['SPOT'].'_logincode',$cookie);
		}
		$this->code=$code;
		$url = "http://".$this->config['MOBILE_DOMAIN']."/index.php/login/scope/logincode-".$code.".html";
		model('login')->gsetloginQrcode($url, $code);
		  
		  }
	  model('forum')->forum_pv('uid='.$this->config['uid']);
	 $this->topics_count=model('forum')->topics_count(array('configuid'=>$this->config['uid']));
	  $this->display('forum_index.html');
		
		}
	public function post(){
		if($_POST){
			$data=array('content'=>$_POST['content'],'uid'=>$this->userinfo['uid'],'createtime'=>time(),'configuid'=>$this->config['uid']);	
			if($this->forum['ischeck'])$data['status']=0;
			if(is_array($_POST['pic'])){
				foreach($_POST['pic'] as $key=>$val){
					$temp[]=array('pic'=>$val,'thumb'=>$_POST['thumb'][$key]);
					}
				
			$data['photos']=serialize($temp);	
				}
			
			model('forum')->topics_save($data);
			 echo json_encode(array('status' => 1, 'message' => '发布成'));die;
			}
		
		  $this->display('forum_post.html');
		}
	public function mine(){
		
		
		  $this->display('forum_mine.html');
		}
   	public function upload(){
	$img = isset($_POST['img'])? $_POST['img'] : '';  
  
// 获取图片  
list($type, $data) = explode(',', $img);  
  
// 判断类型  
if(strstr($type,'image/jpeg')){  
    $ext = '.jpg';  
}elseif(strstr($type,'image/gif')){  
    $ext = '.gif';  
}elseif(strstr($type,'image/png')){  
    $ext = '.png';  
}  
if(!$ext){
$this->msg('图片只能是jpg,gif,png',0);die;	
	}
  $time=time().rand(10000,99999);
// 生成的文件名  
$photo = __ROOTDIR__.'/upload/forum/'.$time.$ext;  
$pic='/upload/forum/'.$time.$ext;  
$thumbname = __ROOTDIR__.'/upload/forum/'.$time.'_thumb'.$ext;  
$thumb=  '/upload/forum/'.$time.'_thumb'.$ext;  
// 生成文件  
if(file_put_contents($photo, base64_decode($data), true)){
	require(CP_CORE_PATH . '/../ext/aliyun-oss-php-sdk-master/samples/Common.php');
	$ossClient = Common::getOssClient();
		if (is_null($ossClient)) exit(1);
	$bucket = Common::getBucketName();
	$temp=explode('upload',$photo);
	$object='upload'.$temp[1];
	
	 $ossClient->uploadFile($bucket, $object, $photo);
	require(CP_CORE_PATH . '/../lib/Image.class.php');
	Image::thumb($photo, $thumbname, '', 60, 60); // 生成图像缩略图
	$temp=explode('upload',$thumbname);
	$object='upload'.$temp[1];
	 $ossClient->uploadFile($bucket, $object, $thumbname);
	$this->msg(array('pic'=>$pic,'thumb'=>$thumb),1);
	}else{
	$this->msg('上传失败',0);	
	} 
// 返回  

		
		}
		
	public function morelist(){
		  $listrows=5;
        //分页处理
        $url=__URL__.'morelist?page={page}'; 
        $limit=$this->pagelimit($url,$listrows);
		if($_POST['data']){
			$where='configuid='.$_POST['data']['configuid'].' and A.status=1';
			if($_POST['s'])$where.=" and A.content like '%".$_POST['s']."%' ";
			$list=model('forum')->topics_list($where,$limit);
			if($list){
				
				foreach($list as $key=>$value){
					if($value['realname']){
						if($value['peopletype']=='student')
						$list[$key]['name']=$value['realname'].$value['relation'];
						elseif($value['peopletype']=='teacher')
						$list[$key]['name']=$value['realname'].'老师';
						}
					$list[$key]['photos']=unserialize($value['photos']);
					$list[$key]['createtime']=date('Y-m-d H:i:s',$value['createtime']);
					$list[$key]['zanlist']=model('forum')->topics_zan_list('A.tid='.$value['id']);
					$list[$key]['mezan']=model('forum')->topics_zan_info('A.tid='.$value['id'].' and A.uid='.$this->userinfo['uid']);
					$list[$key]['comment']=model('forum')->topics_comment_list('A.tid='.$value['id'].' and A.status=1');
					
					}
			$this->msg($list,1);	
			}else{
			$this->msg('no',0);		
				}
			
			}
			
		
		}
	public function zan(){
		$data=array('uid'=>$this->userinfo['uid'],'tid'=>$_POST['id']);
		if(model('forum')->topics_zan($data)){
				$this->msg('取消成功',1);	
			}else{
			$this->msg('取消失败',0);			
				}
		}
	public function reply(){
		$data=array('uid'=>$this->userinfo['uid'],'tid'=>$_POST['id'],'content'=>$_POST['content'],'configuid'=>$_POST['data']['configuid'],'createtime'=>time());
			if($this->forum['comcheck'])$data['status']=0;
		if(model('forum')->topics_comment_save($data)){
			$this->msg('yes',1);	
			}else{
				$this->msg('no',0);		
				}
		
		}
	public function topics_del(){
		$where=array('id'=>$_POST['id'],'configuid'=>$_POST['data']['configuid']);
		
		model('forum')->topics_del($where);
		$this->msg('删除成功',1);	
		}
	public function comment_del(){
		$where=array('id'=>$_POST['id'],'configuid'=>$_POST['data']['configuid']);
		
		model('forum')->comment_del($where);
		$this->msg('删除成功',1);	
		}
		
	public function minemorelist(){
		  $listrows=5;
        //分页处理
        $url=__URL__.'minemorelist?page={page}'; 
        $limit=$this->pagelimit($url,$listrows);
		if($_POST['data']){
			$where='configuid='.$_POST['data']['configuid'].' and A.uid='.$this->userinfo['uid'];
			if($_POST['s'])$where.=" and A.content like '%".$_POST['s']."%' ";
			$list=model('forum')->topics_list($where,$limit);
			if($list){
				
				foreach($list as $key=>$value){
					//$list[$key]['photos']=unserialize($value['photos']);
					if($value['realname']){
						if($value['peopletype']=='student')
						$list[$key]['name']=$value['realname'].$value['relation'];
						elseif($value['peopletype']=='teacher')
						$list[$key]['name']=$value['realname'].'老师';
						}
					$list[$key]['createtime']=date('Y-m-d H:i:s',$value['createtime']);
					$list[$key]['zannum']=model('forum')->topics_zan_count('tid='.$value['id']);
					
					$list[$key]['commentnum']=model('forum')->topics_comment_count('tid='.$value['id'].' and status=1');
					
					}
			
			}
			
			}
				$this->msg($list,1);
		
		}
		public function likemorelist(){
		  $listrows=5;
        //分页处理
        $url=__URL__.'likemorelist?page={page}'; 
        $limit=$this->pagelimit($url,$listrows);
		if($_POST['data']){
			$where='configuid='.$_POST['data']['configuid'].' and C.uid='.$this->userinfo['uid'];
		
			$list=model('forum')->zan_topics_list($where,$limit);
			if($list){
				
				foreach($list as $key=>$value){
					//$list[$key]['photos']=unserialize($value['photos']);
					if($value['realname']){
						if($value['peopletype']=='student')
						$list[$key]['name']=$value['realname'].$value['relation'];
						elseif($value['peopletype']=='teacher')
						$list[$key]['name']=$value['realname'].'老师';
						}
					$list[$key]['createtime']=date('Y-m-d H:i:s',$value['createtime']);
					
					}
			
			}
			
			}
			
		$this->msg($list,1);	
		}
	public function topics_info(){
			$where='configuid='.$this->config['uid'].' and A.id='.intval($_GET['id']);
			$info=model('forum')->topics_info($where);
			if (!is_array($info)) {
			
            $this->error404();
       		 }
			 		if($info['realname']){
						if($info['peopletype']=='student')
						$info['name']=$info['realname'].$info['relation'];
						elseif($info['peopletype']=='teacher')
						$info['name']=$info['realname'].'老师';
						}
			 		$info['photos']=unserialize($info['photos']);
					$info['createtime']=date('Y-m-d H:i:s',$info['createtime']);
					$info['zanlist']=model('forum')->topics_zan_list('A.tid='.$info['id']);
					$info['mezan']=model('forum')->topics_zan_info('A.tid='.$info['id'].' and A.uid='.$this->userinfo['uid']);
					$info['comment']=model('forum')->topics_comment_list('A.tid='.$info['id'].' and A.status=1');
					
			 
			$this->info=$info;
		 $this->display('forum_topics_info.html');
		}
}
?>