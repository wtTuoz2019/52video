<?php
class  workModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function model_list($where=null) {
	
      $data= $this->model->table('work')->where($where)->order('id desc')->select();
		
		if($data){
		foreach($data as $k=>$v){
			$data[$k]['headpic']=model('comment')->get_pic($v['uid']);
			$data[$k]['nickname']=model('comment')->getname($v['uid']);
			$data[$k]['wheadpic']=model('comment')->get_pic($v['wuid']);
			$data[$k]['wnickname']=model('comment')->getname($v['wuid']);
			}
		}
		 return $data;
    }
	
	

    //获取科室树形列表
    public function work_list($id = 0) {
       
        $data=$this->model_list(); 
        
        $cat = new Category(array('id', 'pid', 'name', 'cname'));
        return $cat->getTree($data, $id);
    }

 
    //获取模型信息
    public function info($id) {
        $where['id']=$id;

        return $this->model->table('work')->where($where)->find();
    }
  

     //保存
    public function save($data) {
        
        return $this->model->table('work')->data($data)->insert();
    }

     //保存
    public function edit_save($data) {
        
        return $this->model->table('work')->data($data)->where('id='.$data['id'])->update();
    }




    //字段删除
    public function del($id)
    {
        $status=$this->model->table('work')->where('id='.$id)->delete(); 
       
        return $status;
       
    }

 
    
}

?>