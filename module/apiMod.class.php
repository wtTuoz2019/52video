 <?php
class apiMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
		//$_POST['username']='120101001200';
//		$_POST['password']='123456';
		if($_GET['_action']!='getteacher'&&$_GET['_action']!='sms'&&$_GET['_action']!='getstatus'){
			if(!($_POST['mobile']&&$_POST['smscode'])){
			$array=array('code'=>0,'msg'=>'请提交手机号码跟验证码','data'=>'');
			echo json_encode($array);die;	
				}
			
		$this->user=$user=model('api')->getteacherbymobile('A.mobile='.trim($_POST['mobile']).' and smscode='.trim($_POST['smscode']));
		if(!$user){
			$array=array('code'=>0,'msg'=>'该老师不存在，请联系管理员','data'=>'');
			echo json_encode($array);die;
			}
		
		
		
		}
		
	
		//$_POST['user']=$user;
    }
	
	public function getstatus(){
		
	 	$schoolname=urldecode($_GET['name']);
		$user=model('school')->user($schoolname);
		$return['code']=1;
		if($user){
			if($user['overtime'])
			$return['overtime']=$user['overtime'];
			else
			$return['overtime']='';
			}else{
				$return['overtime']=time()-1000;
				}
		echo json_encode($return);
		}
	public function  getteacher(){
		if($_POST['mobile']){
		$teacher=model('api')->getteacherbymobile('A.mobile='.trim($_POST['mobile']));
		if($teacher){
			$data=array('name'=>$teacher['name'],'schoolname'=>$teacher['schoolname']);
		$array=array('code'=>1,'msg'=>'获取成功','data'=>$data);
			echo json_encode($array);die;
		}
		}
		$array=array('code'=>0,'msg'=>'该老师不存在，请联系管理员','data'=>'');
			echo json_encode($array);die;	
		}
	public function sms(){
		$mobile =trim($_POST['mobile']);
		$teacher=model('api')->getteacherbymobile('A.mobile='.trim($_POST['mobile']));
		if($mobile&&$teacher){
$rand=rand(1000,9999);
  $expire = time() + 7200;

require(CP_CORE_PATH . '/../ext/TopSdk.php');
 $c = new TopClient;
$c ->appkey = '23365622';
$c ->secretKey = '423a30b9c2f0ecf48c3613bd5ba143f4';
$req = new AlibabaAliqinFcSmsNumSendRequest;
$req ->setSmsType( "normal" );
$req ->setSmsFreeSignName( "闪阅云" );
$req ->setSmsParam( "{code:'".$rand."'}" );
$req ->setRecNum( $mobile );
$req ->setSmsTemplateCode( "SMS_9545056" );
$resp = $c ->execute( $req );
if($resp->code){
	$array=array('code'=>0,'msg'=>'发送失败','data'=>'');
		echo json_encode($array);die;	
	}else{ 
	model('common')->smslog(array('uid'=>$teacher['id'],'mobile'=>$mobile,'content'=>'SMS_9545056','time'=>date('Ymd H:i:s'),'type'=>'1'));	
	model('api')->teacher_save(array('id'=>$teacher['id'],'smscode'=>$rand));
$array=array('code'=>1,'msg'=>'发送成功','data'=>'');
		echo json_encode($array);die;	
		}
		}
		$array=array('code'=>0,'msg'=>'该老师不存在，请联系管理员','data'=>'');
		}
	public function login(){
		$data=$this->user;
		$data['logo']=$this->config['imageurl'].$data['logo'];
		
		$array=array('code'=>1,'msg'=>'登录成功','data'=>$data);
		echo json_encode($array);die;	
		
		}
	public function videolist(){
		
		$where=array('tid'=>$this->user['id']);
		if($_POST['cid'])$where['cid']=intval($_POST['cid']);
		else $where['cid']=array('in','(13,18)');
		
		if($_POST['aids']){
			
			$aids=json_decode($_POST['aids'],true);
		
			if($aids)
			$where['aid']=array('in','('.implode(",",$aids).')');
			
			}
		$page=$_POST['page']?intval($_POST['page']):1;$pagenum=$_POST['pagenum']?intval($_POST['pagenum']):10;
		$limit=($page-1)*$pagenum.','.$pagenum;
		
		
		 $loop=model('field')->field_list($where,$limit);
		
		 $count=model('field')->field_index_count($where);
		 $bucket='shanyueyunin';
		 
		if(is_array($loop)){
			$list=array();
		foreach($loop as $key=>$value){
			if($value['image'])$value['image']=$this->config['imageurl'].$value['image'];
			$url = "http://".$sys['MOBILE_DOMAIN']."/content/index?aid=".$value['aid'];
          $id = $value['aid'];
         
           $qcode = 'http://'.$this->config['imageurl']."/upload/aidimage/".$value['aid'].".png";
		    model('content')->gsetQrcode($url, $id,$ $qcode);
		   
		   
			$list[]=array('aid'=>$value['aid'],'cid'=>$value['cid'],'title'=>$value['title'],'image'=>$value['image'],'qcode'=>$qcode,'isupload'=>$value['isupload'],'url'=>$this->user['site']."/content/index?aid=".$value['aid'],'bucket'=>$bucket);
			
			
			
			}
		}
		 
		$array=array('code'=>1,'msg'=>'成功','data'=>array('list'=>$list,'count'=>$count));
		echo json_encode($array);die;	
		}
	public function school(){
		$school=model('school')->school_list();
		$array=array('code'=>1,'msg'=>'成功','data'=>$school);
		echo json_encode($array);die;	
		}
  	public function teacher(){
		$data=$_POST;
		
		if($data['cxueduan']){
			$where['cid']=$data['cxueduan'];
			}
		
		$list=model('teacher')->model_list($where);
		$array=array('code'=>1,'msg'=>'成功','data'=>$list);
		echo json_encode($array);die;
		}
	public function getxueduan(){
		
		$data=model('diyfield')->field_list_data(7);
		$type=array('xueduan','kemu','banben','nianji');
		
		$array=array('code'=>1,'msg'=>'成功','data'=>array('list'=>$data,'type'=>$type));
		echo json_encode($array);die;
		}
	public function getmenus(){
		$menulist=model('web')->menu_list(array('uid'=>$this->user['uid'],'type'=>'cid'));	
			$array=array('code'=>1,'msg'=>'成功','data'=>$menulist);
		echo json_encode($array);die;
		}
	public function addvideo(){
		if(!$_POST['cid']){
			$array=array('code'=>0,'msg'=>'cid必填','data'=>'');	
			
			echo json_encode($array);die;
			
			}
		
		 $_POST=$this->plus_hook_replace('content','add_replace',$_POST);
		 $data=array('title'=>$_POST['title'],
		 			 'image'=>$_POST['image'],
					 'xueduan'=>$_POST['xueduan'],
					 'kemu'=>$_POST['kemu'],
					 'banben'=>$_POST['banben'],
					 'nianji'=>$_POST['nianji'],
					 'mid'=>$_POST['mid'],
					 'tid'=>$this->user['id'],
					 'csid'=>$this->user['csid'],
					 'cid'=>$_POST['cid'],
					 'uid'=>$this->user['uid'],
					 'status'=>0);
		
		$data['aid']=model('content')->add_save($data);
		if($data['aid']){
    	model('content')->add_content_save($data);
		
		$return=array('aid'=>$data['aid'],'url'=>'','bucket'=>'shanyueyunin');
		if($data['cid']==13){
		$return['uploads']=array($this->config['fujia']['video']);
		}elseif($data['cid']==18){
		$return['uploads']=array($this->config['fujia']['videos'],$this->config['fujia']['files']);
		}
		$array=array('code'=>1,'msg'=>'成功','data'=>$return);
		}else{
		$array=array('code'=>0,'msg'=>'添加失败','data'=>'');	
			}
		
		
		echo json_encode($array);die;
		}
	public function editvideo(){
		
		 $_POST=$this->plus_hook_replace('content','add_replace',$_POST);
		 $data=array('title'=>$_POST['title'],
		 			 'image'=>$_POST['image'],
					 'xueduan'=>$_POST['xueduan'],
					 'kemu'=>$_POST['kemu'],
					 'banben'=>$_POST['banben'],
					 'nianji'=>$_POST['nianji'],
					 'mid'=>$_POST['mid'],
					 'aid'=>$_POST['aid'],
					 'status'=>0, );
		
	 $status=model('content')->edit_save($data);
	 $info=model('content')->info(intval($_POST['aid']));
		if($status){
		$return=array('url'=>'','bucket'=>'shanyueyunin');
		if($info['cid']==13){
		$return['uploads']=array($this->config['fujia']['video']);
		}elseif($info['cid']==18){
		$return['uploads']=array($this->config['fujia']['videos'],$this->config['fujia']['files']);
		}
		$array=array('code'=>1,'msg'=>'编辑成功','data'=>$return);
		}else{
		$array=array('code'=>0,'msg'=>'编辑失败','data'=>'');	
			}
		
		
		echo json_encode($array);die;
		}
		public function updatevideo(){
		
		
		 $data=array(
					 'aid'=>$_POST['aid'],
					 'isupload'=>1,
					 '');
		
	 	$status=model('content')->update_save($data);
		if($status){
		
		$array=array('code'=>1,'msg'=>'操作成功','data'=>'');
		}else{
		$array=array('code'=>0,'msg'=>'操作失败','data'=>'');	
			}
		
		
		echo json_encode($array);die;
		}	
	public function upload_dir(){
	
		$data=$this->config['fujia'][$_POST['type']];
		$data['bucket']='shanyueyunin';
		$array=array('code'=>1,'msg'=>'操作成功','data'=>$data);
		echo json_encode($array);die;
		}
	public function upload_list(){
		$page=$_POST['page']?intval($_POST['page']):1;$pagenum=$_POST['pagenum']?intval($_POST['pagenum']):10;
		$limit=($page-1)*$pagenum.','.$pagenum;
		$where=array('uid'=>$this->user['uid'],'tid'=>$this->user['id']);
		
	
		$list=model('content')->video_list($where,$limit);
		$count=model('content')->video_count($where);
		$array=array('code'=>1,'msg'=>'成功','data'=>array('list'=>$list,'count'=>$count));
		echo json_encode($array);die;
		}
	public function upload_save(){
		 $nametemp=explode(".", $_POST['name']);
		 
		
		$data=array('name'=>$nametemp['0'],
					'vurl'=>$_POST['vurl'],
					'type'=>$_POST['type'],
					'size'=>$_POST['size'],
					'tid'=>$this->user['id'],
					 'uid'=>$this->user['uid'],);
				
		if($data['type']=='video'){
			$temp=explode(".", $data['vurl']);
			$data['vurl']= $temp[0];	
			}else{
			$data['type']='file';
			$data['vurl']= $this->config['hk']."source/".$data['vurl'];		
			}			
					
		$id=model('content')->video_add($data);
		$aid=intval($_POST['aid']);
		if($id&&$aid){
			$info=model('content')->info($aid);
			if($info){
				if($info['cid']==13){
					$array=array(
					 'aid'=>$aid,
					 'isupload'=>1,
					 'videourl'=>$data['vurl']
					 );
		
	 			$status=model('content')->update_save($array);
					}elseif($info['cid']==18){
					$content=model('content')->info_content($aid);	
					$content['sources']	=json_decode($content['sources'],true);
					if($data['type']=='video'){
					$content['sources']['videos'][]=$id;
			}else{
					$content['sources']['source'][]=$id;	
			}	
					model('content')->edit_content_save($content);
						}
				
				}
			}
		if($id){
		$array=array('code'=>1,'msg'=>'添加成功','data'=>$id);
		}else{
		$array=array('code'=>0,'msg'=>'添加失败','data'=>'');	
			}
			
		echo json_encode($array);die;
		}
	public function upload_edit(){
		 $data=array('name'=>$_POST['name'],
					 'id'=>intval($_POST['id']));
		$status=model('content')->video_save($data);
		if($status){
		$array=array('code'=>1,'msg'=>'修改成功','data'=>'');
		}else{
		$array=array('code'=>0,'msg'=>'修改失败','data'=>'');	
			}
		echo json_encode($array);die;
		}
	public function upload_del(){
		 $id=intval($_POST['id']);
        $this->alert_str($id,'int',true);
     
        /*hook end*/
        $status=model('content')->video_del($id);
		if($status)
      $array=array('code'=>1,'msg'=>'内容删除成功','data'=>'');
      else
	   $array=array('code'=>0,'msg'=>'内容删除失败','data'=>'');
	   
	   echo json_encode($array);die; 
		
		}
	
	public function infobyaid(){
		$data=model('content')->info(intval($_POST['aid']));
		if(!$data){
		  $array=array('code'=>0,'msg'=>'内容不存在','data'=>'');
		  echo json_encode($array);die; 
		
			}
		$info=array('title'=>$data['title'],
		 			 'image'=>$data['image'],
					 'xueduan'=>$data['xueduan'],
					 'kemu'=>$data['kemu'],
					 'banben'=>$data['banben'],
					 'nianji'=>$data['nianji'],
					 'mid'=>$data['mid'],
					 'aid'=>$data['aid'],
					 'cid'=>$data['cid'],
					 'isupload'=>$data['isupload']
					);
			 if($info['cid']==13){
			
			$info['video']=model('content')->video_info(array('vurl'=>$data['videourl']));
			
			 }else{
				 $content=model('content')->info_content($info['aid']);
				 
				 $source=json_decode($content['sources'],true);
				 if($source['videos']){ 
        $ids=implode(",", $source['videos']);
		$info['videos']=model('content')->video_list('id in ('.$ids.')');
				 }
				 
				  if($source['source']){ 
        $ids=implode(",", $source['source']);
		$info['files']=model('content')->video_list('id in ('.$ids.')');
				 }
		
			
			}
			
			$array=array('code'=>1,'msg'=>'成功','data'=>$info);
			echo json_encode($array);die; 
		}
		
		    //内容删除
    public function del()
    {
        $id=intval($_POST['aid']);
        $this->alert_str($id,'int',true);
     
        /*hook end*/
        $status=model('content')->del($id,$this->user['uid']);
		if($status)
      $array=array('code'=>1,'msg'=>'内容删除成功','data'=>'');
      else
	   $array=array('code'=>0,'msg'=>'内容删除失败','data'=>'');
	   
	   echo json_encode($array);die; 
    }
		
}

?>