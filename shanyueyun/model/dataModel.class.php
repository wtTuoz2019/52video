<?php
class  dataModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取数量
    public function getcount($where) {
        return $this->model->table('user')->where($where)->count();
    }
	  //获取数量
    public function getdatacount($where) {
        return $this->model->table('visit')->field('distinct uid')->where($where)->count();
    }
	
	  //获取数量
    public function getdatalist($where) {
        return $this->model->table('visit')->where($where)->select();
    }
	 public function getdatatime($where=array()){
        $data=$this->model->table('visit')->where($where)->field('sum((endtime-starttime)/60) as staytime')->find();
        return $data['staytime'];
    }
	
	 //获取数量
    public function getavg($where) {
        return $this->model->table('user')->where($where)->find();
    }
	
	 public function staytimeavg($where=array()){
        $data=$this->model->table('visit')->where($where)->field('avg((endtime-starttime)/60) asstaytime')->find();
        return $data['staytime'];
    }
	
	//城市统计	 
	public function city(){
		
		//$sql="SELECT city ,count(city) FROM `dc_user` group by city order by count(city) DESC ;";
		$sql="SELECT dc_user.city , count(dc_user.city) as count FROM  dc_user  where dc_user.uid is not null group by dc_user.city";
		
		$data=$this->model->query($sql);
		return $data;
		
		}
	public function sex(){
		
		
	 	$sql="SELECT dc_user.sex, count(dc_user.sex) as count FROM  dc_user  where dc_user.uid is not null group by dc_user.sex";
		$data=$this->model->query($sql);
		return $data;
		
		
		}

 
    
}

?>