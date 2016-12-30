<?php
use Cdn\Request\V20141111 as Cdn; 
class contentMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    //公共列表信息
    public function common_list_where()
    {
        //排序
        $sequence=intval($_GET['sequence']);
        switch ($sequence) {
            case '1':
                $order='A.updatetime DESC';
                $where_url='1';
                break;
            case '2':
                $order='A.updatetime ASC';
                $where_url='2';
                break;
            case '3':
                $order='A.aid DESC';
                $where_url='3';
                break;
            case '4':
                $order='A.aid ASC';
                $where_url='4';
                break;
            case '5':
                $order='A.inputtime DESC';
                $where_url='5';
                break;
            case '6':
                $order='A.inputtime ASC';
                $where_url='6';
                break;
            case '7':
                $order='A.views DESC';
                $where_url='7';
                break;
            case '8':
                $order='A.views ASC';
                $where_url='8';
                break;
        }
        if(!empty($order)){
        $order=$order.',';
        $where_url='-sequence-'.$where_url;
        }

        //状态
        $status=intval($_GET['status']);
        switch ($status) {
            case '1':
                $where=' AND A.status=1';
                $where_url='1';
                break;
            case '2':
                $where=' AND A.status=0';
                $where_url='2';
                break;
        }
        if(!empty($status)){
        $where_url='-status-'.$where_url;
        }

        //搜索
        $search=in(urldecode($_GET['search']));
        if(!is_utf8($search)){
            $search=auto_charset($search);
        }
        if(!empty($search)){
        $where=' AND A.title like "%' . $search . '%"';
        $where_url='-search-'.urlencode($search);
        }

        //推荐位
        $position=intval($_GET['position']);
        if(!empty($position)){
            $where=' AND D.pid='.$position;
            $where_url='-position-'.$position;
        }
        return array(
            'order'=>$order,
            'where'=>$where,
            'url'=>$where_url
            );

    }
    public function common_list()
    {
        $position_list=model('position')->position_list();
        $category_list=model('category')->category_list();
        $model_info=module('content_category')->get_model();
        $data['position_list']=$position_list;
        $data['category_list']=$category_list;
        $data['model_info']=$model_info;
        //权限部分
        if(model('user_group')->model_power('content','past')){
            $data['past_power']=true;
        }
        if(model('user_group')->model_power('content','cancel')){
            $data['cancel_power']=true;
        }
        if(model('user_group')->model_power('content','del')){
            $data['del_power']=true;
        }
        if(model('user_group')->model_power('content','edit')){
            $data['edit_power']=true;
        }
        return $data;
    }

    //公共调用信息
    public function common_info($id,$status=false)
    {
        if($status){
            $info=model('content')->info($id);
            $info_data=model('content')->info_content($id);
            $position_array=model('position')->relation_array($id);
            $file_id=model('upload')->get_relation('content',$id);
			$functions=model('content')->functions_list(array('aid'=>$id));
            $cid=$info['cid'];
            $action_name='编辑';
            $action='edit';
        }else{
            $cid=$id;
            $action_name='添加';
            $action='add';
        }
        $class_info = model('category')->info($cid);
        $category_list=model('category')->category_list();
        $subject=model('diyfield')->field_list(2);
		$grade=model('diyfield')->field_list(1);
		$school=model('school')->school_list();
		$teacher=model('teacher')->model_list();
		
        $position_list=model('position')->position_list();
        $tpl_list=model('category')->tpl_list();

        $data['info']=$info;
        $data['info_data']=$info_data;
		$data['zidingyi']=unserialize($info_data['zidingyi']);
		$data['info']['field_lists']=unserialize($info['field_lists']);
		if(!is_array($data['info']['field_lists'])){
			$data['info']['field_lists']=array();
			}
		$data['info']['auditfield_lists']=unserialize($info['auditfield_lists']);
		if(!is_array($data['info']['auditfield_lists'])){
			$data['info']['auditfield_lists']=array();
			}
		 $data['functions']=$functions;
		$data['content1']=$info_data['content1'];
		$data['content2']=$info_data['content2'];
        $data['position_array']=$position_array;
        $data['file_id']=$file_id;
        $data['action_name']=$action_name;
        $data['action']=$action;
        $data['class_info']=$class_info;
        $data['category_list']=$category_list;
        $data['subject']=$subject;
		$data['grade']=$grade;
		$data['school']=$school;
		$data['teacher']=$teacher;
        $data['position_list']=$position_list;
        $data['tpl_list']=$tpl_list;

        return $data;
    }

    //公共保存检测信息
    public function common_data_check($data)
    {
        model('expand_model')->content_check($data);
    }


    //获取关键词
    public function get_keyword(){
        
        $title=$_POST['title'];
        $content=$_POST['content'];
        $keyword=model('content')->get_keyword($title,$content);
        if(!empty($keyword)){
            $this->msg($keyword);
        }else{
            $this->msg('暂时无法获取到关键词！',0);
        }
    }

    // 内容列表
    public function index()
    {
    	$id=intval($_GET['id']);
		$id=$id?$id:'13';
        $this->alert_str($id,'int');
        //获取公共信息条件
        $where=$this->common_list_where();
        $this->view()->assign($this->common_list());
		$this->school=model('school')->school_list();
        //栏目信息
        $this->class_info = model('category')->info($id);
        //分页信息
        $listRows=9;
        $url = __URL__ . '/index/id-' . $id . '-page-{page}'.$where['url'].'.html'; //分页基准网址
        $limit=$this->pagelimit($url,$listRows);
        //内容列表
        $this->list=model('content')->content_list($id,$limit,$where['where'],$where['order']);
        $count=model('content')->count($id,$where['where']);
        $this->page=$this->page($url, $count, $listRows);
        $this->show();
    }
	public function teacher(){
		$data=$_POST;
		if($data['sid']){
			$where['sid']=$data['sid'];
			}
		if($data['gid']){
			$where['gid']=$data['gid'];
			}
		   $list=model('teacher')->model_list($where);
		; 

		echo  json_encode($list);
		}
    public function common_data_info()
    {
        $model_info=module('content_category')->get_model();
        if(!empty($model_info['befrom'])){
            $befrom=explode("\n",$model_info['befrom']);
            foreach ($befrom as $value) {
                $befrom_list[]=$value;
            }
        }
        $data['model_info']=$model_info;
        $data['befrom_list']=$befrom_list;
		
        return $data;
    }

    //内容添加
    public function add()
    {	$this->actionname='回看发布';
        $cid=intval($_GET['cid']);
        $this->alert_str($cid,'int');
        $this->view()->assign($this->common_info($cid));
        $this->view()->assign($this->common_data_info($cid));
			$this->field_list=model('form')->field_list(4);
        $this->show('content/info');
    }

    //内容保存
    public function add_save()
    {
//        model('content')->move($_POST['videourl']);
//        $_POST['videourl'] = str_replace('/video/', '/move/', $_POST['videourl']); 
        /*hook*/
        $_POST=$this->plus_hook_replace('content','add_replace',$_POST);
        /*hook end*/
        $this->common_data_check($_POST);
			$_POST['field_lists']=serialize($_POST['field_lists']);
			$_POST['auditfield_lists']=serialize($_POST['auditfield_lists']);
    	//保存内容信息
    	$_POST['aid']=model('content')->add_save($_POST);
    	model('content')->add_content_save($_POST);
        /*hook*/
        $this->plus_hook('content','add',$_POST);

        /*hook end*/
    	$this->msg('内容添加成功！',1);
    }
	
	//直播时间点
	public function livetime(){
		$id=intval($_GET['id']);
        $this->alert_str($id,'int');
	
		$this->live=model('live')->get_live($id);
	
		$this->view()->assign($this->common_info($id,true));
       	$this->display('content/livetime');
	}
	
	public function livetime_save(){
			$id=intval($_POST['aid']);
       		$this->alert_str($id,'int');
			$content=model('content')->info($id);
			$starttime=strtotime($_POST['starttime']);
			$endtime=strtotime($_POST['endtime']);
			$time=($endtime-$starttime)/60;
			$data=array('time'=>$time,'starttime'=>$starttime);
			$flag=model('live')->streamtime(array('aid'=>$id),$data);
			
			//阿里云
		if(($content['tpl']=='alifriendtpl.html'|| $content['tpl']=='friendtpl.html')&&!$content['videourl']){
		$live=model('live')->get_live($content['aid']);
		if($live['starttime']&&$live['time']){
			$stream= model('live')->get_url($content['channel']);
				date_default_timezone_set('UTC');
			
			require(CP_CORE_PATH . '/../ext/aliyunsdk/aliyun-php-sdk-core/Config.php');
					
$iClientProfile = DefaultProfile::getProfile("cn-hangzhou",$this->config['AccessKeyId'], $this->config['AccessSecret']);
			$client = new DefaultAcsClient($iClientProfile); 
			$request = new Cdn\CreateLiveStreamRecordIndexFilesRequest();  
			$request->setMethod("GET");
			$request->setDomainName($this->config['DomainName']);  
			$request->setAppName($this->config['AppName']);
			$request->setStreamName($stream);
	
			$request->setOssEndpoint($this->config['OssEndpoint']);
			$request->setOssBucket($this->config['OssBucket']);
			$request->setOssObject('live/'.$stream.'-'.$content['aid'].'.m3u8');
			$request->setStartTime(date('Y-m-d\TH:i:s\Z',$live['starttime']));
			$request->setEndTime(date('Y-m-d\TH:i:s\Z',($live['starttime']+$live['time']*60)));
			$response = $client->getAcsResponse($request); 

			if($response->RecordInfo->RecordUrl){
				$info['info']['videourl']=$response->RecordInfo->RecordUrl;
				if(!model('content')->video_info(array('vurl'=>$info['info']['videourl']))){
					$array=array(
							'vurl'=>$info['info']['videourl'],
							'name'=>$content['title'],
							'uid'=> $this->user['id']
							);
					model('content')->video_add($array);
					}
				
				}
			
			
			}
		
		}
		$data=array('aid'=>$content['aid'],'cid'=>13);
		if($info['info']['videourl']){
		$data['videourl']=$info['info']['videourl'];	
			}
		
		$status=model('content')->edit_save($data);
		
		$this->msg('操作成功', 1);
		}
	
	public function streamend(){
			$id=intval($_POST['aid']);
       		 $this->alert_str($id,'int');
			$stream=model('live')->get_live($id);
			if($stream){
			$time=(time()-$stream['starttime'])/60;
			$data=array('time'=>$time);
			model('live')->streamtime(array('aid'=>$id),$data);
			}
			$this->msg('操作成功', 1);
		
		}

    //内容编辑
    public function edit()
    {	
		
		$this->actionname='回看编辑';
        $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$info=$this->common_info($id,true);
		$content=$info['info'];
		//阿里云
		if(($content['tpl']=='alifriendtpl.html'|| $content['tpl']=='friendtpl.html')&&!$content['videourl']){
		$live=model('live')->get_live($content['aid']);
		if($live['starttime']&&$live['time']){ 
			$stream= model('live')->get_url($content['channel']);
				date_default_timezone_set('UTC');
			
			require(CP_CORE_PATH . '/../ext/aliyunsdk/aliyun-php-sdk-core/Config.php');
					
$iClientProfile = DefaultProfile::getProfile("cn-hangzhou",$this->config['AccessKeyId'], $this->config['AccessSecret']);
			$client = new DefaultAcsClient($iClientProfile); 
			$request = new Cdn\CreateLiveStreamRecordIndexFilesRequest();  
			$request->setMethod("GET");
			$request->setDomainName($this->config['DomainName']);  
			$request->setAppName($this->config['AppName']);
			$request->setStreamName($stream);
	
			$request->setOssEndpoint($this->config['OssEndpoint']);
			$request->setOssBucket($this->config['OssBucket']);
			$request->setOssObject('live/'.$stream.'-'.$content['aid'].'.m3u8');
			$request->setStartTime(date('Y-m-d\TH:i:s\Z',$live['starttime']));
			$request->setEndTime(date('Y-m-d\TH:i:s\Z',($live['starttime']+$live['time']*60)));
			$response = $client->getAcsResponse($request); 

			if($response->RecordInfo->RecordUrl){
				$info['info']['videourl']=$response->RecordInfo->RecordUrl;
				if(!model('content')->video_info(array('vurl'=>$info['info']['videourl']))){
					$array=array(
							'vurl'=>$info['info']['videourl'],
							'name'=>$content['title'],
							'uid'=> $this->user['id']
							);
					model('content')->video_add($array);
					}
				}
			
			
			}
	
		}
		//金山
		if(($content['tpl']=='jsfriendtpl.html' || $content['tpl']=='friendtpl.html')&&!$content['videourl']){
	
		$live=model('live')->get_live($content['aid']);
		if($live['starttime']&&$live['time']){
			$stream= model('live')->get_url($content['channel']);
			$stream='wenlai10';
			require(CP_CORE_PATH . '/../ext/ks/Ks3Client.class.php');
			
			$client = new Ks3Client("Jppmsp+9+5naFIDt+yUp","dOWE1r4G9VOYJXfV3T4rBsyfLzcJKxoekXx0/I3x","ks3-cn-beijing.ksyun.com");
			 $starttime=$live['starttime'];
			 $endtime=(string)($live['starttime']+$live['time']*60);
		
			for($i=0;$i<strlen($starttime);$i++){
				 $startarray[$i]=$starttime[$i];
				
			}
			for($i=0;$i<strlen($endtime);$i++){
				 $endarray[$i]=$endtime[$i];
				
			}
			foreach($startarray as $key=>$value){
				
				if($value==$endarray[$key]){
					$str.=$value;
					}else{
						break;
						}
				
				}
				
		$args = array(
		"Bucket"=>"urrenvideo",
		"Options"=>array(
			"prefix"=>"record/live/".$stream."/hls/".$stream."-".$str,
			"delimiter"=>""
			)
		);
		$result= $client->listObjects($args);
	
		 $patt='/-'.$str.'(.*?)_/';
		$videos=array();$temp=array();
 		foreach($result['Contents'] as $key=>$value){
			
			 preg_match_all($patt,$value['Key'],$matchtemp);
			$temp['url']=$value['Key'];
			
			$temp['starttime']=intval(intval($str.$matchtemp[1][0])/1000);
			$temp['endtime']=strtotime($value['LastModified']);
			
 			if($temp['starttime']<$endtime&&$temp['endtime']>$starttime){
				$videos[]=$temp;
				}
			}		
			
			if($videos){
				//var_dump($videos);
			if(count($videos)>1){
			
				foreach($videos as $key=>$value){

					if($key>0){
		
					$file.='&file='.base64_encode('urrenvideo:'.$value['url']);
					}
				}
				$videoto="shanyueyun/".$stream.'/'.$stream.'-'.$content['aid'].'.m3u8';
				$commond='tag=avfilecat&mode=1'.$file;
				
				$args=array(
				"Bucket"=>"urrenvideo",
				"Key"=>$videos[0]['url'],
				"Adp"=>array(
				"NotifyURL"=>'http://'.$this->config['MOBILE_DOMAIN'].'/content/video',
				"Adps"=>array(
				array(
					"Command"=>$commond,
					'Key'=>$videoto
				)	
			) 
			
		)
	);
		$result = $client->putAdp($args);
		
		$taskid = $result["TaskID"];
	
		$task = $client->getAdp(array("TaskID"=>$taskid));
		$xml = get_object_vars(simplexml_load_string($task));
	
		if($xml['processstatus']==1){
			
			$videourl=$this->config['jsvideo'].$videoto;
			}
			}else{
						
			$videourl=$this->config['jsvideo'].$videos[0]['url'];		
				}
			$info['info']['videourl']=$videourl;
			$videolistinfo=model('content')->video_info(array('vurl'=>$info['info']['videourl']));
				if(!$videolistinfo){
					$array=array(
							'vurl'=>$info['info']['videourl'],
							'name'=>$content['title'],
							'uid'=> $this->user['id'],
							'taskid'=>$xml['taskid'],
							'process'=>1
							
							);
					model('content')->video_add($array);
					}else{
						$array=array(
							'id'=>$videolistinfo['id'],
							'taskid'=>$xml['taskid'],
							'process'=>1
							
							);
					model('content')->video_save($array);
						
						}
			}
			}
	
		}
		
		
        $this->view()->assign($info);
        $this->view()->assign($this->common_data_info($cid));
		$this->field_list=model('form')->field_list(4);
        $this->show('content/info');
    }

    //内容保存
    public function edit_save()
    {

        /*hook*/
        $_POST=$this->plus_hook_replace('content','edit_replace',$_POST);
        /*hook end*/
        $this->common_data_check($_POST);
			$_POST['field_lists']=serialize($_POST['field_lists']);
			$_POST['auditfield_lists']=serialize($_POST['auditfield_lists']);
        //保存内容信息
        $status=model('content')->edit_save($_POST);
        model('content')->edit_content_save($_POST);
		
        /*hook*/
        $this->plus_hook('content','edit');
        /*hook end*/
		$this->msg('内容编辑成功！',1);
       
    }

    //内容删除
    public function del()
    {
        $id=intval($_POST['aid']);
        $this->alert_str($id,'int',true);
        /*hook*/
        $this->plus_hook('content','del',$id);
        /*hook end*/
        $status=model('content')->del($id);
        model('content')->del_content($id);
        $this->msg('内容删除成功！',1);
    }
	public function video(){
		 $this->actionname='视频列表';
            $this->action='video';
			  $search=in(urldecode($_GET['search']));
        if(!is_utf8($search)){
            $search=auto_charset($search);
        }
        if(!empty($search)){
        $where[]=' name like "%' . $search . '%"';
        $where_url='search-'.urlencode($search);
        }
		
			if($this->user['gid']!=1)
			$where['uid']= $this->user['id'];
			
		 $listRows=20;
        $url = __URL__ . '/video/'.$where_url.'-page-{page}.html'; //分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		$this->list=model('content')->video_list($where,$limit);
		$count=model('content')->video_count($where);
        $this->page=$this->page($url, $count, $listRows);
		 $this->show('content/video');
		}
	public function videoadd(){
		 $this->actionname='视频添加';
            $this->action='videoadd';
			 
		 $this->show('content/videoadd');
		}
	public function videoadd_save(){
		$_POST['uid']= $this->user['id'];
		$id=model('content')->video_add($_POST);
		 $this->msg($id,1);
		}
		public function videoedit(){
		 $this->actionname='视频编辑';
            $this->action='videoedit';
			 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$this->info=model('content')->video_info(array('id'=>$id));	 
		$this->times=model('content')->times_list(array('vid'=>$this->info['id']));
		 $this->show('content/videoadd');
		}
		public function videoedit_save(){
		$id=model('content')->video_save($_POST);
		 $this->msg($id,1);
		}
		public function times_add(){
		$id=model('content')->times_add($_POST);
		 $this->msg($id,1);
		}
		
		public function times_save(){
		$id=model('content')->times_save($_POST);
		 $this->msg($_POST['id'],1);
		}
		public function timesdel(){
		 $id=intval($_POST['id']);
        $this->alert_str($id,'int',true);
       
        $status=model('content')->times_del($id);
    
        $this->msg('删除成功！',1);
		}
	public function videodel(){
		 $id=intval($_POST['id']);
        $this->alert_str($id,'int',true);
       
        $status=model('content')->video_del($id);
    
        $this->msg('删除成功！',1);
		}
	public function functions(){
		 $this->action_name='添加';
            $this->action='functions';
			 $where=$this->common_list_where();
		 $this->list=model('live')->content_list(null,null,$where['where'],$where['order'],true);	 
		 $this->display('content/functions');
		}
	public function functionsedit(){
		 $this->action_name='编辑';
            $this->action='functionsedit';
			  $id=intval($_GET['id']);
        $this->alert_str($id,'int');
			 $where=$this->common_list_where();
		 $this->list=model('live')->content_list(null,null,$where['where'],$where['order'],true);	
		$this->info=model('content')->functions_info(array('id'=>$id));	
		if($this->info['type']=='linkaid'){ 
	
		$this->content=explode(",",$this->info['content']);
		}
		 $this->display('content/functions');
		}

	public function functions_save(){
		if($_POST['type']=='linkaid')
		$_POST['content']=implode(',',$_POST['content']);
		$id=model('content')->functions_add($_POST);
		 $this->msg($id,1);
		}
	public function functionsedit_save(){
		if($_POST['type']=='linkaid')
		$_POST['content']=implode(',',$_POST['content']);
		$id=model('content')->functions_save($_POST);
		 $this->msg($id,1);
		}
	public function functionsdel(){
		 $id=intval($_POST['id']);
        $this->alert_str($id,'int',true);
       
        $status=model('content')->functions_del($id);
    
        $this->msg('删除成功！',1);
		}
    //批量操作
    public function batch(){
        if(empty($_POST['status'])||empty($_POST['id'])){
            $this->msg('请先选择内容！',0);
        }
        $id_array=substr($_POST['id'],0,-1);
        $id_array=explode(',', $id_array);
        switch ($_POST['status']) {
            case '1':
                //审核
                foreach ($id_array as $value) {
                    model('content')->status($value,1);
                }
                break;
            case '2':
                //草稿
                foreach ($id_array as $value) {
                    model('content')->status($value,0);
                }
                break;
            case '3':
                //删除
                foreach ($id_array as $value) {
                    /*hook*/
                    $this->plus_hook('content','del',$value);
                    /*hook end*/
                    model('content')->del($value);
                    model('content')->del_content($value);
                }
                break;
            case '4':
                //转移栏目
                $cid=intval($_POST['cid']);
                if(empty($cid)){
                    $this->msg('请先选择目标栏目！',0);
                }
                foreach ($id_array as $value) {
                    model('content')->edit_cid($value,intval($_POST['cid']));
                }
                break;
        }
        $this->msg('操作执行完毕！',1);

    }

	public function alimerge(){
		
		
		}
	public function downpic(){
	 	$fileurl=$_GET['pic'];
		
header('Content-type:  image/png'); 
header("Content-Disposition: attachment; filename=erweima.png"); 
readfile('../'.$fileurl);
exit;
		}
    

}

?>