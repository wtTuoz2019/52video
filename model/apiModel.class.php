<?php
class apiModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function getteacherbymobile($where=null){
		$data= $this->model->field('A.id,A.uid,C.id as csid,A.name,C.name as schoolname,C.image as logo')->table('teacher','A')->add_table('admin','B','A.uid=B.id')->add_table('school','C','B.cid=C.id')->where($where)->find();
		if($data){$site=$this->web_site(array('uid'=>$data['uid']));
		if(strstr($site, '.')){
			$site='http://'.$site ;}else{
			$site='http://'.$site.'.shanyueyun.com';
			}
		$data['site']=$site;
		}
		return $data;
		}
		
	public function teacher_save($data){
		return $this->model->table('teacher')->where(array('id'=>$data['id']))->data($data)->update();
		
		}
	public function web_site($where){
		$data= $this->model->field('site')->table('web_config')->where($where)->find();
		return $data['site'];
		}


}