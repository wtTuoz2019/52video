<?php
class ajaxModel extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    public function info_search($i, $n, $keyword, $cid)
    {   
        $num = $i*$n;
        $sql="
        SELECT *
        FROM {$this->model->pre}content where cid=$cid and title like '%$keyword%' limit $num, $n";
        $data=$this->model->query($sql);
        return $data;
    }

    //获取列表总数
    public function count_search($keyword, $cid)
    {
        $sql="
        SELECT count(*) as num
        FROM {$this->model->pre}content where cid=$cid and title like '%$keyword%'";
        $data=$this->model->query($sql);
        return $data[0]['num'];
    }

     public function info($i, $n, $cid)     
    {
        $num = $i*$n;
        $sql="SELECT * FROM {$this->model->pre}content where cid IN ($cid) limit $num, $n";
        $data=$this->model->query($sql);
        return $data;
    }

    //获取列表总数
    public function count($cid)
    {
        $sql="
        SELECT count(*) as num
        FROM {$this->model->pre}content where cid IN ($cid);";
        $data=$this->model->query($sql);
        return $data[0]['num'];
    }

    public function subgary($pid)

    {
        $sql="
        SELECT cid
        FROM {$this->model->pre}category where pid=$pid";
        $data=$this->model->query($sql);
        $arr = array();
        $array = '';
        foreach ($data as $k => $v) {
            $arr[$k] = $v['cid'];
            $array .= $arr[$k].",";
        }
        $array = substr($array,0,-1);
        return $array;
    }

    public function user($aid)
    {
        $sql="SELECT uid FROM {$this->model->pre}content where aid=$aid";
        $data=$this->model->query($sql);
        $uid = $data[0]['uid'];
        if(!$uid){
            return false;
        }
        $sql="SELECT count(*) as num FROM {$this->model->pre}content where uid=$uid;";
        $data1=$this->model->query($sql);
        $num = $data1[0]['num'];
        $sql="SELECT username, uid FROM {$this->model->pre}user where uid=$uid";
        $result=$this->model->query($sql);
        $result['num'] = $num;
        return $result;
    }

    public function nicename($uid){
        $sql="
        SELECT nicename
        FROM {$this->model->pre}user where uid=$uid";
        $data=$this->model->query($sql);
        return $data;
    }

    public function comment($aid)
    {
        $sql="SELECT count(*) as num FROM {$this->model->pre}comment where fid=$aid and type='wechat'";
        $data1=$this->model->query($sql);
        return $data1[0]['num'];
    }

    public function commentlist($fid, $index, $Size)
    {
        $num = $index*$Size;
        $sql="SELECT a.id, a.nickname, a.uid, a.fid, a.message, a.time, b.headimgurl FROM {$this->model->pre}comment as a, tp_wechat_group_list as b where a.uid=b.id and fid=$fid and type='wechat' order by time desc limit $num, $Size;";  
        $data1=$this->model->query($sql);
        return $data1;
    }


    public function commentdel($id)
    {
        $status=$this->model->table('comment')->where('id='.$id)->delete(); 
        return $status;
    }

    public function praise($aid)
    {
        $result = $this->model->table('content')->where('aid=' . $aid)->find(); 
        $time=time();
        $laud = $result['laud']+1;
        $sql="UPDATE {$this->model->pre}content SET laud = $laud, updatetime =  $time WHERE aid = $aid ";
        $data=$this->model->query($sql);
        return $data;
    }

    public function wechat($openid)
    {
        $sql="SELECT id, nickname, sex, headimgurl, openid FROM tp_wechat_group_list where openid='$openid'";
        $data=$this->model->query($sql);
        return $data;
    }

    public function getres($t)
    {
        $sql="SELECT  a.*, b.content FROM {$this->model->pre}content as a, {$this->model->pre}content_data as b where a.aid=b.aid and updatetime>$t";
        $data=$this->model->query($sql);
        return $data;
    }

    public function getnum($t)
    {
        $sql="SELECT count(*) as num FROM {$this->model->pre}content as a, {$this->model->pre}content_data as b where a.aid=b.aid and updatetime>$t";
        $data=$this->model->query($sql);
        return $data[0]['num'];
    }

        public function get_live_res($t)
    {
         return $this->model->field('B.*')->table('content','A')
                ->add_table('expand_content_livestream','B','B.aid=A.aid')
                ->where('updatetime>'.$t)->order('updatetime desc')->select();
    }

    public function get_live_num($t)
    {
        return $this->model->field('B.*')->table('content','A')
                ->add_table('expand_content_livestream','B','B.aid=A.aid')
                ->where('updatetime>'.$t)->order('updatetime desc')->count();
    }
    //通道
    public function get_channel_res($t)
    {
        return $this->model->table('channel')->where('updatetime>'.$t)->select();
    }

    public function get_channel_num($t)
    {
        return $this->model->table('channel')->where('updatetime>'.$t)->count();
    }
	
	public function push_add_save($data) {
		        //格式化部分字段
        $data=$this->common_data_save($data);
        $data['inputtime']=time();
        //录入数据
        $aid=$this->model->table('content')->data($data)->insert(); //录入基本信息
/*        model('tags')->content_save($data['keywords'],$aid); //处理TAG
        model('position')->add_content_save($data['position'],$aid); //保存推荐位
        model('expand_model')->add_content_save($data,$aid);  //录入扩展模型数据
        model('upload')->relation('content',$data['file_id'],$aid); //录入附件表*/
        /*hook*/
        $this->plus_hook('content','add_data',$data);
        /*hook end*/
        return $aid;
	}
	
	public function push_edit_save($data, $aid){
        $aid=$this->model->table('content')->data($data)->where('aid='.$aid)->update();
        return $aid;
	}
	
	public function push_edit_content_save($data, $aid){
		$arr['aid'] = $aid;
		$arr['starttime'] = strtotime($data['starttime']);
        $this->model->table('expand_content_livestream')->data($arr)->where('aid='.$aid)->update();
	}
	
	
	public function getaid($aid){
		$result = $this->model->table('content')->where('oldaid='.$aid)->find(); 
		return $result['aid'];
	}
	
	public function push_add_content_save($data, $aid){
		$arr['aid'] = $aid;
		$arr['starttime'] = strtotime($data['starttime']);
        return $this->model->table('expand_content_livestream')->data($arr)->insert();
	}
	
	//内容字段处理
    public function common_content_save($data)
    {
        $data['content']=html_in($data['content']);
        return $data;
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
	
	public function addchannel($data){
		//$arr['oldid'] = $data['oldid'];
		$arr['vMp4url'] = $data['vMp4url'];
		
		$result = $this->model->table('channel')->where($arr)->find();
		
			if($result){
				$r = $this->model->table('channel')->data($data)->where($arr)->update();
				if($r){
					return $result['id'];
				}
			}else{
				$aid=$this->model->table('channel')->data($data)->insert();
				return $aid;
			}
		
	}
	
	public function imgApp($xmlstr){
		//生成图片 
		$imgDir = 'upload/'; 
        //$filedata = $imgDir.date("Y-m")；
		$filename="school/".md5(time().mt_rand(10, 99)).".jpg";///要生成的图片名字		  
		$jpg = $xmlstr;//得到post过来的二进制原始数据 
		if(empty($jpg)) 
		{ 
		  echo 'nostream'; 
		  exit(); 
		} 

    //    if(!file_exists($filedata)){
//            mkdir($filedata);
//        }
//        if(!file_exists("./".$imgDir.$filename)){
//            mkdir("./".$imgDir.$filename); 
//        }  
//		  
		$file = fopen("./".$imgDir.$filename,"w");//打开文件准备写入 
		fwrite($file,$jpg);//写入 
		fclose($file);//关闭 
		  
		$filePath = './'.$imgDir.$filename; 
		$newfilepath = '/'.$imgDir.$filename; 
		  
		//图片是否存在 
		if(!file_exists($filePath)) 
		{ 
		  echo 'createFail'; 
		  exit(); 
		}
		return $newfilepath;
	}




}

?>