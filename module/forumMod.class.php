<?php
class forumMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		  $this->forum=$forum= model('forum')->forum_config(array('uid'=>$this->config['uid']));
	  if(!$forum['isopen'])$this->alert('该论坛未开放');
		$this->getuserinfo();
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
if(strstr($type,'image/jpeg')!==''){  
    $ext = '.jpg';  
}elseif(strstr($type,'image/gif')!==''){  
    $ext = '.gif';  
}elseif(strstr($type,'image/png')!==''){  
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
	
	$this->msg(array('pic'=>$pic,'thumb'=>$thumb),1);
	}else{
	$this->msg('上传失败',0);	
	} 
// 返回  

		
		}
}
?>