<?php
class  diyfieldModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function model_list() {
        return $this->model->table('diyfield')->order('sequence asc,id desc')->select();
    }

    //获取列表
    public function diyfield_list($id = 0) {
       
        $data=$this->model_list(); 
        
        $cat = new Category(array('id', 'pid', 'name', 'cname'));
        return $data;
    }
	
	  public function field_list_data($did)
    {
        $data=$this->model->table('diyfield_value')->field('id,name,pid')->where('did='.$did)->order('sequence asc,id asc')->select();
		$temp=array();
		if(!is_array($data)){
			return $temp;
		}
		foreach($data as $k=>$v){
			$temp[$v['id']]=$v;
			}
		return $temp;
    }
	
		  public function field_list_where($where,$Limit=null)
    {
        $data=$this->model->table('diyfield_value')->where($where)->limit($limit)->order('sequence desc')->select();
		$temp=array();
		if(!is_array($data)){
			return $temp;
		}
		foreach($data as $k=>$v){
			$temp[$v['id']]=$v;
			}
		return $temp;
    }
	
			  public function field_list_count($where)
    {
        $data=$this->model->table('diyfield_value')->where($where)->count();
		
		return $data;
    }

    //获取列表
    public function field_list($did = 0) {
       
        $data=$this->field_list_data($did); 
       
        $cat = new Category(array('id', 'pid', 'name', 'cname'));
         return $cat->getTree($data, 0);
    }


 
    //获取模型信息
    public function info($id) {
        $where['id']=$id;

        return $this->model->table('diyfield')->where($where)->find();
    }
  
  	//获取模型信息
    public function field_info($id) {
        $where['id']=$id;

        return $this->model->table('diyfield_value')->where($where)->find();
    }
  

     //保存
    public function save($data) {
        
        return $this->model->table('diyfield')->data($data)->insert();
    }

     //保存
    public function edit_save($data) {
        
        return $this->model->table('diyfield')->data($data)->where('id='.$data['id'])->update();
    }




    //字段删除
    public function del($id)
    {
        $status=$this->model->table('diyfield')->where('id='.$id)->delete(); 
       
        return $status;
       
    }
	
	  //保存
    public function field_save($data) {
        
        return $this->model->table('diyfield_value')->data($data)->insert();
    }

     //保存
    public function field_edit($data) {
        
        return $this->model->table('diyfield_value')->data($data)->where('id='.$data['id'])->update();
    }




    //字段删除
    public function field_del($id)
    {
        $status=$this->model->table('diyfield_value')->where('id='.$id.' or pid='.$id)->delete(); 
       
        return $status;
       
    }

 
    
}

?>