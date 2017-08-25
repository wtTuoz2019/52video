<?php
class  commentModel extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

  
		//获取树形列表
    public function comment_list($fid,$type,$limit) {
       
       $list=$this->model->table('comment')->where(array('fid'=>$fid,'type'=>$type,'pay'=>1))->limit($limit)->order('id desc')->select();
	   if(is_array($list)){
	   foreach($list as $k=>$v){
		  $list[$k]['width']=(100/5)*$v['score']; 
		   }
	   }
		return $list;
    }
	
    //获取树形列表
    public function comment_id_count($uid) {
       
        return $this->model->table('comment')->where('fid='.$uid)->count();
    }
	  //获取用户头像
    public function picture($uid,$size=1) {
        $info=$this->info($uid);

        switch ($size) {
            case 1:
                $name='large';
                break;
            case 2:
                $name='moderate';
                break;
            case 3:
                $name='small';
                break;
            case 4:
                $name='original';
            break;
        }

       	$file='user/avatar/'.$uid.'/'.$name.'.jpg';
        $url='/user/avatar/'.$uid.'/'.$name.'.jpg';
		
        if(!file_exists($file)){
            $sex='male_';
            switch ($info['sex']) {
                case 1:
                    $sex='male_';
                    break;
                case 2:
                    $sex='female_';
                    break;
                case 3:
                    $sex='male_';
                    break;
            }
            $url='/user/public/images/avatar/'.$sex.$name.'.png';
        }
        return $url;


    }
	
	 //获取用户信息
    public function info($uid=0	) {
		
        return $this->model
                    ->field('C.*,A.*,B.name as gname')
                    ->table('user','A')
                    ->add_table('user_group','B','A.gid=B.gid')
                    ->add_table('user_append','C','A.uid=C.uid')
                    ->where('A.uid='.$uid)
                    ->find();
    }
		 //获取用户信息
    public function wetchheadpic($uid=0) {
		
       $info= $this->model
                    ->table('user')
                    ->where('uid='.$uid)
                    ->find();
		return $info['headimgurl'];
    }
	
	 public function comment_count($fid,$type) {
       
       return $this->model->table('comment')->where(array('fid'=>$fid,'type'=>$type))->count();
	  
		
    }
	
	
    public function edit_save($data) {
        
        return $this->model->table('comment')->data($data)->where('id='.$data['id'])->update();
    }
 //删除
    public function del($id)
    {
        $status=$this->model->table('comment')->where('id='.$id)->delete(); 
       
        return $status;
       
    }
	
	//评论
	public function comment($data) {
		return $this->model->table('comment')->data($data)->insert();
	}
	
	//评论
	public function save($data) {
		return $this->model->table('comment')->data($data)->insert();
	}
	
	//根据用户id获取用户nicename
	public function getname($id=0) {
		$data = $this->model->table('user')->where('uid='.$id)->find();
		if(!$data['nicename']){
			return "匿名网友";
		}else{
			return $data['nicename'];
		}
	}
	
	//根据用户id获取评论
	public function get_comment($id) {
		return $this->model->table('comment')->where('id='.$id)->select();
	}
	
	//根据用户id获取用title
	public function get_t($id) {
		$data = $this->model->table('content')->where('aid='.$id)->find();
		return $data['title'];
	}
	
	public function comment_($fid)
    {
		return $this->model->table('comment')->where(array('fid'=>$fid,'type'=>'content'))->count();
    }
	
	
	public function priceall($aid)
    {
		return $this->model->table('redlog')->where(array('aid'=>$aid))->count('price');
    }
	
	//获取内容
    public function info_($fid, $index,$type='')
    {  
	
		if($this->contentinfo($fid)){
		$where=' AND flag=1';
		}
		if($type=='fid'){
			$where=" AND (type='wechat' or (type='wechatpay' and pay=1) )";
			}
		
		return $this->model->table('comment')->where('fid='.$fid.' AND id>'.$index.$where)->limit(1)->order('id asc')->select();
    }
	
	//获取内容
    public function contentinfo($fid)
    {   
		
		$data=$this->model->table('content')->where('aid='.$fid)->find();
		return $data['comment'];
    }
	
//删除内容
    public function comment_del($where)
    {   
		$status=$this->model->table('comment')->where($where)->delete();
		return $status;
    }

    public function wechat_add($data){
		//if($data['unionid']){
//			if($this->model->table('user')->where("unionid='".$data['unionid']."'")->find()){
//				return $this->model->table('user')->where("unionid='".$data['unionid']."'")->data($data)->update();	
//				}
//			}
		if($this->model->table('user')->where("openid='".$data['openid']."'")->find()){
			
			return $this->model->table('user')->where("openid='".$data['openid']."'")->data($data)->update();	
			}else{
			return $this->model->table('user')->data($data)->insert();	
				}
        
    }

    public function wechat_sel($openid) {
        $openid = "'".$openid."'";
        $res=$this->model->table('user')->where('openid='.$openid)->find();
        return $res['uid'];
    }

    public function wechat_up($data, $uid){
        return $this->model->table('user')->data($data)->where('uid='.$uid)->update();
    }
	
	public function stream($where,$data){
		$info=$this->model->table('streamtime')->where($where)->find();
		if($info){
        return $this->model->table('streamtime')->data($data)->where($where)->update();
		
		}else{
			$data = array_merge($data, $where);  
		 return $this->model->table('streamtime')->data($data)->insert();	
			}
    }
		public function streamtime($where,$data){
		
        return $this->model->table('expand_content_livestream')->data($data)->where($where)->update();
		
    }
	public function addstreamlog($data){
	
	 return $this->model->table('streamtimelog')->data($data)->insert();	
			
    }
	public function livestream($where){
		
        return $this->model->table('expand_content_livestream')->where($where)->find();
		
    }
	
	public function streaminfo($where){
		return $this->model->table('streamtime')->where($where)->find();
		}
    public function get_pic( $uid=0){
		
			$res = $this->model->table('user')->field('headimgurl')->where('uid='.$uid)->find();
			
        	return $res['headimgurl'];
		
    }
	
//获取评论内容
    public function pc_info($fid, $limit, $id=0,$type=null)
    {   
		$where = "A.fid=".$fid." and A.pid=0 ";
		if($id){
			$where.="and A.id<".$id;
			}
		if($this->contentinfo($fid)){
		$where.=' AND A.flag=1';
		}
		if($type)$where.=' AND progress>0';else $where.=' AND progress=0';
		$list=$this->model->field('A.*,B.nicename as name,headimgurl as pic')->table('comment','A')
            ->add_table('user','B','B.uid=A.uid')->where($where)->limit(10)->order('A.time desc')->select();
			return $list;
    }

//获取评论内容
    public function mycomment($uid=0,$id=0)
    {   
		$where = " A.pid=0 and A.uid=".$uid;
		if($id){
			$where.=" and A.id<".$id;
			}
		
		
		$list=$this->model->field('A.*,B.nicename as name,headimgurl as pic')->table('comment','A')
            ->add_table('user','B','B.uid=A.uid')->where($where)->limit(10)->order('A.time desc')->select();
			return $list;
    }
	
	    public function mycommentarticle($uid=0,$id=0)
    {   
		$where = " A.pid=0 and A.uid=".$uid;
		if($id){
			$where.=" and B.aid<".$id;
			}
		
		
		$list=$this->model->field('distinct B.aid,B.image,B.title,B.updatetime,B.cid')->table('comment','A')
            ->add_table('content','B','B.aid=A.fid')->where($where)->limit(10)->order('B.aid desc')->select();
			return $list;
    }


	    public function mycommentid($uid=0)
    {   
		$where = " pid=0 and uid=".$uid;
		
		
			$list=$this->model->field('id')->table('comment')->where($where)->select();
			$data=array(1);
			if(is_array($list)){
				foreach($list as $key=>$value){
					$data[]=$value['id'];
					}
				}
			
			return $data;
    }
	 public function getbecommentcount($where)
    {   
		
		
		
			return $this->model->field('distinct pid')->table('comment')->where($where)->count();
			
			
			
    }
	 public function getcommentcount($where)
    {   
		
		
		
			return $this->model->table('comment')->where($where)->count();
			
			
			
    }
public function getlaudnum($where){
		return $this->model->table('laud_')->where($where)->count();		
	}
	
	//获取评论内容
    public function pc_auto_info($fid,$id)
    {   
		$where = "A.fid=".$fid." and A.id>".$id;
		if($this->contentinfo($fid)){
		$where.=' AND A.flag=1';
		}
		$list=$this->model->field('A.*,B.nicename as name,headimgurl as pic')->table('comment','A')
            ->add_table('user','B','B.uid=A.uid')->where($where)->limit(10)->order('A.time desc')->select();
			return $list;
    }
	
	

	
	
	// //获取评论数量
    public function pc_auto_count($fid, $id=0)
    {   
		$where = "fid=".$fid." and pid=0 and pay=1 and id>".$id;
		if($this->contentinfo($fid)){
		$where.=' AND flag=1';
		}
		
		return $this->model->table('comment')->where($where)->count();  
    }
	
	//获取评论数量
    public function pc_count($fid, $id=0)
    {   
		$where = "fid=".$fid." and id>".$id;
		if($this->contentinfo($fid)){
		$where.=' AND flag=1';
		}
		return $this->model->table('comment')->where($where)->count();  
    }
	
	//获取显示时间
	public function get_time($time)
	{
	   //获取今天凌晨的时间戳
	   $day = strtotime(date('Y-m-d',time()));
	   //获取昨天凌晨的时间戳
	   $pday = strtotime(date('Y-m-d',strtotime('-1 day')));
	   //获取现在的时间戳
	   $nowtime = time();
	   $tc = $nowtime-$time;
	   if($time<$pday){
		  $str = date('Y-m-d H:i',$time);
	   }elseif($time<$day && $time>$pday){
		  $str = "昨天";
	   }elseif($tc>60*60){
		  $str = floor($tc/(60*60))."小时前";
	   }elseif($tc>60){
		  $str = floor($tc/60)."分钟前";
	   }else{
		  $str = "刚刚";
	   }
	   return $str;
	}
	
	//获取子评论条数
	public function comment_n($pid){
		$num = $this->model->table('comment')->where('pid='.$pid)->count();	
		if(!$num){
			return 0;
		}
		return $num;
	}
		
	public function comment_reply($pid,$signup=false,$aid=0){
			
	$where = " A.pid=".$pid;
		
	
	$where.=' AND A.flag=1';
		
		$list=$this->model->field('A.*,B.nicename as name,headimgurl as pic')->table('comment','A')
            ->add_table('user','B','B.uid=A.uid')->where($where)->limit(10)->order('A.time ASC')->select();
		if(is_array($list)){
			foreach($list as $k=>$v){
				if($signup){
					$userinfo=model('form_list')->infobyuser($v['uid'],'signup');
				$list[$k]['name']=$userinfo['name']?$userinfo['name']:$list[$k]['name'];
				$list[$k]['nickname']=$userinfo['name']?$userinfo['name']:$list[$k]['nickname'];
			
					
					}
				
				}
			}	
			
			
		return $list;


	 }
	
	public function getz($id,$fid){
			$where = 'pid='.$id." and pay=1";
		
		if($this->contentinfo($fid)){
		$where.=' AND flag=1';
		}
		
		$res = $this->model->table('comment')->where($where)->order('time asc')->select();
		return $res;
	}

	public function getc($id){
		$res = $this->model->table('comment')->where('pid='.$id." and pay=1")->order('time desc')->select();
		return $res;
	}

	public function getf($id){
		$res = $this->model->table('comment')->where('pid='.$id." and pay=1")->order('time desc')->find();
		return $res;
	}

	public function geta($id){
		$res = $this->model->table('comment')->where('id='.$id." and pay=1")->find();
		return $res;
	}
	
	public function getList($pid=0,$fid){
		$result = array();
		$res = $this->getz($pid,$fid);
		
		return $res;
	}
		
	public function get_limit($page, $list){
		return $page*$list.','.$list;
	}
	
	public function get_uid(){
		$str = isset($_COOKIE['93GDN__duxuser']) ? $_COOKIE['93GDN__duxuser'] : 0;
		if(!$str){
			return false;
		}else{
			$uid=explode("|",$str);
			return $uid[0];
		}
	}
	
	public function laud_num($id){
		return $this->model->table('laud_')->where("type='comment' and cid=".$id)->count();		
	}
	public function laud_list($id){
		$list=$this->model->table('laud_')->where("type='comment' and cid=".$id)->select();		if(is_array($list)){
			foreach($list as $k=>$v){
				
				$list[$k]['picture']=$this->wetchheadpic($v['uid']);
				
				}
			
			
			}
			
			return $list;
	}
	
	public function laud_get($data){
		return $this->model->table('laud_')->where($data)->find();
	}
	public function laud_canse($data){
		
		return $this->model->table('laud_')->where($data)->delete();
	}
	public function laud_add($data){
		$data['laud'] = 1;
		return $this->model->table('laud_')->data($data)->insert();
	}
	
	public function addredlog($data){
		
		return $this->model->table('redlog')->data($data)->insert();
	}
	public function getuid($openid){
		
		$data = $this->model->table('user')->where("openid='".$openid."'")->find();
		if(!$data){
			return FALSE;
		}else{
			return $data['uid'];	
		}
	}
}

?>