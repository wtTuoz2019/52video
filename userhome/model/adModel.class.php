<?php
class  adModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function model_list() {
        return $this->model->table('ad')->order('sequence desc,id desc')->select();
    }
	
	//获取树形列表
    public function position_list($limit) {
       
        return $this->model->table('adposition')->order('sequence desc,id desc')->limit($limit)->select();
    }
	
	//获取树形列表
    public function position_list_count() {
       
        return $this->model->table('adposition')->count();
    }

    //获取树形列表
    public function ad_list($pid = 0,$limit) {
        return $this->model->table('ad')->where(array('pid'=>$pid))->limit($limit)->order('sequence desc,id desc')->select();
       
    }
	//获取树形列表
    public function ad_list_count($pid = 0) {
        return $this->model->table('ad')->where(array('pid'=>$pid))->count();
       
    }

    //获取模型信息
    public function table_info($table,$mid=null) {
        $where="`table`='".$table."'";
        if(!empty($mid)){
        $where.=' AND mid<>'.$mid;
        }
        return $this->model->table('ad')->where($where)->find();
    }
    //获取模型信息
    public function info($id) {
        $where['id']=$id;
		
        return $this->model->table('ad')->where($where)->find();
    }
	
	 //获取模型信息
    public function infoposition($id) {
        $where['id']=$id;
		
        return $this->model->table('adposition')->where($where)->find();
    }
    //保存广告位
    public function saveposition($data) {
        
        return $this->model->table('adposition')->data($data)->insert();
    }

     //保存
    public function save($data) {
        
        return $this->model->table('ad')->data($data)->insert();
    }
	
	   //保存
    public function peopleonflag($id) {
        
		
        $sql="
            SELECT A.*,B.realname 
             FROM dc_ad_mid as A 
             LEFT JOIN dc_member as B ON A.uid = B.id
             where A.flag=0 AND A.did=".$id."
            
            ";
        $data=$this->model->query($sql);
		
        return $data;
    } 
	
	//保存
    public function peopleflag($id) {
        
		
        $sql="
            SELECT A.*,B.realname 
             FROM dc_ad_mid as A 
             LEFT JOIN dc_member as B ON A.uid = B.id
             where A.flag=1 AND A.did=".$id."
            
            ";
        $data=$this->model->query($sql);
		
        return $data;
    } 


	

     //保存
    public function edit_save($data) {
        
        return $this->model->table('ad')->data($data)->where('id='.$data['id'])->update();
    }
	
	//保存
    public function editposition_save($data) {
        
        return $this->model->table('adposition')->data($data)->where('id='.$data['id'])->update();
    }

    //广告删除
    public function del($id)
    {
        $status=$this->model->table('ad')->where('id='.$id)->delete(); 
       
        return $status;
       
    }
	
	
    //广告位删除
    public function delposition($id)
    {
        $status=$this->model->table('adposition')->where('id='.$id)->delete(); 
       
        return $status;
       
    }
	


 
    
}

?>