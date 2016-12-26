<?php
//field显示
class fieldModel extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }


    public function field_index_list($where,$limit) {
        session_start();
      
		if($_SESSION['sid']){
			$where.=' AND csid='.$_SESSION['sid'];
			}
			
        return $this->model->table('content')->where($where)->order('inputtime desc')->limit($limit)->select();
    }
 
    public function field_index_count($where) {
        session_start();
      
			if($_SESSION['sid']){
			$where.=' AND csid='.$_SESSION['sid'];
			}
        return $this->model->table('content')->where($where)->count();
    }

    public function field_array($did) {
       
        return $this->model->table('diyfield_value')->where(array('did'=>$did))->select();
    }
	
    public function video($limit,$cid=null) {
        session_start();
			if($_SESSION['sid']){
			$whereadd=' AND A.csid='.$_SESSION['sid'];
			}
     	if($cid){
			$whereadd.=' AND A.cid='.$cid;	
			}
            $loops="
            SELECT A.*,B.starttime,B.time
            FROM {$this->model->pre}content A
            LEFT JOIN {$this->model->pre}expand_content_livestream B
            ON A.aid=B.aid
            WHERE  A.status=1 AND B.starttime<>0 and B.time<>0 {$whereadd}
            ORDER BY A.inputtime desc LIMIT {$limit}
            ";
        
        $loop = $this->model->query($loops);
        if (!$loop) {
            return '';
        }
        foreach ($loop as $key => $value) {
            $time = $value['starttime'] + 60*$value['time'];
            if($value['starttime']<=time() && $time>=time()){
                $loop[$key]['status'] = 0;
            }elseif($value['starttime']>=time()){
                $loop[$key]['status'] = 1;
            }else{
                $loop[$key]['status'] = 2;
            };
        }
        foreach ($loop as $key => $value) {
            $volume[$key] = $value['status'];
            $edition[$key] = $value['starttime'];
        };
        array_multisort($volume, SORT_ASC, $edition, SORT_ASC, $loop);
		
		
        return $loop;
    }

    public function video_list ($where,$limit){
        session_start();
			if($_SESSION['sid']){
			$whereadd=' AND A.csid='.$_SESSION['sid'];
			}
        	
            $loops="
            SELECT A.*,B.starttime,B.time
            FROM {$this->model->pre}content A
            LEFT JOIN {$this->model->pre}expand_content_livestream B
            ON A.aid=B.aid
            WHERE {$where}{$whereadd} ORDER BY A.inputtime desc LIMIT {$limit}
            ";
       
        $loop = $this->model->query($loops);
        if (!$loop) {
            return '';
        }
		 $nowtime=time();
        foreach ($loop as $key => $value) {
           $time = $value['starttime'] + 60*$value['time'];
            if($value['starttime']<=$nowtime && $time>=$nowtime){
                $loop[$key]['status'] = 0;
            }elseif($value['starttime']>=$nowtime){
                $loop[$key]['status'] = 1;
            }else{
                $loop[$key]['status'] = 2;
            };
        }
		 
        foreach ($loop as $key => $value) {
            $volume[$key] = $value['status'];
            $edition[$key] = $value['starttime'];
        };
		
        array_multisort($volume, SORT_ASC, $edition, SORT_ASC, $loop);
		
		
        return $loop;
    }
	
	  public function video_list_count ($where){
        session_start();
			if($_SESSION['sid']){
			$whereadd=' AND A.csid='.$_SESSION['sid'];
		  }
        
          $loops="
            SELECT count(*) as number
            FROM {$this->model->pre}content A
            LEFT JOIN {$this->model->pre}expand_content_livestream B
            ON A.aid=B.aid
            WHERE {$where}{$whereadd} ";
       
        $loop = $this->model->query($loops);
        return $loop[0]['number'];
    }

    public function get_id($token) {
        $res = $this->model->table('collect')->where(array('token'=>$token))->find();
        if(!$res){
            return false;
        }else{
            return $res['id'];
        }
    }

  
	

}