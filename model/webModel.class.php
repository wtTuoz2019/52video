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
	public function topics_list($where,$limit){
		
		return $this->model->field('A.*,B.nicename as name,headimgurl as pic')->table('web_topics','A')->add_table('user','B','A.uid=B.uid')->where($where)->limit($limit)->order('A.top desc,A.sort desc,A.id desc')->select();	
		}
	public function topics_count($where){
		
		return $this->model->table('web_topics')->where($where)->count();	
		}
	public function topics_comment_list($where){
		return $this->model->field('A.*,B.nicename as name,headimgurl as pic')->table('web_comment','A')->add_table('user','B','A.uid=B.uid')->where($where)->limit($limit)->order('A.id desc')->select();
		}
	public function topics_comment_count($where){
		return $this->model->table('web_comment')->where($where)->count();
		}
	public function topics_zan_count($where){
		
		return $this->model->table('web_zan')->where($where)->count();	
		}
	public function topics_del($id){
		return $this->model->table('web_topics')->where(array('id'=>$id))->delete();
		}
	public function comment_del($id){
		return $this->model->table('web_comment')->where(array('id'=>$id))->delete();
		}
	public function topics_top($id){
		if($this->model->table('web_topics')->where(array('id'=>$id,'top'=>1))->find()){
		return $this->model->table('web_topics')->where(array('id'=>$id))->data(array('top'=>0))->update();	
			}else{
		return $this->model->table('web_topics')->where(array('id'=>$id))->data(array('top'=>1))->update();
			}
		}
	public function topics_status($id){
	
		return $this->model->table('web_topics')->where(array('id'=>$id))->data(array('status'=>1))->update();	
			
		}
	public function comment_status($id){
	
		return $this->model->table('web_comment')->where(array('id'=>$id))->data(array('status'=>1))->update();	
			
		}
}
?>