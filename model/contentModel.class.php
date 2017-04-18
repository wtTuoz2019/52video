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
        return $this->model->table('content')->where("aid in (".$aids.")")->order('updatetime desc')->select(); 
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

}