<?php
use Cdn\Request\V20141111 as Cdn;
class contentMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {  	
	 if(!$_GET['frame'])
	$this->getuserinfo();
	
		if($_POST){
		if($_POST['code']!=$_COOKIE['mobilecode']){
			
			 $this->msg('手机验证码不对！',0);
			}
		
		$_POST['fid']=4;
        $data=$this->data_check($_POST);
        //处理完毕后交由模型处理数据
		$data['uid']=$this->userinfo['uid'];
	
		$info=model('form_list')->infobyuser($data['uid'],'signup');
		if($info){
			 model('form_list')->editbyuid($data); 
			}else{	
        	model('form_list')->add($data);
			}
		
		$sign=array(
				'aid'=>$_POST['aid'],
				'uid'=>$data['uid']
				);
		 $info=model('content')->info($_POST['aid']);	
		 $sign['status']=1;
		if($info['noaudit'])$sign['status']=0;
		if($info['autoaudit']&&$info['noaudit']){
		$info['auditfield_lists']=unserialize($info['auditfield_lists']);
		$where=array();
			foreach($info['auditfield_lists'] as $key=>$val)
			{	
				$where[$val]=$data[$val];
				}
				$where['aid']=$_POST['aid'];
				if(model('form_list')->signautoinfo($where)){
					$sign['status']=1;
					
					}
			
		}
		
		
		
		model('form_list')->addsign($sign); 
		 $this->msg('报名成功！',1);exit();
			}
	
		 $aid = intval($_GET['aid']);
        if (empty($aid)) {
            $this->error404();
        }
        $info=model('content')->info($aid);
        if (!is_array($info)) {
			
            $this->error404();
        }
		if($_GET['nosign']=='yes'){
		  $expire = time() + 7200;
  		 setcookie('nosign','yes',$expire,'/');
		$this->redirect(module('label')->get_aurl($info['aid']));
				
			}
			
		if($_GET['nosign']=='no'){
		
		setcookie("nosign", NULL, time() - 3600,'/');
		$this->redirect(module('label')->get_aurl($info['aid']));
			}

        //判断跳转
        if (!empty($info['url']))
        {
            $link=$this->display(html_out($info['url']),true,false);
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$link."");
			exit;
        }
        //查询栏目的信息
        $this->category = model('category')->info($info['cid']);
        // //模块自动纠正
        // model('content')->model_jump($this->category['mid'],'content');
        $model_info = model('category')->model_info($this->category['mid']);
        //位置导航
        $this->nav=array_reverse(model('category')->nav($this->category['cid']));
        //查询上级栏目信息
        $this->parent_category = model('category')->info($this->category['pid']);
        if (!$this->parent_category) {
            $this->parent_category = array(
                "cid" => "0",
                "pid" => "0",
                "mid" => "0",
                "name" => "无上级栏目");
        }
        //获取顶级栏目信息
        $this->top_category = model('category')->info($this->nav[0]['cid']);

        //读取完整内容信息
        $info=model('content')->model_content($info['aid'],$this->category['expand']);
		
	
		
		if($info['activity_id']){
			$this->activity=$activity=model('content')->model_content($info['activity_id']);
			if($activity['signup']){
		
		$signinfo=model('form_list')->signinfo(array('uid'=>$this->userinfo['uid'],'aid'=>$activity['aid']));	
		
		
				
		
			if($activity['autoaudit']&&$activity['noaudit']&&$signinfo['status']==0){
		$activity['auditfield_lists']=unserialize($activity['auditfield_lists']);
		$userinfo=model('form_list')->infobyuser($this->userinfo['uid'],'signup');
				$where=array();
			foreach($activity['auditfield_lists'] as $key=>$val)
			{	
				$where[$val]=$userinfo[$val];
				}
				$where['aid']=$aid;
				
				$userinfo=model('form_list')->signautoinfo($where);
				
				if($userinfo){
					$signinfo['status']=1;
			
					model('form_list')->editsign($signinfo);
					
					
					}
				
				
			}
			
		$this->signinfo=$signinfo;
		
	
		if(($this->signinfo['status']==0)&&$_COOKIE['nosign']!='yes'){
			
		
		$this->redirect(__URL__.'/index?aid='.$activity['aid']);	
		}else{
			if($activity['beforeid']){
			
			if(!model('selfform')->input_value(array('fid'=>$activity['beforeid'],'aid'=>$activity['aid'],'uid'=>$this->userinfo['uid']))){
			
				$this->redirect(__URL__.'/index?aid='.$activity['aid']);	
				}
			}
		
		
			$userinfo=model('form_list')->infobyuser($this->userinfo['uid'],'signup');
	 	$_SESSION["name"]=$userinfo['name']?$userinfo['name']:$_SESSION["nickname"];
			}
	
				
				}
			}
		 $info['field_lists']=unserialize($info['field_lists']);
		 	
		if($info['signup']){
			
			$signinfo=model('form_list')->signinfo(array('uid'=>$this->userinfo['uid'],'aid'=>$info['aid']));
			
			
			if($info['autoaudit']&&$info['noaudit']&&$this->signinfo['status']==0){
		$info['auditfield_lists']=unserialize($info['auditfield_lists']);
		$userinfo=model('form_list')->infobyuser($this->userinfo['uid'],'signup');
				$where=array();
			foreach($info['auditfield_lists'] as $key=>$val)
			{	
				$where[$val]=$userinfo[$val];
				}
				$where['aid']=$aid;
				
				$userinfo=model('form_list')->signautoinfo($where);
				
				if($userinfo){
					$signinfo['status']=1;
			
					model('form_list')->editsign($signinfo);
					
					
					}
				
				
			}
			
		$this->signinfo=$signinfo;
		
	
		if($this->signinfo['status']==0&&($_COOKIE['nosign']!='yes')){
		$this->userinfo=model('form_list')->infobyuser($this->userinfo['uid'],'signup');
		
		$this->field_list=model('form')->field_list(4);
		$this->info=$info;
		$this->display('signup.html');	die;	
		}else{
			
			$userinfo=model('form_list')->infobyuser($this->userinfo['uid'],'signup');
			$_SESSION["name"]=$userinfo['name']?$userinfo['name']:$_SESSION["nickname"];
			}
	
	
		
		} 
		
		
	    //读取内容信息
        $info_content=model('content')->info_content($info['aid']);
		 $content=$info_content['content'];
		   //读取内容替换
        if(!empty($content)){
            $content=model('content')->format_content($content);
        }
        //自动增加TAG链接
        if(!empty($content)&&$info['taglink']){
            $content=model('content')->tag_link($content,$info['aid']);
        }
		
			 $info['content']=$content;
			 
			if($info['beforeid']){
			if(!model('selfform')->input_value(array('fid'=>$info['beforeid'],'aid'=>$info['aid'],'uid'=>$this->userinfo['uid']))){
			  $this->info=$info;
			$this->form_inputs=model('selfform')->form_inputs_list(array('fid'=>$info['beforeid']));
			
			$this->display('beforeform.html');	die;
			}
			}
		
        //更新访问计数
        model('content')->views_content($info['aid'],$info['views']);
        
    
		$zidingyi=unserialize($info_content['zidingyi']);
		$this->zidingyi=$zidingyi;
	 	$this->functions=model('content')->functions_list(array('aid'=>$info['aid']));
		
      
     
		

        //MEDIA信息
        $this->common=model('pageinfo')->media($info['title'].' - '.$this->category['name'],$info['keywords'],$info['description']);
        
    
	
        $this->plus_hook('content','index',$this->info);
        $this->info=$this->plus_hook_replace('content','index_replace',$this->info);
        /*hook end*/
       
        $this->info=$info;

        if ($info["cid"] == 16) {

            $this->channel=model('content')->channel($info['channel']);
        }
		
		$data=array('uid'=>$_SESSION['uid'],
					'aid'=>$info['aid'],
					'type'=>'aid'
					);
		if(model('content')->subscribeinfo($data)){
			$this->subscribe='nosubscribe';
			}else{
			$this->subscribe='subscribe';	
				}
				
			
		if($info['tpl']&&MOBILE){
			
		
			$this->display($info['tpl']);
			
			}else{
				if($_GET['frame']){
				 $this->display('frame.html');	exit();
				}
				
			if($_GET['from']=='live'){
				$this->display('contentlive.html');	
				exit();
				}
				
			 $this->display($this->category['content_tpl']);	
			}
        
    }
	public function school(){
		if(MOBILE)
		$this->getuserinfo();
		 $this->csid=$csid=intval($_GET['csid']);
		 $this->school=$school=model('school')->info($csid);
	
		 if(!$school){
		if (empty($aid)) {
            $this->error404();
        	}
		}
		
		
		$where=' status=1';
		$where.=" AND csid=".$school['id']."";
		
		$this->history=model('field')->field_index_list($where,6);
		$this->historynum=model('field')->field_index_count($where);
		$this->common=model('pageinfo')->media($school['name'],$field);
		$this->info=$info=model('content')->get_livecontent($csid);
		if($info){
		$this->functions=model('content')->functions_list(array('aid'=>$info['aid']));
		$this->channel=model('content')->channel($info['channel']);
		$info_content=model('content')->info_content($info['aid']);
		$zidingyi=unserialize($info_content['zidingyi']);
		$this->zidingyi=$zidingyi;
		  //更新访问计数
        model('content')->views_content($info['aid'],$info['views']);
        
		}
		
		
		
		
		$this->display('schoollive.html');
		
		}
	public function functioninfo(){
		$id=intval($_GET['id']);
		$this->vo=model('content')->functions_info(array('id'=>$id));
		$this->display('functioninfo.html');
		}
	public function  userplay(){
		$uid=intval($_GET['uid']);
		if (empty($uid)) {
            $this->error404();
        }
		$this->user=model('user')->info($uid);
		$this->display('userplay.html');
		}
	  public function data_check($data)
    {
        //获取所有字段
        $field_list=model('form_list')->list_lod($data['fid']);
        if(empty($field_list)){
            $this->msg('未发现报名字段！',0);
        }
        foreach ($field_list as $value) {
            $data[$value['field']]=model('expand_model')->field_in($data[$value['field']],$value['type'],$value['field']);
        }
        return $data;
    }
		public function playstream(){
		$this->getuserinfo();
		$where['stream']=$this->stream=$_GET['stream'];
		$nowtime=time();
		$where['endtime']=array('>',$nowtime);
		$where['uid']=array('<>',$this->userinfo['uid']);
		 $count=model('data')->streamvisitnum($where);
		if($count>=10){
			$this->alert('观看人数已满，请联系管理员');
			}
		
		 $this->display('playstream.html');	
		
		}	
		

  public function monitor(){
	   	$this->getuserinfo();
		
	  if($_GET['type']=='id'){
		  $id=intval($_GET['id']);
		  $device=model('content')->deviceinfo($id);
		  
		 $devicepeople=model('content')->devicepeople($this->userinfo['uid'],$id);
		     $stream=$device['sn'];
		  }else{
	  $stream=addslashes($_GET['stream']);
	  
	  $id = model('content')->get_channel_id($stream);	
	  }
		if(!isset($id)){
			echo "参数非法！";
            exit;
		}
		
		if($this->userinfo['gid']!=3&&!$devicepeople){
		$this->redirect(__URL__.'/playstream/stream-'.$stream);die;
			}
		
		if($id)
		$zid = model('content')->get_content_id($id);
		$fid = isset($zid) ? $zid : 0;
		$this->info= $info=model('content')->model_content($fid,3);
	
		$this->fid = $fid;
		//if (!$info) {
//			echo "该流暂时无直播";
//            exit;
//		}
		//奥点云
		//$url='http://openapi.aodianyun.com/v2/LSS.GetPublishInfo';
//		 $poststr='{"access_id":"'.$this->config['AccessID'].'","access_key":"'.$this->config['AccessKEY'].'","appid":"schoolvideo","stream":"'.$stream.'"}';
//		$json=$this->curlGet($url,$poststr);
//		$result=json_decode($json,true);
//		
//		if($result['Flag']==100){
//	
//			if($result['List'][0]){
//				
//			
//			foreach($result['List'][0] as $key=>$val){
//				$result['List'][0][$key]=$val?$val:0;
//				}
//				
//				$this->result=$result['List'][0];
//			
//			}
//			
//			}
$nowtime=time();
		//阿里
//		date_default_timezone_set('UTC');
//		require(CP_CORE_PATH . '/../ext/aliyunsdk/aliyun-php-sdk-core/Config.php');
//		$iClientProfile = DefaultProfile::getProfile("cn-hangzhou",$this->config['AccessKeyId'], $this->config['AccessSecret']);
//			$client = new DefaultAcsClient($iClientProfile); 
//			$request = new Cdn\DescribeLiveStreamsFrameRateAndBitRateDataRequest();  
//			$request->setMethod("GET");
//			$request->setDomainName($this->config['DomainName']);  
//			$request->setAppName($this->config['AppName']);
//			$request->setStartTime(date('Y-m-d\TH:i:s\Z',$info['starttime']));
//			$request->setEndTime(date('Y-m-d\TH:i:s\Z',($info['starttime']+$info['time']*60)));
//			
		
		//	$response = $client->getAcsResponse($request); 
	
		
		$timenow=time();
		$wheretemp[aid]=$fid;
		$wheretemp[]='endtime between '.($timenow-120).' and '.($timenow);
			
		$this->views=model('content')->getdatacount($wheretemp);	
			
		$this->type='fid';
		
		$this->assign('stream', $stream);
	  $this->display('monitor.html');
	  }
	public function subscribe(){
			$this->getuserinfo();
		if($_POST){
			$nosubscribe=false;
			if(!$this->userinfo['subscribe_time']){
			
			$access_token=getAccessToken($this->config['appid'],$this->config['appsecret']);
			$user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$this->userinfo['openid'].'&lang=zh_CN';
		//转成对象
		$user_info = file_get_contents($user_info_url);
		if (isset($user_info->errcode)) {
			$this->msg($user_info->errmsg, 0);
		}
		$data = json_decode($user_info, true);
		
		if($data['subscribe_time']){
			$res['openid']=$data['openid'];
		$res['nicename']=$data['nickname'];
		$res['headimgurl']=$data['headimgurl'];
		$res['sex']=$data['sex'];
		$res['city']=$data['city'];
		$res['country']=$data['country'];
		$res['province']=$data['province'];
		$res['subscribe_time']=$data['subscribe_time'];
		$res['unionid']=$data['unionid'];
		$res['groupid']=$data['groupid'];
		
		$uid=model('comment')->wechat_add($res);
		}else{
			$nosubscribe=true;
			}
			}
		$uid=$this->userinfo['uid'];
		$aid=intval($_POST['aid']);
		$type=$_POST['type'];
		$data=array('uid'=>$uid,
					'aid'=>$aid,
					'type'=>$type
					);
		if(model('content')->subscribeinfo($data)){
			model('content')->subscribedel($data);
			$this->msg('subscribe',1);
		}else{
			model('content')->subscribeadd($data);
			if($nosubscribe){
				$this->msg('nosubscribe',2);
				}else{
		$info= $info=model('content')->model_content($aid,3);
       
		$access_token=getAccessToken($this->config['appid'],$this->config['appsecret']);
		$url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
			$teacher=$this->teacher[$info['tid']]?$this->teacher[$info['tid']]:'';
			 $json='{
"touser":"'.$this->userinfo['openid'].'",
"template_id":"'.$this->config['subscribeid'].'",
"url":"'.$this->config['MOBILE_DOMAIN'].''.module('label')->get_aurl($info['aid']).'",
"topcolor":"#FF0000",
"data":{
"first": {
"value":"您好，您成功预约了一场直播！",
"color":"#173177"
},
"keyword1":{
"value":"'.$info['title'].'",
"color":"#173177"
},
"keyword2":{
"value":"'.$teacher.'",
"color":"#173177"
},
"keyword3":{
"value":"'.date('Y年m月d日 H:i',$info['starttime']).'",
"color":"#173177"
},
"remark":{
"value":"点击即可进入",
"color":"#173177"
}
}
}';
		$json2=json_decode(curlGet($url,'post',$json));		
			
			$this->msg('nosubscribe',1);
				}
			
			}
		
		}
		$this->msg('操作失败！',0);
		
		}
		public function   subscribemessage(){
		$timenow=time();
		$where=" and dc_subscribe.type='aid' and  dc_expand_content_livestream.starttime<".($timenow+5*60)." and  dc_expand_content_livestream.starttime>".($timenow);
		$list=model('content')->get_liveaids($where);
		
		echo date('Y-m-d: H:i');
		if(!$list){
			echo 'none';exit;
			}
		$access_token=getAccessToken($this->config['appid'],$this->config['appsecret']);
		$url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
		foreach($list as $key=>$val){
			
			$teacher=$this->teacher[$val['tid']]?$this->teacher[$val['tid']]:'';
			$json='{
"touser":"'.$val['openid'].'",
"template_id":"'.$this->config['subscribeid'].'",
"url":"'.$this->config['MOBILE_DOMAIN'].''.module('label')->get_aurl($val['aid']).'",
"topcolor":"#FF0000",
"data":{
"first": {
"value":"您好，您有1场直播马上要开始了！",
"color":"#173177"
},
"keyword1":{
"value":"'.$val['title'].'",
"color":"#173177"
},
"keyword2":{
"value":"'.$teacher.'",
"color":"#173177"
},
"keyword3":{
"value":"'.date('Y年m月d日 H:i',$val['starttime']).'",
"color":"#173177"
},
"remark":{
"value":"点击观看即可",
"color":"#173177"
}
}
}';
		$json2=json_decode(curlGet($url,'post',$json));
		
		echo '文章id:'.$val['aid'];
			}
		}
		
	public function video(){

		require_once CP_CORE_PATH . "/../ext/wxpay/log.php";
		$logHandler= new CLogFileHandler(__ROOTDIR__ . "/public/logs/".date('Y-m-d').'.log');
		$log = Log::Init($logHandler, 15);
		$data = file_get_contents("php://input");
		$json=json_decode($data,true);
		 
		Log::DEBUG($json['taskid'].":".$data);
		if($json['status']==3){
		model('content')->video_updateprocess(array('taskid'=>$json['taskid']));
		}
		echo '{"result":true}';
		}
}

?>