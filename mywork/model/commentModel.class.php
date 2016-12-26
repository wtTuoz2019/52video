<?php
class  commentModel extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

  
		//获取树形列表
    public function comment_list($fid,$type,$limit) {
       
       $list=$this->model->table('comment')->where(array('fid'=>$fid,'type'=>$type))->limit($limit)->order('id desc')->select();
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
         $data['updatetime']=time();
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
	
	//获取内容
    public function info_($fid, $index)
    {   if($this->contentinfo($fid)){
		$where=' AND flag=1';
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
    public function comment_del($id)
    {   
		$status=$this->model->table('comment')->where('id='.$id)->delete();
		return $status;
    }

    public function wechat_add($data){
        return $this->model->table('user')->data($data)->insert();
    }

    public function wechat_sel($openid) {
        $openid = "'".$openid."'";
        $res=$this->model->table('user')->where('openid='.$openid)->find();
        return $res['uid'];
    }

    public function wechat_up($data, $uid){
        return $this->model->table('user')->data($data)->where('uid='.$uid)->update();
    }

    public function get_pic( $uid=0){
		
			$res = $this->model->table('user')->data($data)->where('uid='.$uid)->find();
        	return $res['headimgurl'];
		
    }
	
	//获取评论内容
    public function pc_info($fid, $limit, $id=0)
    {   
		$where = "fid=".$fid." and pid=0 ";
		if($id){
			$where.="and id<".$id;
			}
		if($this->contentinfo($fid)){
		$where.=' AND flag=1';
		}
		return $this->model->table('comment')->where($where)->limit($limit)->order('time desc')->select();
    }


	
	//获取评论内容
    public function pc_auto_info($fid,$id)
    {   
		$where = "fid=".$fid." and id>".$id;
		if($this->contentinfo($fid)){
		$where.=' AND flag=1';
		}
		return $this->model->table('comment')->where($where)->order('time desc')->select();
    }
	
	// //获取评论数量
    public function pc_auto_count($fid, $id=0)
    {   
		$where = "fid=".$fid." and id>".$id;
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
		
	public function comment_reply($pid){
		$result = model('comment')->getList($pid);
	
		if($result){
			foreach ($result as $k => $v) {
				if(empty($v['uid'])) continue;
				if($v['time']){
					$result[$k]['time'] = model('comment')->get_time($v['time']);
				}	
				
				if($v['uid']){
					
					$result[$k]['pic'] = model('comment')->get_pic($v['uid']);	
				}
				
				if($v['rid']){
					$result[$k]['rid'] = model('comment')->getname($v['rid']);
				}
				if($v['com']){
					foreach($v['com'] as $key => $value){	
						if(is_array($value)){
							if($value['time']){
								$result[$k]['com'][$key]['pic'] = model('comment')->get_pic($value['uid']);
								$result[$k]['com'][$key]['time'] =model('comment')->get_time($value['time']);
								$result[$k]['com'][$key]['rid'] =model('comment')->getname($value['rid']);
							}
						}	
					}		
				}
			}		
		}
		return $result;
	 }
	
	public function getz($id){
		$res = $this->model->table('comment')->where('pid='.$id)->order('time asc')->select();
		return $res;
	}

	public function getc($id){
		$res = $this->model->table('comment')->where('pid='.$id)->order('time desc')->select();
		return $res;
	}

	public function getf($id){
		$res = $this->model->table('comment')->where('pid='.$id)->order('time desc')->find();
		return $res;
	}

	public function geta($id){
		$res = $this->model->table('comment')->where('id='.$id)->find();
		return $res;
	}
	
	public function getList($pid=0){
		$result = array();
		$res = $this->getz($pid);
		if ($res) {
			$result = $res;
			foreach ($res as $key => $value) {
				$data = $this->getc($value['id']);
				if ($data) {
					$result[$key]['com'] = $data;
					foreach ($data as $k => $v) {
						if ($data[$k]['id']) {
							$res = $this->getf($data[$k]['id']);
							if ($res) {
								$result[$key]['com'][$k]['com'] = $res;
							}
						}
					}
				}
			}
		}
		return $result;
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
	
	public function laud_add($data){
		$data['laud'] = 1;
		return $this->model->table('laud_')->data($data)->insert();
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