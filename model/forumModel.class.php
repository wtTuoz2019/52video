<?php
class forumModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function forum_config($where=null){
		
		return $this->model->table('forum_config')->where($where)->find();
		}
		
	public function forum_config_save($data){
		
		if($this->model->table('forum_config')->where(array('token'=>$data['token']))->find()){
		
		return $this->model->table('forum_config')->where(array('token'=>$data['token']))->data($data)->update();
		
		}else{
			$this->model->table('forum_config')->data($data)->insert();	
			}
		}
	public function topics_save($data){
		$this->model->table('forum_topics')->data($data)->insert();	
		
		
		}
}

?>