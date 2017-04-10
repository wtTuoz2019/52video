<?php
//comment显示
class commentMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

    public function index() {
		$this->fid=$fid=intval($_GET['id']);
		
		 $list = model('comment')->pc_info($fid, 5);
		if($list)	
		foreach ($list as $k => $v) {
		
			$list[$k]['name'] = model('comment')->getname($v['uid']);
			$list[$k]['pic'] = model('comment')->get_pic($v['uid']);	
			$list[$k]['time'] = model('comment')->get_time($v['time']);
			$list[$k]['num'] = model('comment')->comment_n($v['id']);
			$list[$k]['res'] = model('comment')->comment_reply($v['id'],$fid);
			$list[$k]['praise'] = model('comment')->laud_num($v['id']);
			$list[$k]['praiselist'] = model('comment')->laud_list($v['id']);
			$list[$k]['images']=explode('|',$v['images']); 
			 $id=$v['id'];
		}
	   $this->pageindex=intval($id);
		$this->commentlist=$list;
		$this->headpic=model('comment')->get_pic('', $_SESSION['uid']);
		$this->nickname=model('comment')->getname($_SESSION['uid']);
     	$this->display('comment.html');
	}
	
	 public function save() {
		 $this->getuserinfo();
		 
		if($_POST['formdata']){
			$formstring=explode('|',$_POST['formdata']);
			foreach($formstring as $k=>$v){
				if($v){
					$temp=explode('_',$v);
					$formdata[$temp[0]]=$temp[1];
					
					}
				}
			
			}
			
 		$data['fid']=$_POST['fid']; 
		$data['message']=$_POST['message'];
		$data['images']=$_POST['images']; 
		$data['progress']=floatval($_POST['progress']);
		$data['progressend']=floatval($_POST['progressend']);
		$data['time']=time();
		$uid=$this->userinfo['uid'];//通过微信获取用户id
		
	 		$data['uid']=$uid;
			$data['type']='wechat';
			$data['nicename']=$this->userinfo['nicename'];
			if(model('comment')->contentinfo($data['fid'])){
			$data['flag']=0;
				}else{
			$data['flag']=1;
					}
			$res=model('comment')->comment($data);
			
			if($res>0){
				module('selfform')->formin($formdata);
				$content=model('content')->info($data['fid']);
				if($content['redpacket']&&!$content['comment']){
					$priceall=model('comment')->priceall($content['aid']);
					if($content['quota']>$priceall+$content['uprice']){
						$rerult=module('wxpay')->sendredlog($content['uprice'],$this->userinfo['openid'],$content['title']);						if($rerult['result_code']=='SUCCESS')
						model('comment')->addredlog(array('uid'=>$uid,'aid'=>$content['aid'],'price'=>$content['uprice'],'dateline'=>time()));
						
						}
					
					}
				
				
				$this->msg($rerult, 1);
			}else{
				$this->msg('评论失败', 0);
			}	
	 	
	 }
	 public function reply_add(){
		$data['time']=time();
		$data['uid']=$_SESSION['uid'];
		$data['fid']=$_POST['id'];
		$data['pid']=$_POST['pid'];
		$data['toname']=$_POST['toname'];
		$data['type']="content";
		$data['message']=$_POST['mes'];
		$data['nickname']=$_SESSION['nickname'];
		if($data['uid'] > 0){
		if(model('comment')->contentinfo($data['fid'])){
		$data['flag']=1;
		}else{
			$data['flag']=1;
					}
			
			$id=model('comment')->save($data);
			$this->msg($id,1);
		 }else{
			$this->msg('请先登录！',0); 
		}	
		
	}
	  public function savepay() {
 		$data['fid']=$_POST['fid']; 
		$data['message']=$_POST['message'];
		$data['images']=$_POST['images']; 
		$data['time']=time();
		$uid=$_SESSION['uid'];//通过微信获取用户id
		if ($uid>0) {
	 		$data['uid']=$uid;
			$data['type']='wechatpay';
			$data['pay']=0;
			$data['price']=$_POST['price'];
			$data['nicename']=model('comment')->getname($uid);
			$res=model('comment')->comment($data);
			
			if($res>0){
				
				$this->msg($res, 1);
			}else{
				$this->msg('评论失败', 0);
			}	
	 	}else{
	 		$this->msg('未授权', 0);
	 	}
	 }

public function editpay() {
	$data=array('id'=>intval($_GET['id']),'pay'=>1);
	return model('comment')->edit_save($data);
	
}
	 
	 public function getCode(){
		if (isset($_GET['code'])){
			echo $_GET['code'];
		}else{
			echo "NO CODE";
		}	 
	 }
	 
	  public function Sharepic(){
		$img = $_POST['img'];
		
		if(empty($img))
		{
			return false;
		}
	
		// 获取图片
		list($type, $data) = explode(',', $img);

		// 判断类型
		if(strstr($type,'image/jpeg')!=='')
		{
			$ext = '.jpg';
		}elseif(strstr($type,'image/gif')!=='')
		{
			$ext = '.gif';
		}elseif(strstr($type,'image/png')!=='')
		{
			$ext = '.png';
		}
		//$userinfo = session('mam_spo_uinfo');
		//$uid = $userinfo['id'];
		//$model = M('Sharepic');
	
		// 生成的文件名
		$photo ='/upload/sharepic/'.time().$ext;
		$fopen    =   fopen('.'.$photo,   'wb');
		fputs($fopen,   base64_decode($data));//向文件中写入内容; 
		fclose($fopen); 
		// 生成文件
		//file_put_contents($photo, base64_decode($data), true);
		// 返回
		header('content-type:application/json;charset=utf-8');
		$ret = array('img'=>$photo,'msg'=>'图片上传成功');
		echo json_encode($ret);		
		
		exit; 
	 }
	 
	
	 
	 public function list_() {
		$aid = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$vMp4url=addslashes($_GET['vMp4url']);
		if($aid < 0 && !isset($vMp4url)){
			echo "参数非法！";
            exit;
		}
		if($vMp4url){
			
			$id = model('content')->get_channel_id($vMp4url);	
			if($id)
			$zid = model('content')->get_content_id($id);
        }
		 $fid = isset($zid) ? $zid : $aid;
		$this->info= $info=model('content')->info($fid);
		$this->fid = $fid;
		if (!$info) {
			echo "该流暂时无直播";
            exit;
		}
		$this->type=isset($_GET['type']) ?$_GET['type'] :'fid';
        $this->title = model('comment')->get_t($fid);
     	$this->display('commentlist.html');
		
	}
	
		 public function commentlist() {
		$aid = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$vMp4url=addslashes($_GET['vMp4url']);
		if($aid < 0 && !isset($vMp4url)){
			echo "参数非法！";
            exit;
		}
		if(isset($vMp4url)){
			$id = model('content')->get_channel_id($vMp4url);	
			$zid = model('content')->get_content_id($id);
        }
		 $fid = isset($zid) ? $zid : $aid;
		$this->fid = $fid;
		if (!$fid) {
			echo "该流暂时无直播";
            exit;
		}
        $this->title = model('comment')->get_t($fid);
     	$this->display('commentlist.html');
		
	}
	public function stream(){
		$where=array(
				'fid'=>intval($_POST['fid']),
				'sid'=>intval($_POST['sid']),
				'type'=>$_POST['type']
				);
		
		$data=array($_POST['act']=>time());
		$res=model('comment')->stream($where,$data);
		if(!$res){
			$this->msg('操作失败', 0);
		}else{
			if($_SESSION['uid']){
			$data=array(
					'uid'=>$_SESSION['uid'],
					'fid'=>intval($_POST['fid']),
					'sid'=>intval($_POST['sid']),
					'type'=>$_POST['act'],
					'streamtime'=>time(),
					'dateline'=>time()
					);
			model('comment')->addstreamlog($data);
			}
			$this->msg('操作成功', 1);
		}
		}
		public function streamstart(){
		$where=array(
				'fid'=>intval($_POST['fid']),
				'sid'=>intval($_POST['sid']),
				'type'=>$_POST['type']
				);
	
		$data=array('starttime'=>strtotime($_POST['time']),'endtime'=>0);
		$res=model('comment')->stream($where,$data);
		if(!$res){
	
			$this->msg('操作失败', 0);
		}else{
			
			if($_SESSION['uid']){
				$datalog=array(
					'uid'=>$_SESSION['uid'],
					'fid'=>intval($_POST['fid']),
					'sid'=>intval($_POST['sid']),
					'type'=>'starttime',
					'streamtime'=>time(),
					'dateline'=>time()
					);
		
			model('comment')->addstreamlog($datalog);
			}
				$data=array('starttime'=>strtotime($_POST['time']));
			model('comment')->streamtime(array('aid'=>intval($_POST['fid'])),$data);
			$this->msg('操作成功', 1);
		}
		}
		public function streamend(){
		$where=array(
				'fid'=>intval($_POST['fid']),
				'sid'=>intval($_POST['sid']),
				'type'=>$_POST['type']
				);
	
		$data=array('endtime'=>time());
		$res=model('comment')->stream($where,$data);
		if(!$res){
	
			$this->msg('操作失败', 0);
		}else{
				if($_SESSION['uid']){
				$data=array(
					'uid'=>$_SESSION['uid'],
					'fid'=>intval($_POST['fid']),
					'sid'=>intval($_POST['sid']),
					'type'=>'endtime',
					'streamtime'=>time(),
					'dateline'=>time()
					);
			model('comment')->addstreamlog($data);
				}
			$stream=model('comment')->livestream(array('aid'=>intval($_POST['fid'])));
			$time=(time()-$stream['starttime'])/60;
			$data=array('time'=>$time);
			model('comment')->streamtime(array('aid'=>intval($_POST['fid'])),$data);
			$this->msg('操作成功', 1);
		}
		}
	public function streamstatus(){
		
		$where=array(
				'fid'=>intval($_POST['fid']),
				'sid'=>intval($_POST['sid']),
				'type'=>$_POST['type']
				);
		$info=model('comment')->streaminfo($where);
		
	
		if($info){
		
			$res=(($info[$_POST['act']])<time()&&$info[$_POST['act']])?true:false;
			
			if($res){
			$this->msg('操作成功', 1);
			}
			}
		$this->msg('操作失败', 0);
			
		}
	 public function ajax_list() {
		$fid=intval($_POST['fid']);
		$limit = '1';
		$pageIndex=$_POST['pageIndex'];
	
		$this->fid=$fid;
		if(!$fid){
            $this->msg('参数非法', 0);exit;
        }
		$type=isset($_GET['type']) ?$_GET['type'] :'fid';
        $info = model('comment')->info_($fid, $pageIndex,$type);
		
		if(!$info){
			$this->msg('暂无评论', 0);exit;
		}
	
		foreach ($info as $k=>$v) {
			$pageIndex=$v['id']>$pageIndex?$v['id']:$pageIndex;
			$info[$k]['name'] = model('comment')->getname($v['uid']);	
			$info[$k]['pic'] = model('comment')->picture($v['uid']);
			
			$info[$k]['t'] = date("Y-m-d h:i", $v['time']);
			if ($v['type'] == 'wechat'||1) {
				$info[$k]['pic'] = model('comment')->get_pic($v['uid']);
			}else{
				$info[$k]['pic'] = model('comment')->picture($v['uid']);
			}
		}
		$data['pageIndex']=$pageIndex;
		$data['info']=$info;
		$this->msg($data, 1);
	}
	
public function commentdel() {
		$id=intval($_POST['id']);	
		$where['id']=$id;
		$where['uid']=$_SESSION['uid'];
		$res=model('comment')->comment_del($where);
		if(!$res){
			$this->msg('删除失败', 0);
		}else{
			$wherepid['pid']=$id;
			$res=model('comment')->comment_del($wherepid);	
			$this->msg('删除成功', 1);
		}
	}
	public function resdel() {
		$id=intval($_POST['id']);	
		$where['id']=$id;
		$where['uid']=$_SESSION['uid'];
		$res=model('comment')->comment_del($where);
		if(!$res){
			$this->msg('删除失败', 0);
		}else{
		
			$this->msg('删除成功', 1);
		}
	}
	
	public function pc_list() {
		$fid=intval($_POST['data']['fid']);
		$id = isset($_POST['data']['id']) ? $_POST['data']['id'] : 0;
		
		if(!is_numeric($fid)){
            $this->msg('参数非法', 0);
        }
		 $content=model('content')->info($fid);
        if (!is_array($content)) {
			
            $this->error404();
        }else{
			
			if($content['signup']){
			$aid=$fid;
				$signup=true;		
				
				}
		if($content['activity_id']){
			$activity=model('content')->info($content['activity_id']);
			if($activity['signup']){
				$aid=$activity['aid'];
				$signup=true;		
				
				}
				}	
			
			}
		
		$info = model('comment')->pc_info($fid, $_POST['data']['pageSize'],$_POST['data']['pageIndex']);		
		
		$fkeywords=explode('，',$this->config['fkeywords']);
		if($info)
		foreach ($info as $k => $v) {
			
			$info[$k]['time'] = model('comment')->get_time($v['time']);
			$info[$k]['num'] = 0;
			$info[$k]['res'] = model('comment')->comment_reply($v['id'],$signup,$aid);
			$info[$k]['praiselist'] = model('comment')->laud_list($v['id']);
				if($signup){
				
				$signinfo=model('form_list')->signinfo(array('uid'=>$v['uid'],'aid'=>$aid));
				
				if($signinfo['status']){
				$userinfo=model('form_list')->infobyuser($v['uid'],'signup');
				$info[$k]['name']=$userinfo['name']?$userinfo['name']:$info[$k]['name'];
				$info[$k]['school']=$userinfo['school']?$userinfo['school']:$info[$k]['school'];
				$info[$k]['school']=$userinfo['company']?$userinfo['company']:$info[$k]['school'];
					
					}
				}
			
			foreach($fkeywords as $key=>$value){
				if((string)strpos($v['message'],$value)=='0'||strpos($v['message'],$value)>0){
					$info[$k]['status']=0;
					continue ;
					};
				}
			 $pageindex=$v['id'];
		}
		
		$data['pageindex']=intval($pageindex);
		if($info)
		$data['count']=count($info);
		else
		$data['count']=0;
		$data['info']=$info;
		$this->msg($data, 1);
	}

	public function pc_auto() {
		$fid=intval($_POST['data']['fid']);
		$id = isset($_POST['data']['id']) ? $_POST['data']['id'] : 0;
		if(!is_numeric($fid)){
            $this->msg('参数非法', 0);
        }
		$count = model('comment')->pc_auto_count($fid, $id);
		if($count == 0){
			$this->msg('暂无评论', 0);
		}
		$info = model('comment')->pc_auto_info($fid, $id);
		
			$fkeywords=explode('，',$this->config['fkeywords']);
		foreach ($info as $k => $v) {
			
			$info[$k]['time'] = model('comment')->get_time($v['time']);
			$info[$k]['num'] = 0;
			$info[$k]['res'] = 0;
			$info[$k]['praise'] = 0;
			foreach($fkeywords as $key=>$value){
				if(strpos($v['message'],$value)){
					$info[$k]['status']=0;
					continue ;
					};
				}
			
		}
		$data['count']=intval($count);
		$data['info']=$info;
		$this->msg($data, 1);
	}
	
	
	public function pc_autoflag() {
		$fid=intval($_POST['data']['fid']);
		$id = isset($_POST['data']['id']) ? $_POST['data']['id'] : 0;
		if(!is_numeric($fid)){
            $this->msg('参数非法', 0);
        }
		$count = model('comment')->pc_auto_count($fid, $id);
		if($count == 0){
			$this->msg('暂无评论', 0);
		}
		
		$this->msg($count, 1);
	}
	
	public function pc_up() {
		$fid=intval($_POST['fid']);
		$time=isset($_POST['time']) ? $_POST['time'] : time();
		if(!$fid){
            $this->msg('参数非法', 0);
        }
		$count = model('comment')->pc_count_up($fid, $time);
		if($count == 0){
			$this->msg('暂无评论', 0);
		}
		$info = model('comment')->pc_up($fid, $time);
		
		foreach ($info as $k => $v) {
			$info[$k]['name'] = model('comment')->getname($v['uid']);
			$info[$k]['pic'] = model('comment')->get_pic($v['uid']);	
			$info[$k]['time'] = model('comment')->get_time($v['time']);
			$info[$k]['num'] = model('comment')->comment_n($v['id']);
			$info[$k]['res'] = model('comment')->comment_reply($v['id'],$fid);
			$info[$k]['praise'] = model('comment')->laud_num($v['id']);
		}
		$data['time']=time();
		$data['info']=$info;
		$this->msg($data, 1);
	}
}