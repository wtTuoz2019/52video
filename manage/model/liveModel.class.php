<?php
class liveModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function get_starttime($aid){
	 $data = $this->model->table('expand_content_livestream')->field('starttime')->where(array('aid'=>$aid))->find();
     return $data['starttime'];	
		
	}
    //内容列表
    public function content_list($cid,$limit=null,$where=null,$order=null,$all=false,$addtable=array())
    {   
        if(!$all){
            if(empty($cid)){
                return;
            }
            $where_cid='A.cid='.$cid;
        }
        if(!empty($addtable)){
            $tablelist='';
            foreach ($addtable as $value) {
                $tablelist.=$value;
            }
        }
        $position=intval($_GET['position']);
        if($position){
            $position_sql=" LEFT JOIN {$this->model->pre}position_relation D ON D.aid = A.aid ";
        }
		 $user=model('user')->current_user();
		 
		 if($user['gid']==6){
					$temp;$temp[]=0;
			if($user['cid']){
				$temp[]=$user['cid'];
				}
			$nextuser=model('user')->admin_list(' AND pid='.$user['id']);
			if($nextuser){
			foreach($nextuser as $key=>$val){
				$temp[]=$val['cid'];
				}
			} 
			$whereuid=" AND A.csid  in (".implode(',',$temp).") ";
			 }else{
		if($user['cid'])	
	 	$whereuid=" AND A.csid =".$user['cid'];
			 }
        if(!empty($where)||!empty($where_cid)){
            $where="WHERE {$where_cid}{$where}";
        }else{
			 $where="WHERE A.aid>0";
			}
		if($limit){
			$limit='LIMIT '.$limit;
			}
        $sql="
            SELECT A.*,B.name as cname,B.mid,C.admin_content
             FROM {$this->model->pre}content A 
             LEFT JOIN {$this->model->pre}category B ON A.cid = B.cid
             LEFT JOIN {$this->model->pre}model C ON C.mid = B.mid
             {$position_sql}
             {$tablelist}
             {$where}{$whereuid} ORDER BY {$order}A.updatetime DESC,A.aid DESC {$limit}
            ";
        $data=$this->model->query($sql);
        return $data;
    }
	
	public function get_url($id){
		$data = $this->model->table('device')->field('sn')->where('id='.$id)->find();
		return $data['sn'];
	}

    //获取内容统计
	public function count($cid,$where=null,$all=false,$addtable=array()) {
		if(!$all){
            if(empty($cid)){
                return;
            }
            $where_cid='A.cid='.$cid;
        }
        $position=intval($_GET['position']);
        if($position){
            $position_sql=" LEFT JOIN {$this->model->pre}position_relation D ON D.aid = A.aid ";
        }
		 $user=model('user')->current_user();
		 if($user['gid']==6){
				$temp;$temp[]=0;
			if($user['cid']){
				$temp[]=$user['cid'];
				}
			$nextuser=model('user')->admin_list(' AND pid='.$user['id']);
			if($nextuser){
			foreach($nextuser as $key=>$val){
				$temp[]=$val['cid'];
				}
			} 
			$whereuid=" AND A.csid  in (".implode(',',$temp).") ";
			 }else{
		if($user['cid'])	
	 	$whereuid=" AND A.csid =".$user['cid'];
			 }
        if(!empty($where)||!empty($where_cid)){
            $where="WHERE {$where_cid}{$where}";
        }
        $sql="
            SELECT count(*) as num
             FROM {$this->model->pre}content A 
             LEFT JOIN {$this->model->pre}category B ON A.cid = B.cid
             LEFT JOIN {$this->model->pre}model C ON C.mid = B.mid
             {$position_sql}
             {$tablelist}
             {$where}{$whereuid}
            ";
        $data=$this->model->query($sql);
        return $data[0]['num'];
	}

    //字段保存格式化
    public function common_data_save($data)
    {
        $data['updatetime']=strtotime($data['updatetime']);
        if(empty($data['updatetime'])){
            $data['updatetime']=time();
        }
        if(is_array($data['position'])){
            $position='';
            foreach ($data['position'] as $value) {
                $position.=$value.',';
            }
            $data['position']=substr($position, 0,-1);
        }
        if(empty($data['position'])){
            $data['position']='';
        }
        $data['urltitle']=$this->get_urltitle($data['title'],$data['urltitle'],$data['aid']);
        $data['taglink']=intval($data['taglink']);
		
        return $data;
    }

    //内容字段处理
    public function common_content_save($data)
    {	
	
		
	
		$data['content1']=html_in($data['zidingyi']['content1']['content']);
		unset($data['zidingyi']['content1']['content']);
		
		$data['content2']=html_in($data['zidingyi']['content2']['content']);
			unset($data['zidingyi']['content2']['content']);
		$data['zidingyi']=serialize($data['zidingyi']);
		
	
        $data['content']=html_in($data['content']);
	
        return $data;
    }

	//基础表信息
	public function add_save($data)
    {
        //格式化部分字段
        $data=$this->common_data_save($data);
        $data['inputtime']=time();
        //录入数据
        $aid=$this->model->table('content')->data($data)->insert(); //录入基本信息
		if($data['functions']){
			foreach($data['functions'] as $key=>$val){
				  model('content')->functions_save(array('id'=>$val,'aid'=>$aid));
				}
			}
        model('tags')->content_save($data['keywords'],$aid); //处理TAG
        model('position')->add_content_save($data['position'],$aid); //保存推荐位
        model('expand_model')->add_content_save($data,$aid);  //录入扩展模型数据
        model('upload')->relation('content',$data['file_id'],$aid); //录入附件表
        /*hook*/

        $this->plus_hook('content','add_data',$data);
        /*hook end*/
        return $aid;
    }

    //内容表信息
    public function add_content_save($data)
    {
        //格式化部分字段
        $data=$this->common_content_save($data);
		
		
        return $this->model->table('content_data')->data($data)->insert();
    }

    //获取内容基本信息
    public function info($aid)
    {
        return $this->model->table('content')->where('aid='.$aid)->find();
    }

    //获取附加内容
    public function info_content($aid)
    {
        return $this->model->table('content_data')->where('aid='.$aid)->find();
    }

    //基础表信息
    public function edit_save($data,$ext=true)
    {
        //格式化部分字段
        $data=$this->common_data_save($data);
        //录入数据
		
        $aid=$this->model->table('content')->data($data)->where('aid='.$data['aid'])->update(); //录入基本信息	
			if($data['functions']){
			foreach($data['functions'] as $key=>$val){
				  model('content')->functions_save(array('id'=>$val,'aid'=>$data['aid']));
				}
			}
        model('tags')->content_save($data['keywords'],$data['aid']); //处理TAG
        model('position')->edit_content_save($data['position'],$data['aid']); //保存推荐位
        if($ext){
        model('expand_model')->edit_content_save($data); //录入扩展模型数据
        }
        model('upload')->relation('content',$data['file_id'],$data['aid']); //录入附件表
        /*hook*/
        $this->plus_hook('content','edit_data',$data);
        /*hook end*/
        return $aid;
    }

    //内容表信息
    public function edit_content_save($data)
    {
        //格式化部分字段
        $data=$this->common_content_save($data);
		
        return $this->model->table('content_data')->data($data)->where('aid='.$data['aid'])->update();
    }
	
	//内容表信息
    public function edit_starttime($t, $time, $aid)
    {
		$data['starttime'] = $time; 
        $data['time'] = $t;
        return $this->model->table('expand_content_livestream')->data($data)->where('aid='.$aid)->update();
    }
	
		//获取直播信息
    public function get_live($aid)
    {
		return $this->model->table('expand_content_livestream')->where('aid='.$aid)->find();
    }
	
	public function streamtime($where,$data){
		
        return $this->model->table('expand_content_livestream')->data($data)->where($where)->update();
		
    }
    //获取内容ID列表
    public function get_list_id($cid)
    {
        return $this->model->table('content')->field('aid')->where('cid='.$cid)->select();
    }

    //内容删除
    public function del($aid)
    {
        /*hook*/
        $this->plus_hook('content','del_data',$aid);
        /*hook end*/
        //删除内容基本信息
        model('expand_model')->del_content($aid);
        $status=$this->model->table('content')->where('aid='.$aid)->delete(); 
        model('position')->del_content($aid);
        model('tags')->del_content($aid);
        model('upload')->del_file('content',$aid);
        return $status;
    }

    public function del_content($aid)
    {
        return $this->model->table('content_data')->where('aid='.$aid)->delete(); 
    }

    //审核草稿
    public function status($aid,$status){
        $where['status']=intval($status);
        return $this->model->table('content')->data($where)->where('aid='.$aid)->update();
    }

    //修改栏目ID
    public function edit_cid($aid,$cid){
        $where['cid']=intval($cid);
        return $this->model->table('content')->data($where)->where('aid='.$aid)->update();
    }

    //获取标题拼音
    public function get_urltitle($name='', $urlname = null, $aid = null)
    {
        if(empty($name)){
            return false;
            exit;
        }
        if (empty($urlname))
        {
            $pinyin = new Pinyin();
            $name = preg_replace('/\s+/', '-', $name);
            $pattern = '/[^\x{4e00}-\x{9fa5}\d\w\-]+/u';
            $name = preg_replace($pattern, '', $name);
            $urlname = substr($pinyin->output($name, true),0,30);
            if(substr($urlname,0,1)=='-'){
                $urlname=substr($urlname,1);
            }
            if(substr($urlname,-1)=='-'){
                $urlname=substr($urlname,0,-1);
            }
        }

        $where='';
        if (!empty($aid))
        {
            $where = 'AND aid<>' . $aid;
        }
        
        $info = $this->model->table('content')->where("urltitle='".$urlname."'" .$where)->count(); 

        if (empty($info))
        {
            return $urlname;
        }
        else
        {
            return $urlname.substr(cp_uniqid(),8);
        }
    }

    //获取关键词
    public function get_keyword($title,$content){
        $data=Http::doGet('http://keyword.discuz.com/related_kw.html?ics=utf-8&ocs=utf-8&title='.urlencode($title).'&content='.urlencode($content),10);
        if(empty($data)){
            return;
        }
        preg_match_all("/<kw>(.*)A\[(.*)\]\](.*)><\/kw>/",$data, $list, PREG_SET_ORDER);

        if(empty($list)){
            return;
        }

        $keywords='';
        foreach ($list as $value) {
            $keywords.=$value[2].',';
        }
        return substr($keywords,0,-1);
    }

    public function gsetQrcode($url, $id){
        $filename = __ROOTDIR__.'/upload/aidimage/'.$id.'.png';  
        if(!file_exists($filename)){
            $qercode = new Qrcodes();
            $qr = $qercode->_Qrcode($url,$filename);
		require(CP_CORE_PATH . '/../ext/aliyun-oss-php-sdk-master/samples/Common.php');
	$ossClient = Common::getOssClient();
		if (is_null($ossClient)) exit(1);
	$bucket = Common::getBucketName();
	$temp=
	$object='upload/aidimage/'.$id.'.png';
	
	 $ossClient->uploadFile($bucket, $object, $filename);
   
			
        }
    }
	
	 public function getQrcode($url, $name){
        $filename = __ROOTDIR__.'/upload/aidimage/'.$name.'.png';  
        if(!file_exists($filename)){
            $qercode = new Qrcodes();
            $qr = $qercode->_Qrcode($url,$filename);
		require(CP_CORE_PATH . '/../ext/aliyun-oss-php-sdk-master/samples/Common.php');
	$ossClient = Common::getOssClient();
		if (is_null($ossClient)) exit(1);
	$bucket = Common::getBucketName();
	$temp=
	$object='upload/aidimage/'.$name.'.png';
	
	 $ossClient->uploadFile($bucket, $object, $filename);
   
			
        }
    }
	 public function gsetQrcodecomment($url, $id){
        $filename = __ROOTDIR__.'/upload/aidimage/comment_'.$id.'.png';  
        if(!file_exists($filename)){
            $qercode = new Qrcodes();
            $qr = $qercode->_Qrcode($url,$filename);
        }
    }

}
?>