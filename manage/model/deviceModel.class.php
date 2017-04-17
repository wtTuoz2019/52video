<?php
class  deviceModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function model_list($where=null) {
	
      return  $this->model->table('device')->where($where)->select();
	
        
    }
	
	 //列表
    public function count($where=null) {
	
      $data= $this->model->table('device')->where($where)->count();
		
        return $data;
    }
	
	public function channel_list($where=null){
		
		return  $this->model->field('A.id,A.sn,C.name')->table('device','A')->add_table('admin','B','A.cid=B.id')->add_table('school','C','B.cid=C.id')->where($where)->select();
		}
		public function getcsid($snid){
		
		$data=$this->model->field('C.id')->table('device','A')->add_table('admin','B','A.cid=B.id')->add_table('school','C','B.cid=C.id')->where('A.id='.$snid)->find();
		return $data['id'];
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
  	  public function peoples($id) {
        $where['did']=$id;

        return $this->model->field('A.*,B.headimgurl')->table('device_peoples','A')->add_table('user','B','A.uid=B.uid')->where($where)->select();
    }
  	  //保存
    public function addpeople_save($data) {
        
        return $this->model->table('device_peoples')->data($data)->insert();
    }
 //字段删除
    public function delpeople($id)
    {
        $status=$this->model->table('device_peoples')->where('id='.$id)->delete(); 
       
        return $status;
       
    }
     //保存
    public function save($data) {
        
        return $this->model->table('device')->data($data)->insert();
    }

     //保存
    public function edit_save($data) {
        
        return $this->model->table('device')->data($data)->where('id='.$data['id'])->update();
    }


	   public function baoxiu($id) {
        
        return $this->model->table('device')->data(array('error'=>1))->where('id='.$id)->update();
    }




    //字段删除
    public function del($id)
    {
        $status=$this->model->table('device')->where('id='.$id)->delete(); 
       
        return $status;
       
    } 

 
    
}

?>