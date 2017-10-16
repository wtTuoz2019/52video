<?php
//老师管理
class teacherMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	 //老师列表
    public function index()
    {	 $user=model('user')->current_user();
		 
	if($user['gid']==6){
			$temp;
			$temp[]=0;
			if($user['cid']){
				$temp[]=$user['id'];
				}
			$nextuser=model('user')->admin_list(' AND pid='.$user['id']);
			if($nextuser){
			foreach($nextuser as $key=>$val){
				$temp[]=$val['id'];
				}
			}
			
			if($temp){
				$wheret='uid in ('.implode(',',$temp).') ';	
				}
			}else{
		
			
			if($user['cid']){
				$wheret='uid='.$uid;	
				}
		
     
			}
			
		
        $this->list=model('teacher')->model_list($wheret);
		$this->subject=model('diyfield')->field_list_data(2);
		$this->grade=model('diyfield')->field_list_data(1);
		
        $this->show();
    }
    //老师添加
    public function add()
    {
        $this->action_name='添加';
        $this->action='add';
      $this->subject=model('diyfield')->field_list_data(2);
		$this->grade=model('diyfield')->field_list_data(1);

        $this->display('teacher/info');
    }
  //老师保存
    public function add_save()
    {
        

        model('teacher')->save($_POST);
       
        /*hook end*/
        $this->msg('老师添加成功！',1);
    }

     //老师添加
    public function edit()
    {    $id=intval($_GET['id']);
        $this->action_name='编辑';
        $this->action='edit';
       $this->subject=model('diyfield')->field_list_data(2);
		$this->grade=model('diyfield')->field_list_data(1);
        $this->info=model('teacher')->info($id);

        $this->display('teacher/info');
    }
  //老师保存
    public function edit_save()
    {
        

     	model('teacher')->edit_save($_POST);
       
        /*hook end*/
        $this->msg('老师编辑成功！',1);
    }
	

	//老师删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('teacher')->del($_POST['id']);
        
        $this->msg('页面删除成功！',1);
    }

}