<?php
class  commentModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function model_list($limit, $where=null) {
	
      $data= $this->model->table('comment')->where($where)->limit($limit)->order('time desc,id desc')->select();
		
		$temp=array();
		if($data){
		foreach($data as $k=>$v){
			$temp[$v['id']]=$v;
			}
		}
		 return $temp;
    }

    public function count($where=null)
    {
        return $this->model->table('comment')->where($where)->count();
    }
	
	

    //获取科室树形列表
    public function comment_list($id = 0) {
       
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
        return $this->model->table('comment')->where($where)->find();
    }
    //获取模型信息
    public function info($id) {
        $where['id']=$id;

        return $this->model->table('comment')->where($where)->find();
    }
  

     //保存
    public function save($data) {
        
        return $this->model->table('comment')->data($data)->insert();
    }

     //保存
    public function edit_save($data) {
        
        return $this->model->table('comment')->data($data)->where('id='.$data['id'])->update();
    }




    //字段删除
    public function del($id)
    {
        $status=$this->model->table('comment')->where('id='.$id)->delete(); 
        return $status;
    }

    //审核通过
    public function examine($id)
    {   
        $data['flag'] = 1;
        $status=$this->model->table('comment')->data($data)->where('id='.$id)->update(); 
        return $status;
    }

    //获取用户信息
    public function info_user($uid) {
        if($uid > 0) {
           $data=$this->model->table('user')->where('uid='.$uid)->find();
           return $data['nicename']; 
        }else{
           return '匿名用户';
        } 
    }
		 //获取用户头像
    public function wetchheadpic($uid=0) {
		
       $info= $this->model
                    ->table('user')
                    ->where('uid='.$uid)
                    ->find();
		return $info['headimgurl'];
    }
    //获取评论标题
    public function info_title($fid) {
        if($fid > 0) {
           $data=$this->model->table('content')->where('aid='.$fid)->find();
           return $data['title']; 
        }else{
           return false;
        } 
    }



 
    
}

?>