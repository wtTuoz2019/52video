<?php
class  deviceModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function model_list($where=null,$limit=null) {
	
      $data= $this->model->table('device')->where($where)->limit($limit)->select();
		$temp=array();
		if($data){
		foreach($data as $k=>$v){
			$temp[$v['id']]=$v;
			}
		}
        return $temp;
    }
	public function channel_list($where=null){
		
		return  $this->model->field('id,sn as name')->table('device')->where($where)->select();
		}
	
	 //列表
    public function count($where=null) {
	
      $data= $this->model->table('device')->where($where)->count();
		
        return $data;
    }
	

    //获取科室树形列表
    public function teacher_list($id = 0) {
       
        $data=$this->model_list(); 
        
        $cat = new Category(array('id', 'pid', 'name', 'cname'));
        return $cat->getTree($data, $id);
    }

    //获取模型信息
    public function table_info($table,$mid=null) {
        $where="`table`='".$table."'";
        if(!empty($mid)){
        $where.=' AND mid<>'.$mid;
        }
        return $this->model->table('teacher')->where($where)->find();
    }
    //获取模型信息
    public function info($id) {
        $where['id']=$id;

        return $this->model->table('device')->where($where)->find();
    }
  

     //保存
    public function save($data) {
        
        return $this->model->table('device')->data($data)->insert();
    }

     //保存
    public function edit_save($data) {
        
        return $this->model->table('device')->data($data)->where('id='.$data['id'])->update();
    }




    //字段删除
    public function del($id)
    {
        $status=$this->model->table('device')->where('id='.$id)->delete(); 
       
        return $status;
       
    }

 
    
}

?>