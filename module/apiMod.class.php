 <?php
class apiMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
		//$_POST['username']='120101001200';
//		$_POST['password']='123456';
		if($_GET['_action']!='getteacher'&&$_GET['_action']!='sms'){
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
		$where=array('csid'=>$this->user['csid']);
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
		   
		   
			$list[]=array('aid'=>$value['aid'],'title'=>$value['title'],'image'=>$value['image'],'qcode'=>$qcode,'isupload'=>$value['isupload'],'url'=>$this->user['site']."/content/index?aid=".$value['aid'],'bucket'=>$bucket);
			
			
			
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
		
		if($data['csid']){
			$where['cid']=$data['csid'];
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
	public function getvideoinputs(){
		
		}
	public function addvideo(){
		 $_POST=$this->plus_hook_replace('content','add_replace',$_POST);
		 $data=array('title'=>$_POST['title'],
		 			 'image'=>$_POST['image'],
					 'nianji'=>$_POST['nianji'],
					 'kemu'=>$_POST['kemu'],
					 'banben'=>$_POST['banben'],
					 'sid'=>$_POST['sid'],
					 'tid'=>$_POST['tid'],
					 'csid'=>$_POST['csid'],
					 'cid'=>$this->selection['cid'],
					 'uid'=>$this->user['uid'],
					 'status'=>-5);
		
		$data['aid']=model('content')->add_save($data);
		if($data['aid']){
    	model('content')->add_content_save($data);
		model('content')->edit_save(array('aid'=>$data['aid'],'videourl'=>'aid'.$data['aid']));
		if($this->selection['cid']==18){
		$return=array('url'=>'aid'.$data['aid'],'uploads'=>array($this->config['fujia']['video'],$this->config['fujia']['plan'],$this->config['fujia']['source']),'bucket'=>'dianjiao');
		}elseif($this->selection['cid']==33){
		$return=array('url'=>'aid'.$data['aid'],'uploads'=>array($this->config['fujia']['videos'],$this->config['fujia']['register'],$this->config['fujia']['source']),'bucket'=>'weikecheng');	
		}else{
		$return=array('url'=>'aid'.$data['aid'],'uploads'=>array($this->config['fujia']['video'],$this->config['fujia']['register'],$this->config['fujia']['plan'],$this->config['fujia']['source']),'bucket'=>'dianjiao');
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
					 'nianji'=>$_POST['nianji'],
					 'kemu'=>$_POST['kemu'],
					 'banben'=>$_POST['banben'],
					 'sid'=>$_POST['sid'],
					 'tid'=>$_POST['tid'],
					 'csid'=>$_POST['csid'],
					 'aid'=>$_POST['aid'],
					 'status'=>-5, 
					 '');
		
	 $status=model('content')->edit_save($data);
	 $info=model('content')->info(intval($_POST['aid']));
		if($status){
		if($info['cid']==18){
		$return=array('url'=>'aid'.$data['aid'],'uploads'=>array($this->config['fujia']['video'],$this->config['fujia']['plan'],$this->config['fujia']['source']),'bucket'=>'dianjiao');
		}elseif($info['cid']==33){
		$return=array('url'=>'aid'.$data['aid'],'uploads'=>array($this->config['fujia']['videos'],$this->config['fujia']['register'],$this->config['fujia']['source']),'bucket'=>'weikecheng');	
		}else{
		$return=array('url'=>'aid'.$data['aid'],'uploads'=>array($this->config['fujia']['video'],$this->config['fujia']['register'],$this->config['fujia']['plan'],$this->config['fujia']['source']),'bucket'=>'dianjiao');
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
	public function infobyaid(){
		$data=model('content')->info(intval($_POST['aid']));
		if(!$data){
		  $array=array('code'=>1,'msg'=>'编辑成功','data'=>$info);
		  echo json_encode($array);die; 
		
			}
		$info=array('title'=>$data['title'],
		 			 'image'=>$data['image'],
					 'nianji'=>$data['nianji'],
					 'kemu'=>$data['kemu'],
					 'banben'=>$data['banben'],
					 'sid'=>$data['sid'],
					 'tid'=>$data['tid'],
					 'csid'=>$data['csid'],
					 'aid'=>$data['aid'],
					 'isupload'=>$data['isupload']
					);
			 if($info['cid']==33){
		 $bucket=model('source')->sourcebyweiketitle($data['videourl']);
		 $info['register']=$bucket['register']; $info['source']=$bucket['source']; 
			 if(!$bucket['weike']){
			$bucket['weike']= module('content')->getweike($data['videourl']);
			}
			   $info['weike']=$bucket['weike'];
			if(!$info['source']){
			$bucket['source']=module('content')->getweikesource($data['videourl']);
			 $info['source']=$bucket['source'];
			}
			 
			if(!$info['register']){
			$bucket=module('content')->getweikeregister($data['videourl']);
			$info['register']=$bucket['register'];
			
			}
			
			
			 }else{
				  $bucket=model('source')->sourcebytitle($data['videourl']);
				
			 	$info['register']=$bucket['register']; 
				
		 if(!$bucket['plan']){
			$bucket=module('content')->getplan($data['videourl']);  
			}$info['plan']=$bucket['plan'];
		 if(!$info['source']){
			$bucket['source']=module('content')->getsource($data['videourl']);
			$info['source']=$bucket['source']; 
			}
			
				if(!$info['register']){
			$bucket=module('content')->getregister($data['videourl']);
			$info['register']=$bucket['register'];
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