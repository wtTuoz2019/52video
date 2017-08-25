<?php
class selfformMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function index(){
		  $listRows=9;
        $url = __URL__ . '/index/page-{page}.html'; //分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		if($_GET['s']){
			$where['name']=array('like',"'%".$_GET['s']."%'");
			} 
				 
		 if($this->user['gid']==6){
				$temp;$temp[]=0;
		
				$temp[]=$this->user['id'];
				
			$nextuser=model('user')->admin_list(' AND pid='.$this->user['id']);
			if($nextuser){
			foreach($nextuser as $key=>$val){
				$temp[]=$val['id'];
				}
			} 
		 	$where[]=" uid  in (".implode(',',$temp).") ";
			 }else{
		if($this->user['cid'])	
	 	$where[]=" uid =".$this->user['id'];
			 }
			
		 $this->list=model('selfform')->form_list($where,$limit);
        $count=model('selfform')->count($where);
        $this->page=$this->page($url, $count, $listRows);
		 $this->show();
		}
		
	public function add(){
		$this->actionname='表单添加';
		$this->action='add';
		 $this->show('selfform/info');
		}
	public function add_save(){
		
		$_POST['uid']= $this->user['id'];
		$_POST['token']= $this->user['token'];
		$_POST['time']=time();
		model('selfform')->add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function edit(){
		
		 $this->action_name='表单编辑';
         $this->action='edit';
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$this->info=model('selfform')->form_info(array('id'=>$id));	
		 $this->show('selfform/info');
		}
	public function edit_save(){
		
	
		$_POST['time']=time();
		model('selfform')->edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function del(){
		 $id=$_POST['id'];
      
        $this->alert_str($_POST['id'],'int',true);
        model('selfform')->del($id);
      
        $this->msg('删除成功！',1);
		
		}
	public function inputs(){
		  $this->action='edit';
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$this->info=model('selfform')->form_info(array('id'=>$id));	
		 $this->list=model('selfform')->inputs_list(array('fid'=>$id));
		 $this->field_type=model('expand_model')->field_type();
		
		 $this->show();
		}
	public function inputs_add(){
		$this->actionname='添加';
		$this->action='inputs_add';
		 $fid=intval($_GET['fid']);
        $this->alert_str($fid,'int');
		$this->form=model('selfform')->form_info(array('id'=>$fid));	
		   $this->view()->assign(module('expand_model')->data_info());
		 $this->show('selfform/inputs_info');
		}
	public function inputs_add_save(){
		
		
		model('selfform')->inputs_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function inputs_edit(){
		$this->actionname='编辑';
		$this->action='inputs_edit';
		 $fid=intval($_GET['fid']);
        $this->alert_str($fid,'int');
		$this->form=model('selfform')->form_info(array('id'=>$fid));	
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		 $this->view()->assign(module('expand_model')->data_info());
		 $this->info=model('selfform')->form_inputs_info(array('id'=>$id));
		 $this->show('selfform/inputs_info');
		}
	public function input_del(){
		 $id=$_POST['id'];
        $fid=$_POST['fid'];
        $this->alert_str($_POST['fid'],'int',true);
        $this->alert_str($_POST['id'],'int',true);
        model('selfform')->form_input_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
	public function inputs_edit_save(){
		
	
		$_POST['time']=time();
		model('selfform')->inputs_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function infos(){
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$this->info=model('selfform')->form_info(array('id'=>$id));	
		 $this->formlist=model('selfform')->inputs_list(array('fid'=>$id));
		 $where=array('fid'=>$id);
		 if($_GET['download']){
			$list=model('form_list')->form_signuplist($aid);
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=signup.xls");
		foreach($this->formlist as $k=>$v){
			echo iconv('utf-8','gbk',$v['name'])."\t";
			}
		echo iconv('utf-8','gbk','时间')."\t";
		echo "\n";
			$list=model('selfform')->form_value_list($where);
		if(is_array($list)){
			foreach($list as $key=>$val){
				$value=unserialize($val['values']);
				
				foreach($this->formlist as $k=>$model){
				
						
			 $string=model('expand_model')->get_list_model($model['type'],$value[$model['field']],$model['options']);
		
				echo iconv('utf-8','gbk',  $string)."\t";
						
					}
				echo iconv('utf-8','gbk',  date('Y-m-d H:i:s',$val['time']))."\t";
					echo "\n";
				
				}
		
			}
			die;
			}
		
		 
		 
		  $listRows=9;
        $url = __URL__ . '/infos/id-'.$id.'-page-{page}.html'; //分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		
		$list=model('selfform')->form_value_list($where,$limit);
		if(is_array($list)){
			foreach($list as $key=>$val){
				$list[$key]['values']=unserialize($val['values']);
				}
		
			}
			
		 $this->list=$list;
        $count=model('selfform')->form_value_count($where);
        $this->page=$this->page($url, $count, $listRows);
		$this->show();
		}
	public function value_del(){
		 $id=$_POST['id'];
        $fid=$_POST['fid'];
        $this->alert_str($_POST['fid'],'int',true);
        $this->alert_str($_POST['id'],'int',true);
        model('selfform')->form_value_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
		  //批量操作
    public function value_batch(){
        if(empty($_POST['status'])||empty($_POST['id'])){
            $this->msg('请先选择内容！',0);
        }
        $id_array=substr($_POST['id'],0,-1);
        $id_array=explode(',', $id_array);
		$fid=intval($_POST['fid']);
        switch ($_POST['status']) {
            case '1':
                //审核
                foreach ($id_array as $value) {
                    model('form_list')->status($value,$aid,1);
                }
				break;
			 case '2':
                //审核
                foreach ($id_array as $value) {
                    model('selfform')->form_value_del($value,$fid);
                }
                break;
           
        }
        $this->msg('操作成功！',1);

    }
}