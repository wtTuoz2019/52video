<?php
class changeMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {   
      
      session_start();
     
      $uid =$_SESSION['uid']; 
     
      $fid = $_POST['fid'] + 0;
      if($_SESSION['status'] == 1 && $_SESSION['uid'] == $uid && $_SESSION['fid'] == $fid){
          $arr = array(
              'status' => 0,
              'msg' => "已经点过赞"
          );
      }else{
          $result = model('change')->praise($fid);
          if($result){
              $_SESSION['status'] = 1;
              $_SESSION['uid'] = $uid;
              $_SESSION['fid'] = $fid;
              $arr = array(
                  'status' => 1,
                  'msg' => "点赞成功"
              );
          }else{
              $arr = array(
                  'status' => 0,
                  'msg' => "点赞失败"
              );
          }

      }
      echo json_encode($arr);
    }
	
	public function laud()
    {   
	  $data['type'] = $_POST['type'];
      $data['uid'] = $_SESSION['uid']; 
	  $data['cid'] = $_POST['fid'];
      if($data['uid'] <= 0){
      	$this->msg('请先登录',0);
      }
	  $state = model('comment')->laud_get($data);
	  if(!$state){
		  model('comment')->laud_add($data);
		  $this->msg('点赞成功',1);	
	  }else{
			$this->msg('已点过赞',0);	
	  }
	}
	
		public function canselaud()
    {   
	  $data['type'] = $_POST['type'];
      $data['uid'] = $_SESSION['uid']; 
	  $data['cid'] = $_POST['fid'];
      if($data['uid'] <= 0){
      	$this->msg('请先登录',0);
      }
	  $state = model('comment')->laud_canse($data);
	  if($state){
		
		  $this->msg('取消成功',1);	
	  }else{
			$this->msg('取消失败',0);	
	  }
	}

    public function collection(){
      session_start();
    
      $uid = $_SESSION['uid'];
      if($uid <= 0){
      	$this->msg('请先登录',0);
      };
      $fid = $_POST['fid'] + 0;
      $status = model('change')->status_c($fid, $uid);
      if(!$status){
      	$result = model('change')->collection($fid, $uid);
      	if($result){
      		$this->msg('收藏成功',1);
      	}else{
      		$this->msg('收藏失败',0);
      	}
      }else{
      	$this->msg('重复收藏',0);
      }
    }
}

?>