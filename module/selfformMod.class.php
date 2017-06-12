<?php
class selfformMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
		$this->getuserinfo();
    }

    public function index()
    {  	
	 $fid=in($_GET['fid']);
    	if(empty($fid)){
    		$this->error404();
    	}
	
    	$this->info=$info=model('selfform')->info($fid);
	
    	if(empty($info)){
    		$this->error404();
    	}
		
			//获取所有字段
    	$this->field_list=$field_list=model('selfform')->form_inputs_list(array('fid'=>$info['id']));
    	if(empty($field_list)){
            
                $this->msg('未发现表单信息！',0);
           
        }
	
	$signinfo=model('selfform')->input_value(array('fid'=>$fid,
	 			  'uid'=>$this->userinfo['uid']));
	if($signinfo){
		
		$signinfo['values']=unserialize($signinfo['values']);
		$this->signinfo=$signinfo;
	}
	
	$this->display('selfform_index.html');
	}
	//提交表单
    public function post()
    {
    	$fid=in($_POST['fid']);
    	if(empty($fid)){
    		$this->error404();
    	}
	
    	$info=model('selfform')->info($fid);
	
    	if(empty($info)){
    		$this->error404();
    	}
    	//获取所有字段
    	$field_list=model('selfform')->form_inputs_list(array('fid'=>$info['id']));
    	if(empty($field_list)){
            
                $this->msg('未发现表单信息！',0);
           
        }

        $data=array();
        foreach ($field_list as $value) {
            if($value['must']==1){
                if(empty($_POST[$value['field']])){
                    
                        $this->msg($value['name'].'不能为空！',0);
                   
                }
            }
            $data[$value['field']]=model('expand_model')->field_in($_POST[$value['field']],$value['type'],$value['field']);
            if(!isset($_POST[$value['field']])){
                $data[$value['field']]=$value['default'];
            }
        }

     $valuedata=array('fid'=>$fid,
	 			  'uid'=>$this->userinfo['uid'],
				  'aid'=>$_POST['aid'],
				  'values'=>serialize($data),
				  'time'=>time());
        //过滤完后提交表单
        model('selfform')->add_value($valuedata);
    
      
       
         $this->msg('提交成功');
       
        
    }
	
		//提交表单
    public function only_post()
    {
    	$fid=in($_POST['fid']);
    	if(empty($fid)){
    		$this->error404();
    	}
	if($_COOKIE['mobilecode']&&$_POST['code']!=$_COOKIE['mobilecode']){
			
			 $this->msg('手机验证码不对！',0);
			}
    	$info=model('selfform')->info($fid);
	
    	if(empty($info)){
    		$this->error404();
    	}
    	//获取所有字段
    	$field_list=model('selfform')->form_inputs_list(array('fid'=>$info['id']));
    	if(empty($field_list)){
            
                $this->msg('未发现表单信息！',0);
           
        }

        $data=array();
        foreach ($field_list as $value) {
            if($value['must']==1){
                if(empty($_POST[$value['field']])){
                    
                        $this->msg($value['name'].'不能为空！',0);
                   
                }
            }
            $data[$value['field']]=model('expand_model')->field_in($_POST[$value['field']],$value['type'],$value['field']);
            if(!isset($_POST[$value['field']])){
                $data[$value['field']]=$value['default'];
            }
        }
	if(model('selfform')->input_value(array('fid'=>$fid,
	 			  'uid'=>$this->userinfo['uid'],
				  'aid'=>$_POST['aid'],))){
			 $this->msg('请勿重复提交',0);		  
					  }
     $valuedata=array('fid'=>$fid,
	 			  'uid'=>$this->userinfo['uid'],
				  'aid'=>$_POST['aid'],
				  'values'=>serialize($data),
				  'time'=>time());
        //过滤完后提交表单
        model('selfform')->add_value($valuedata);
    
      
       
         $this->msg('提交成功',1);
       
        
    }
	//提交表单
    public function formin($formdata)
    {
		
	
    	$fid=in($formdata['fid']);
    	if(empty($fid)){
    		$this->error404();
    	}
	
    	$info=model('selfform')->info($fid);
	
    	if(empty($info)){
    		$this->error404();
    	}
    	//获取所有字段
    	$field_list=model('selfform')->form_inputs_list(array('fid'=>$info['id']));
    	if(empty($field_list)){
            
            return;
           
        }

        $data=array();
        foreach ($field_list as $value) {
            if($value['must']==1){
				
				
                if(empty($formdata[$value['field']])){
			
                    return;
                   
                }
            }
            $data[$value['field']]=model('expand_model')->field_in($formdata[$value['field']],$value['type'],$value['field']);
            if(!isset($formdata[$value['field']])){
                $data[$value['field']]=$value['default'];
            }
        }

     $valuedata=array('fid'=>$fid,
	 			  'uid'=>$this->userinfo['uid'],
				  'aid'=>$formdata['aid'],
				  'commentid'=>$formdata['commentid'],
				  'values'=>serialize($data),
				  'time'=>time());
				 	
        //过滤完后提交表单
        model('selfform')->add_value($valuedata);
    
       
         return true;
       
        
    }
}

?>