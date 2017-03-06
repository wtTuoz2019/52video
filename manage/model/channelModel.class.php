<?php
class  channelModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function model_list($where=null) {
	
      $data= $this->model->table('channel')->where($where)->select();
		$temp=array();
		if($data){
		foreach($data as $k=>$v){
			$temp[$v['id']]=$v;
			}
		}
        return $temp;
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

        return $this->model->table('channel')->where($where)->find();
    }
  

     //保存
    public function save($data) {
        
        return $this->model->table('channel')->data($data)->insert();
    }

     //保存
    public function edit_save($data) {
        
        return $this->model->table('channel')->data($data)->where('id='.$data['id'])->update();
    }




    //字段删除
    public function del($id)
    {
        $status=$this->model->table('channel')->where('id='.$id)->delete(); 
       
        return $status;
       
    }

 
    
}

?>