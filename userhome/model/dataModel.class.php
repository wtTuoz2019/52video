<?php
class  dataModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取数量
    public function getcount($where) {
        return $this->model->table('visit')->field('DISTINCT  uid')->where($where)->count();
    }
	
	 //获取数量
    public function getavg($where) {
        return $this->model->table('visit')->where($where)->find();
    }
	
	 public function staytimeavg($where=array()){
        $data=$this->model->table('visit')->where($where)->field('avg((endtime-starttime)/60) as staytime')->find();
        return $data['staytime'];
    }
	
	 public function looktimesum($where=array()){
        $data=$this->model->table('visit')->where($where)->field('sum(endtime-starttime) as staytime')->find();
        return $data['staytime'];
    }
	public function getstarttime($where=array()){
        $data=$this->model->table('visit')->where($where)->field('starttime')->order('starttime asc')->find();
        return $data['starttime'];
    }
	 public function getendtime($where=array()){
        $data=$this->model->table('visit')->where($where)->field('endtime')->order('endtime desc')->find();
        return $data['endtime'];
    }
	
	//城市统计	 
	public function city($aid){
		
		$aid=intval($aid);
		$sql="SELECT city ,count(distinct uid) as count FROM `dc_visit` where `aid`=".$aid." group by city";
	 	//$sql="SELECT dc_user.city , count(distinct dc_user.uid) as count FROM `dc_visit` LEFT join dc_user on dc_visit.uid=dc_user.uid  where `aid`=".$aid." and dc_user.uid is not null group by dc_user.city";
		
		$data=$this->model->query($sql);
		
		return $data;
		
		}
	public function sex($aid){
		$aid=intval($aid);
		
	 	$sql="SELECT dc_user.sex, count(distinct dc_user.uid) as count FROM `dc_visit` LEFT join dc_user on dc_visit.uid=dc_user.uid  where `aid`=".$aid." and dc_user.uid is not null group by dc_user.sex";
		$data=$this->model->query($sql);
		return $data;
		
		
		
		}

  public function getdatatime($where=array()){
        $data=$this->model->table('visit')->where($where)->field('sum((endtime-starttime)/60) as staytime')->find();
        return $data['staytime'];
    }
	  //获取数量
    public function getdatalist($where) {
        return $this->model->table('visit')->where($where)->select();
    }
    
   public function get_livestream($aid){
         return $this->model->table('expand_content_livestream')->where(array('aid'=>$aid))->find();
       
    }
}

?>