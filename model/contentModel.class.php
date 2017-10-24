<?php
class contentModel extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    //模块修正
    public function model_jump($mid,$module){
        $model_info = model('category')->model_info($mid);
        if (!empty($model_info['module_content'])&&$model_info['module_content']<>$module) {
            module($model_info['module_content'])->index();
            if ( $this->config['HTML_CACHE_ON'] ) {
                cpHtmlCache::write();
            }
            exit;
        }
    }
		public function functions_list($where)
    {	
	
		
       return $this->model->table('functions')->where($where)->order('sequence asc,id asc')->select(); 
      
      
    }
	
		public function functions_info($where)
    {	
	
		
       return $this->model->table('functions')->where($where)->find(); 
      
      
    }
	
	
	
    //获取内容
    public function info($aid)
    {
        return $this->model->table('content')->where('aid='.$aid)->find();
    }
	
	 //获取内容
    public function getvideourl($aid)
    {
        $data=$this->model->field('videourl')->table('content')->where('aid='.$aid)->find();
		return $data['videourl'];
    }
	
	 //获取内容 
    public function getlist($aids) 
    {
        return $this->model->table('content')->where("aid in (".$aids.")")->order('sequence desc,updatetime desc')->select(); 
    }
 
    public function info_content($aid)
    {
        return $this->model->table('content_data')->where('aid='.$aid)->find();
    }

    public function info_source($aid)
    {
        $data = $this->model->table('content')->where('aid='.$aid)->find();
        return $data;
    }

    //完整内容
    public function model_content($aid,$ext_id=0)
    {
        $data = model('content')->info_source($aid);
        if(!empty($ext_id)){
            $model_info=model('category')->expand_model_info($ext_id);
            $expand="LEFT JOIN {$this->model->pre}expand_content_{$model_info['table']} C ON C.aid = A.aid";
            $expand_field="C.*,";
        }
        $source = $data['source'];
        $info="
            SELECT {$expand_field}A.*
             FROM {$this->model->pre}content A 
             {$expand}
             WHERE A.aid={$aid} LIMIT 1
            ";
            $info=$this->model->query($info);
			
			
            return $info[0]; 

    }

    //上一篇
    public function prev_content($aid,$cid,$ext_id){
        if(!empty($ext_id)){
            $model_info=model('category')->expand_model_info($ext_id);
            $expand="LEFT JOIN {$this->model->pre}expand_content_{$model_info['table']} C ON C.aid = A.aid";
            $expand_field="C.*,";
        }
        $info="
            SELECT {$expand_field}A.*
             FROM {$this->model->pre}content A 
             LEFT JOIN {$this->model->pre}category B ON B.cid = A.cid
             {$expand}
             WHERE A.aid<{$aid} AND A.status=1 AND B.cid={$cid} ORDER BY A.aid desc LIMIT 1
            ";
            $info=$this->model->query($info);
        return $info[0]; 
    }

    //下一篇
    public function next_content($aid,$cid,$ext_id){
        if(!empty($ext_id)){
            $model_info=model('category')->expand_model_info($ext_id);
            $expand="LEFT JOIN {$this->model->pre}expand_content_{$model_info['table']} C ON C.aid = A.aid";
            $expand_field="C.*,";
        }
        $info="
            SELECT {$expand_field}A.*
             FROM {$this->model->pre}content A 
             LEFT JOIN {$this->model->pre}category B ON B.cid = A.cid
             {$expand}
             WHERE A.aid>{$aid} AND A.status=1 AND B.cid={$cid} ORDER BY A.aid asc LIMIT 1
            ";
            $info=$this->model->query($info);
        return $info[0];
    }

    //替换后内容
    public function format_content($content){

        $replace = $this->model->table('replace')->select();
        $content=html_out($content);
        if (!empty($replace)) {
            foreach ($replace as $export) {
                if(empty($export['key'])){
                    if(empty($export['num'])){
                        $export['num']=1;
                    }
                    $content = preg_replace("/(?!<[^>]+)".preg_quote($export['key'],'/')."(?![^<]*>)/",$export['content'], $content,$export['num']);
                }
            }
        }
        return $content;
    }

   //增加TAG链接
    public function tag_link($content,$aid){
        $taglist = $this->model
            ->field('A.*')
            ->table('tags','A')
            ->add_table('tags_relation','B','B.tid=A.id')
            ->where('B.aid='.$aid)
            ->select();
        $content=html_out($content);
        if (!empty($taglist)) {
            foreach ($taglist as $export) {
                if(!empty($export['name'])){
                    $content = preg_replace("/(?!<[^>]+)".preg_quote($export['name'],'/')."(?![^<]*>)/", '<a href="'.__INDEX__.'/tags-'.$export['name'].'/"  target="_blank">'.$export['name'].'</a>',$content,1);
                }
            }
        }
        return $content;
    }

    //访问计数
    public function views_content($aid,$views){
        $data['views'] = $views + 1;
        $condition['aid'] = $aid;
        $this->model->table('content')->data($data)->where($condition)->update();
    }
	
	  public function views_teacher($id=0){
       $info="
            update {$this->model->pre}teacher  set views=views+1
             WHERE id=".$id;
            $info=$this->model->query($info);
    }
	
	 public function views_school($id=0){
        $info="
            update {$this->model->pre}school  set views=views+1
             WHERE id=".$id;
            $info=$this->model->query($info);
    }

    //URL路径
    public function url_format($dir,$cid,$cname,$info){
            $patterns =array(  
            "{EXT}",
            "{CDIR}",
            "{YY}",
            "{YYYY}",
            "{M}",
            "{D}",
            "{AID}",
            "{URLTITLE}",
            "{P}", 
            );
            $replacements=array(  
            '.html',
            $cname,
            date('y',$info['inputtime']),
            date('Y',$info['inputtime']),
            date('m',$info['inputtime']),
            date('d',$info['inputtime']),
            $info['aid'],
            $info['urltitle'],
            '{page}',
            );
            $url_content=str_replace($patterns,$replacements,$dir);
            return  __INDEX__ .$lang.'/'. $url_content;

    }

    //视频通道
    public function channel($id){
		$id=intval($id);
        return $this->model->field('sn as vMp4url')->table('device')->where('id='.$id)->find();
    }

    public function get_channel_id($vMp4url){
        $data = $this->model->table('channel')->where("vMp4url='".$vMp4url."'")->find();
        return $data['id'];
    }
	
    public function get_content_id($id){
       
        $sql = "SELECT dc_content.aid
            FROM dc_content
            LEFT JOIN dc_expand_content_livestream
            ON dc_expand_content_livestream.aid=dc_content.aid
            WHERE dc_content.channel = $id and dc_content.cid=16 and (dc_expand_content_livestream.starttime+dc_expand_content_livestream.time*60)>".time()." order by dc_expand_content_livestream.starttime asc limit 1";
        $data=$this->model->query($sql);
        return $data[0]['aid'];
    }
	
	    public function get_livecontent($csid){
       
        $sql = "SELECT *
            FROM dc_content
            LEFT JOIN dc_expand_content_livestream
            ON dc_expand_content_livestream.aid=dc_content.aid
            WHERE dc_content.csid = $csid and dc_content.cid=16 and (dc_expand_content_livestream.starttime+dc_expand_content_livestream.time*60)>".time()." order by dc_expand_content_livestream.starttime asc limit 1";
        $data=$this->model->query($sql);
        return $data[0];
    }
	
	public function get_starttime($aid){
	 $data = $this->model->table('expand_content_livestream')->field('starttime')->where(array('aid'=>$aid))->find();
     return $data['starttime'];	
		
	}
	public function get_liveaids($where){
      
     $sql = "SELECT dc_content.aid,dc_content.title,dc_content.tid,dc_user.openid,dc_expand_content_livestream.starttime
            FROM dc_content
            LEFT JOIN dc_expand_content_livestream 
            ON dc_expand_content_livestream.aid=dc_content.aid   LEFT JOIN dc_subscribe
            ON dc_expand_content_livestream.aid=dc_subscribe.aid  LEFT JOIN dc_user
            ON dc_user.uid=dc_subscribe.uid
            WHERE  dc_content.cid=16 $where  order by dc_expand_content_livestream.starttime asc ";
        $data=$this->model->query($sql);
        return $data;
    }
	
	public function getname(){
        return $this->model->field('name')->table('diyfield')->select();
    }
	 //获取数量
    public function getdatacount($where) {
        return $this->model->table('visit')->field('distinct uid')->where($where)->count();
    }
	
	 //获取内容
    public function subscribeinfo($where)
    {
        return $this->model->table('subscribe')->where($where)->find();
    }
	
	//订阅
	public function subscribeadd($data) {
		return $this->model->table('subscribe')->data($data)->insert();
	}

	//取消订阅
	public function subscribedel($where) {
		return $this->model->table('subscribe')->where($where)->delete();
	}
	
	
	public function gettimes($url) {
		$vid=$this->getvideoid($url);
		
		return $this->model->table('videotimes')->where(array('vid'=>$vid))->order('id asc')->select();
	}
		public function getvideoid($url) {
		$data= $this->model->table('videolist')->where(array('vurl'=>$url))->find();
		return $data['id'];
	}
	
	
	public function video_list($where,$limit=null)
    {	
	
		
       return $this->model->table('videolist')->field('id,name,vurl,type,size')->limit($limit)->where($where)->order('id desc')->select(); 
      
      
    }
	
	 //获取模型信息
    public function deviceinfo($id) {
        $where['id']=$id;

        return $this->model->table('device')->where($where)->find();
    }
	
	public function devicepeople($uid,$did){
		$where=array('uid'=>$uid,'did'=>$did);
		return $this->model->table('device_peoples')->where($where)->find();
		
		}
  public function getdesimage($csid){
	 $data= $this->model->field('pid')->table('admin')->where('cid='.$csid)->find();
	
	 if($data['pid']){
		$data=$this->model->field('desimage')->table('admin')->where('id='.$data['pid'])->find();
		
	 }
	 return $data['desimage'];
	  }
	     public function gsetQrcode($url, $id,$image){
			 
		if(!file_exists($image)){
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
       $data['sources']=json_encode($data['sources']);
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


    //基础表信息
    public function edit_save($data,$ext=true)
    {
        //格式化部分字段
        $data=$this->common_data_save($data);
        //录入数据
        $aid=$this->model->table('content')->data($data)->where('aid='.$data['aid'])->update(); //录入基本信息

      
        /*hook end*/
        return $aid;
    }


    //基础表信息
    public function update_save($data)
    {
       
       
        $aid=$this->model->table('content')->data($data)->where('aid='.$data['aid'])->update(); //录入基本信息

      
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

    //获取内容ID列表
    public function get_list_id($cid)
    {
        return $this->model->table('content')->field('aid')->where('cid='.$cid)->select();
    }

    //内容删除
    public function del($aid,$uid=0)
    {
       
      
        $status=$this->model->table('content')->where(array('aid'=>$aid,'uid'=>$uid))->delete(); 
		if($status){
		
        model('position')->del_content($aid);
        model('tags')->del_content($aid);
		}
        return $status;
    }

    public function del_content($aid)
    {
        return $this->model->table('content_data')->where('aid='.$aid)->delete(); 
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
	
		public function video_add($data)
    {
        
        $id=$this->model->table('videolist')->data($data)->insert(); //录入基本信息
      
        return $id;
    }
	public function video_save($data)
    {	
	
		
        $id=$this->model->table('videolist')->data($data)->where('id='.$data['id'])->update(); //录入基本信息
      
        return $id;
    }
	
		public function video_count($where)
    {	
	
		
       return $this->model->table('videolist')->where($where)->count(); 
      
      
    }
	public function video_info($where)
    {	
		 $id=$this->model->field('id,name,vurl,type,size')->table('videolist')->where($where)->find(); 
      
        return $id;
    }
	
	
	public function video_del($id){
		
		  $status=$this->model->table('videolist')->where('id='.$id)->delete(); 
      
        return $status;
		}
	
}