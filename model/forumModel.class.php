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
		return $this->model->table('forum_topics')->data($data)->insert();	
		
		
		}
	public function topics_list($where,$limit){
		
		return $this->model->field('A.*,B.nicename as name,headimgurl as pic')->table('forum_topics','A')->add_table('user','B','A.uid=B.uid')->where($where)->limit($limit)->order('A.top desc,A.sort desc,A.id desc')->select();	
		}
	public function topics_info($where){
		
		return $this->model->field('A.*,B.nicename as name,headimgurl as pic')->table('forum_topics','A')->add_table('user','B','A.uid=B.uid')->where($where)->find();	
		}
	public function zan_topics_list(){
		
		return $this->model->field('A.*,B.nicename as name,headimgurl as pic')->table('forum_topics','A')->add_table('user','B','A.uid=B.uid')->add_table('forum_zan','C','C.tid=A.id')->where($where)->limit($limit)->order('A.top desc,A.sort desc,A.id desc')->select();	
		
		}
	public function topics_count($where){
		
		return $this->model->table('forum_topics')->where($where)->count();	
		}
	public function topics_zan($data){
		if($this->model->table('forum_zan')->where($data)->find()){
			return $this->model->table('forum_zan')->where($data)->delete();
			}else{
				$data['time']=time();
			return $this->model->table('forum_zan')->data($data)->insert();	
				}
		 
		}
	public function topics_zan_list($where){
		return $this->model->field('A.uid,B.headimgurl as pic')->table('forum_zan','A')->add_table('user','B','A.uid=B.uid')->where($where)->select();	
	}
	
	public function topics_zan_count($where){
		return $this->model->table('forum_zan')->where($where)->count();
		}
	public function topics_zan_info($where){
		return $this->model->field('headimgurl as pic')->table('forum_zan','A')->add_table('user','B','A.uid=B.uid')->where($where)->find();	
	}
	
	public function topics_comment_save($data){ 
		
		return $this->model->table('forum_comment')->data($data)->insert();	
		}
	public function topics_comment_list($where){
		return $this->model->field('A.*,B.nicename as name,headimgurl as pic')->table('forum_comment','A')->add_table('user','B','A.uid=B.uid')->where($where)->limit($limit)->order('A.id desc')->select();
		}
	public function topics_comment_count($where){
		return $this->model->table('forum_comment')->where($where)->count();
		}
	public function forum_pv($where){
		$sql='update dc_forum_config set pv=pv+1 where '.$where;
		return $this->model->query($sql);
		}
	public function topics_del($where){
		if($this->model->table('forum_topics')->where($where)->delete()){
			$this->model->table('forum_comment')->where(array('tid'=>$where['id']))->delete();
			return $this->model->table('forum_zan')->where(array('tid'=>$where['id']))->delete();
			
			}
		
		}
	public function comment_del($where){
		
		return $this->model->table('forum_comment')->where($where)->delete();
			
		
		}
}

?>