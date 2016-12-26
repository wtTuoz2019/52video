<?php
class  commentModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

  
	//获取树形列表
    public function comment_list($fid,$type,$limit) {
       
       $list=$this->model->table('comment')->where(array('fid'=>$fid,'type'=>$type))->limit($limit)->order('time desc')->select();
	   if(is_array($list)){
	   foreach($list as $k=>$v){
		  $list[$k]['width']=(100/5)*$v['score']; 
		   }
	   }
		return $list;
    }
	
	//获取树形列表
    public function comment_count($uid) {
       
        return $this->model->table('comment')->where(array('uid'=>$uid))->count();
    }
	
        //获取树形列表
    public function comment_id_count($uid) {
       
        return $this->model->table('comment')->where('fid='.$uid)->count();
    }
	
	  //保存
    public function save($data) {
        
        return $this->model->table('comment')->data($data)->insert();
    }
	
	//获取模型信息
    public function info($id) {
        $where['id']=$id;
		
        return $this->model->table('comment')->where($where)->find();
    }
	  //保存
    public function edit_save($data) {
         $data['updatetime']=time();
        return $this->model->table('comment')->data($data)->where('id='.$data['id'])->update();
    }
 //删除
    public function del($id)
    {
        $status=$this->model->table('comment')->where('id='.$id)->delete(); 
       
        return $status;
       
    }
 
    
}

?>