<?php
class myhomeMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
		$this->getuserinfo();
    }

    public function index()
    {	
		 $list=model('comment')->mycomment($_SESSION['uid']);
			if(is_array($list)){
			$fkeywords=explode('，',$this->config['fkeywords']);
		 foreach($list as $k=>$v){
			 	$list[$k]['aid']=$list[$k]['fid'];
				
				$list[$k]['videourl']=model('content')->getvideourl($list[$k]['aid']);	
				if($list[$k]['videourl']){
				if(strstr($info['videourl'],"heims")||strstr($info['videourl'],"aodianyun")||strstr($info['videourl'],"urren")||strstr($info['videourl'],"shanyueyun")){
				}else{
				$list[$k]['videourl']="http://".$this->config['out']."/video/".$list[$k]['videourl']."/video.m3u8";	
					}
				}
					
				$list[$k]['res'] = model('comment')->comment_reply($v['id'],$v['fid']);
				$list[$k]['rescount']=count($list[$k]['res']); 
				foreach($fkeywords as $key=>$value){
				if((string)strpos($v['message'],$value)=='0'||strpos($v['message'],$value)>0){
					unset($list[$k]);
					continue ;
					};
				}
				$id=$v['id'];
				
					}
			}
			
			$this->myarticle=model('comment')->mycommentarticle($_SESSION['uid']);
			$mycommentid=model('comment')->mycommentid($_SESSION['uid']);
			$wherec="id in (".implode(",",$mycommentid).")";
			$wherel="cid in (".implode(",",$mycommentid).")";
			$wherer="pid in (".implode(",",$mycommentid).")";
			$data['mycommentnum']=model('comment')->getcommentcount($wherec);
			$data['mycommentreplynum']=model('comment')->getcommentcount($wherer);
			$data['mycommentlaudnum']=model('comment')->getlaudnum($wherel);
			$wherebec='uid='.$_SESSION['uid']." and pid<>0";
			 $whereber='uid='.$_SESSION['uid']." and pid<>0";
			$wherebel='uid='.$_SESSION['uid'];
			$data['becommentnum']=model('comment')->getbecommentcount($wherebec);
			$data['becommentreplynum']=model('comment')->getcommentcount($whereber);
			$data['becommentlaudnum']=model('comment')->getlaudnum($wherebel);
		$where=array();
		$where['uid']=$_SESSION['uid'];
		$where['cid']=array('<>',0);
		$data['alltime']=intval(model('data')->looktimesum($where)/60);
       	$where['cid']='13';
		$data['fieldtime']=intval(model('data')->looktimesum($where)/60);
		 	$where['cid']='16';
		$data['livetime']=intval(model('data')->looktimesum($where)/60);
			$this->mydata=$data;
			$this->loop=$list;
		$this->pageIndex=$id;
	 $this->display('user.html');	
	}
	
	public function getcomment(){
		 $list=model('comment')->mycomment($_SESSION['uid'],$_POST['pageIndex']);
		
		 	if(is_array($list)){
			$fkeywords=explode('，',$this->config['fkeywords']);
		 foreach($list as $k=>$v){
			 	$list[$k]['aid']=$list[$k]['fid'];
				
				$list[$k]['videourl']=model('content')->getvideourl($list[$k]['aid']);	
				if($list[$k]['videourl']){
				if(strstr($info['videourl'],"heims")||strstr($info['videourl'],"aodianyun")||strstr($info['videourl'],"urren")||strstr($info['videourl'],"shanyueyun")){
				}else{
				$list[$k]['videourl']="http://".$this->config['out']."/video/".$list[$k]['videourl']."/video.m3u8";	
					}
				}
					$list[$k]['time'] = model('comment')->get_time($v['time']);
				$list[$k]['res'] = model('comment')->comment_reply($v['id'],$v['fid']);
				$list[$k]['rescount']=count($list[$k]['res']); 
				foreach($fkeywords as $key=>$value){
				if((string)strpos($v['message'],$value)=='0'||strpos($v['message'],$value)>0){
				$list[$k]['status']=0;
					continue ;
					};
				}
				
				
					}
			}
			
		$this->msg($list, 1);
		}
	public function getvideos(){
		
		$list=model('comment')->mycommentarticle($_SESSION['uid'],$_POST['index']);
		if(is_array($list)){
		 foreach($list as $k=>$v){
			 $list[$k]['time']=date('Y-m-d H:i',$v['updatetime']);
		 }}
		$this->msg($list, 1);
		}
	public function addlive(){
		if($_POST){
			
		$starttime=strtotime(date('Y').$_POST['month'].$_POST['date'].$_POST['hour'].$_POST['miunte']);		$timenow=time();
			$array=array(
						'title'=>$_POST['title'],
						'desc'=>$_POST['desc'],
						'starttime'=>$starttime,
						'inputtime'=>$timenow,
						'updatetime'=>$timenow,
						'uid'=>$_SESSION['uid']
						);
				
			$res=model('user')->addlive($array);
			$this->msg('添加成功',1); 
			
			}
		$this->display('liveinfo.html');
		}
	public function set(){
			if($_POST){
			$res=model('user')->edit($_POST);
			$this->msg('添加成功',1); 
			}
		$this->display('userset.html');
		}
}
?>