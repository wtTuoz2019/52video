<?php
class webModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function web_config($where=null){
		
		return $this->model->table('web_config')->where($where)->find();
		}
		
	public function web_config_save($data){
		
		if($this->model->table('web_config')->where(array('token'=>$data['token']))->find()){
		
		return $this->model->table('web_config')->where(array('token'=>$data['token']))->data($data)->update();
		
		}else{
			$this->model->table('web_config')->data($data)->insert();	
			}
		}
	public function menu_list($where){
		$data=$this->model->table('web_menu')->field('id,name,pid')->where($where)->order('sequence desc,id asc')->select();
		
		  $cat = new Category(array('id', 'pid', 'name', 'cname'));
        return $cat->getTree($data, 0);
		}
	public function menu_add_save($data){
		return $this->model->table('web_menu')->data($data)->insert();
		
		}
	public function menu_info($where){
		
		return $this->model->table('web_menu')->where($where)->find();
		}
	public function menu_edit_save($data){
		return $this->model->table('web_menu')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function pics_list($where){
		return $this->model->table('web_menu_pics')->where($where)->order('sequence desc,id asc')->select();
		
		}
	public function pics_add_save($data){
		return $this->model->table('web_menu_pics')->data($data)->insert();
		
		}
	public function pics_info($where){
		
		return $this->model->table('web_menu_pics')->where($where)->find();
		}
	public function pics_edit_save($data){
		return $this->model->table('web_menu_pics')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	    //栏目导航
    public function nav($id,$where)
    {
        $data = $this->model->field('id,pid,name')->table('web_menu')->where($where)->select();
        $cat = new Category(array(
            'id',
            'pid',
            'name',
           
            'cname'));
        if(empty($data)){
             return;
        }
        $cat = $cat->getPath($data, $id);
        return $cat; 
    }
	
    //栏目树
    public function getcat($id,$where)
    {
        $id = $cid;
        $data = $this->model->field('id,pid,name')->table('web_menu')->where($where)->select();
        $cat = new Category(array(
            'id',
            'pid',
            'name',
            'cname')); //初始化无限分类

        $cat_for = $cat->getTree($data, $id); //获取分类数据树结构
        if(empty($cat_for)){
            return $id;
        }
        foreach ($cat_for as $v) {
            $cat_id .= $v['id'] . ",";
        }

        if (!empty($cat_id)) {
            return $cat_id . $id;
        } else {
            return $id;
        }
    }
	
	    //栏目树
    public function getmids($where)
    {
       
        $data = $this->model->field('id,pid,name')->table('web_menu')->where($where)->select();
        $cat = new Category(array(
            'id',
            'pid',
            'name',
            'cname')); //初始化无限分类

        $cat_for = $cat->getTree($data, 0); //获取分类数据树结构
        if(empty($cat_for)){
            return $id;
        }
        foreach ($cat_for as $k=>$v) {
			if(!$k)$cat_id= $v['id'];
			else
            $cat_id .="," . $v['id'] ;
        }

        if (!empty($cat_id)) {
            return $cat_id ;
        } else {
            return $id;
        }
    }
	
	  //内容列表
    public function content_list($where,$limit)
    {
      
    
		
     
               $loop="
            SELECT {$expand_field}A.*,B.name as cname,B.subname as csubname,B.mid
             FROM {$this->model->pre}content A 
             LEFT JOIN {$this->model->pre}category B ON A.cid = B.cid
             {$expand}
             WHERE {$where} ORDER BY aid desc LIMIT {$limit}
            ";
        
            return $this->model->query($loop);
    }

    //内容统计
    public function content_count($where)
    {
        $count=$this->model
                ->table('content','A')
                ->add_table('category','B','A.cid = B.cid')
                ->where($where)
                ->count();
        return $count;
    }

	
}
?>