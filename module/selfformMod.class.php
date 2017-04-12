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