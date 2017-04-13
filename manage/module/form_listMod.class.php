<?php
//表单管理
class form_listMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {

        $id=intval($_GET['id']);
        $this->alert_str($id,'int');
        //分页处理
        $url = __URL__ . '/index/id-' . $id . '-page-{page}.html';
        $listRows = 20;
        $limit=$this->pagelimit($url,$listRows);
        $this->info=model('form')->info($id);
        //内容列表
        $count=model('form_list')->count($id);
        $this->list=model('form_list')->form_list($id,$limit,$this->info['order']);
        $this->field_list=model('form_list')->field_list($id);
        $this->page = $this->page($url, $count, $listRows);
		$this->show();
	}
	
	public function signup() {
		$this->actionname="报名审核";
		$id=4;
        $this->aid=$aid=intval($_GET['aid']);
        $this->alert_str($aid,'int');
		$where='aid='.$aid;
			
		if($_GET['s']){
			$s=$_GET['s'];
			 $where.=" and  ( name = '".$s."' or mobile = '".$s."' )";
			}
	
        //分页处理
        $url = __URL__ . '/signup/aid-' . $aid . '-page-{page}.html';
        $listRows = 20;
        $limit=$this->pagelimit($url,$listRows);
        $info=model('content')->info($aid);
		 $info['field_lists']=unserialize($info['field_lists']);
		  $info['auditfield_lists']=unserialize($info['auditfield_lists']);
		  if(!is_array($info['auditfield_lists'])){
			$info['auditfield_lists']=array();
			}
        $this->info=$info;
		
        //内容列表
		$this->list=model('form_list')->form_signuplist($where,$limit);
        $count=model('form_list')->signupcount($where);
        
        $this->field_list=model('form_list')->field_list($id);
	
		if($_GET['download']){
			$list=model('form_list')->form_signuplist($where);
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=signup.xls");
		foreach($this->field_list as $k=>$v){
			echo iconv('utf-8','gbk',$v['name'])."\t";
			}
		
		echo iconv('utf-8','gbk','总时长(分钟)')."\n";
		echo iconv('utf-8','gbk','分时长')."\n";
			
			foreach($list as $key=>$value){
				 foreach($this->field_list  as $key1=> $model){
        
      	  if($model['admin_html']<>''){
        eval(html_out(str_replace('{content}', $value[$model['field']] ,$model['admin_html'])));
       		 }else{
        $string= model('expand_model')->get_list_model($model['type'],$value[$model['field']],$model['config']);
		
		echo  "\"".iconv('utf-8','gbk',  $string)."\r\n \""."\t";
      		  }
     	   }
				
				$sumtime=model('data')->getdatatime(array('aid'=>$aid,'uid'=>$value['uid']));
				echo iconv('utf-8','gbk',intval($sumtime))."\t";
				$times=model('data')->getdatalist(array('aid'=>$aid,'uid'=>$value['uid']));
				$timestring='';
				if(is_array($times)){
				 foreach($times as  $key2=>$time){
					 if($key2>0)$timestring.='###';
					$timestring.=date('Y-m-d H:i',$time['starttime']).'--'.date('Y-m-d H:i',$time['endtime']);	
					 }
				}
				echo iconv('utf-8','gbk',$timestring)."\n";
				}
			die;
			}
		
		
        $this->page = $this->page($url, $count, $listRows);
		$this->show();
	}
	
	public function signupauto() {
		$this->actionname="预审核列表";
		$id=4;
        $this->aid=$aid=intval($_GET['aid']);
        $this->alert_str($aid,'int');
		$where='aid='.$aid;
			
		if($_GET['s']){
			$s=$_GET['s'];
			 $where.=" and  ( name = '".$s."' or mobile = '".$s."' )";
			}
		
        //分页处理
        $url = __URL__ . '/signupauto/aid-' . $aid . '-page-{page}.html';
        $listRows = 20;
        $limit=$this->pagelimit($url,$listRows);
        $info=model('content')->info($aid);
		
		  $info['field_lists']=unserialize($info['auditfield_lists']);
        $this->info=$info;
		$this->field_list=model('form_list')->field_list($id);
		
		
		if($_FILES){
		$return=module('editor_upload')->upload();
			
			if($return['error'])$this->error($return['msg']);
			$data = new Spreadsheet_Excel_Reader();
			// 设置输入编码 UTF-8/GB2312/CP936等等
			$data->setOutputEncoding('UTF-8');
			$data->read('..'.$return['url']);
			$sheet=$data->sheets[0];
			$rows=$sheet['cells'];
			$temp=array();
		
			foreach($rows as  $key=>$val){
				if($key==1){
					foreach($val as $k=>$v){
							foreach($this->field_list as $i=>$j){
								if(trim($v)==$j['name']){
									$temp[$j['field']]=$k;
									}
							}
						}
					}else{
						$array=array();
						foreach($this->field_list as $i=>$j){
							if($val[$temp[$j['field']]])
							$array[$j['field']]=$val[$temp[$j['field']]];
							}
						if($array){
						$array['aid']=$aid;
						model('form_list')->addsignauto($array);	
							}	
						
						}
				
				}
			}
		
		
        //内容列表
		$this->list=model('form_list')->form_signupautolist($where,$limit);
        $count=model('form_list')->signupautocount($where);
        
        
	
		
        $this->page = $this->page($url, $count, $listRows);
		$this->show();
	}
	  //批量操作
    public function batch(){
        if(empty($_POST['status'])||empty($_POST['uid'])){
            $this->msg('请先选择内容！',0);
        }
        $id_array=substr($_POST['uid'],0,-1);
        $id_array=explode(',', $id_array);
		$aid=intval($_POST['aid']);
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
                    model('form_list')->delsign($value,$aid);
                }
                break;
           
        }
        $this->msg('操作执行完毕！',1);

    }
  //批量操作
    public function batchauto(){
        if(empty($_POST['status'])||empty($_POST['id'])){
            $this->msg('请先选择内容！',0);
        }
        $id_array=substr($_POST['id'],0,-1);
        $id_array=explode(',', $id_array);
		$aid=intval($_POST['aid']);
        switch ($_POST['status']) {
            
			 case '2':
                //审核
                foreach ($id_array as $value) {
                    model('form_list')->delsignauto($value,$aid);
                }
                break;
           
        }
        $this->msg('操作执行完毕！',1);

    }
    public function data_check($data)
    {
        //获取所有字段
        $field_list=model('form_list')->list_lod($data['fid']);
        if(empty($field_list)){
            $this->msg('未发现表单字段！',0);
        }
        foreach ($field_list as $value) {
            if($value['must']==1){
                if(empty($data[$value['field']])){
                    $this->msg($value['name'].'不能为空！',0);
                }
            }
            $data[$value['field']]=model('expand_model')->field_in($data[$value['field']],$value['type'],$value['field']);
        }
        return $data;
    }

    //表单添加
    public function add() {
        $id=$_GET['id'];
        $this->alert_str($id,'int');
        $this->field_list=model('form')->field_list($id);
        $this->form_info=model('form')->info($id);
        $this->action_name='添加';
        $this->action='add';
        $this->show('form_list/info'); 
    }

    //表单数据处理
    public function add_save() {
        $this->alert_str($_POST['fid'],'int',true);
        $data=$this->data_check($_POST);
        //处理完毕后交由模型处理数据
        $id=model('form_list')->add($data);
        model('upload')->relation('form',$data['file_id'],$id);
        $this->msg('添加成功！',1);

    }

    //表单编辑
    public function edit() {
        $fid=$_GET['fid'];
        $id=$_GET['id'];
        $this->alert_str($fid,'int');
        $this->alert_str($id,'int');
        $this->field_list=model('form')->field_list($fid);
        $this->form_info=model('form')->info($fid);
        $this->info=model('form_list')->info($id,$this->form_info['table']);
        $this->file_id=model('upload')->get_relation('form',$id);
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('form_list/info');
    }

    //内容编辑数据处理
    public function edit_save() {
        $this->alert_str($_POST['fid'],'int',true);
        $this->alert_str($_POST['id'],'int',true);
        $data=$this->data_check($_POST);
        //模型处理数据
        model('form_list')->edit($data);
        model('upload')->relation('form',$data['file_id'],$data['id']);
        $this->msg('编辑成功！',1);

    }

    //删除
    public function del() {
        $id=$_POST['id'];
        $fid=$_POST['fid'];
        $this->alert_str($_POST['fid'],'int',true);
        $this->alert_str($_POST['id'],'int',true);
        model('form_list')->del($id,$fid);
        model('upload')->del_file('form',$id);
        $this->msg('内容删除成功！',1);
    }


}