<?php
class  schoolModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function model_list($where=null,$limit=null) {
	
      $data= $this->model->table('school')->where($where)->limit($limit)->select();
		$temp=array();
		if($data){
		foreach($data as $k=>$v){
			$temp[$v['id']]=$v;
			}
		}
        return $temp;
    }
	

	 //列表
    public function count($where=null) {
	
      $data= $this->model->table('school')->where($where)->count();
		
        return $data;
    }
	
    //获取科室树形列表
    public function school_list($where=null) {
       
       $data= $this->model->table('school')->where($where)->select();
		$temp=array();
		if($data){
		foreach($data as $k=>$v){
			$temp[$v['id']]=$v;
			}
		}
        return $temp;
    }

	    //获取科室树形列表
    public function userschool_list($where=null) {
       
       $data= $this->model->field('A.*')->table('admin','A')->add_table('school','B','A.cid=B.id')->where($where)->select();
		$temp=array();
		if($data){
		foreach($data as $k=>$v){
			$temp[$v['id']]=$v;
			}
		}
        return $temp;
    }

    //获取模型信息
    public function table_info($table,$mid=null) {
        $where="`table`='".$table."'";
        if(!empty($mid)){
        $where.=' AND mid<>'.$mid;
        }
        return $this->model->table('school')->where($where)->find();
    }
    //获取模型信息
    public function info($id) {
        $where['id']=$id;

        return $this->model->table('school')->where($where)->find();
    }
  

     //保存
    public function save($data) {
        
        return $this->model->table('school')->data($data)->insert();
    }

     //保存
    public function edit_save($data) {
        
        return $this->model->table('school')->data($data)->where('id='.$data['id'])->update();
    }




    //字段删除
    public function del($id)
    {
        $status=$this->model->table('school')->where('id='.$id)->delete(); 
       
        return $status;
       
    }

 
    
}

?>