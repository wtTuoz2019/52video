<?php
class verificationModel extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }


    //验证码
    public function verify_image($action,$html=true){
    	$config=model('system')->config();
    	$status=intval($config['verification_'.$action]);
    	if($status){
    		if($html){
    			return '<li><label><span class="red">*</span> 验证码：</label> <img src="'.__APP__.'/verification/verify_img" alt="如果您无法识别验证码，请点图片更换" width="50" height="25" border="0" align="top" id="verifyImg" style="margin-left:3px;" onClick="fleshVerify()"/> <input class="text" name="checkcode" id="checkcode" type="text" style="width:50px !important" reg="[0-9]{4,4}" msg="验证码格式错误" > </li>';
    		}else{
    			return true;
    		}
    	}else{
    		if(!$html){
    			return true;
    		}
    	}
    }

    //判断验证码
    public function verify_image_data($action,$code){
        $config=model('system')->config();
        $status=intval($config['verification_'.$action]);
        if($status){
            if ($code == $_SESSION['verify']) {
                return true;
            }else{
                return false;
            }
        }
        return true;
        
    }

    //生成验证记录
    public function code($length,$time=0){
        $key=$this->get_key($length);
        if($this->test_key($key)){
            $data=array();
            $data['code']=$key;
            $data['starttime']=time();
            if($time){
                $data['stoptime']=time()+$time*3600;
            }else{
                $data['stoptime']=0;
            }
            $vid=$this->model->table('user_verify')->data($data)->insert(); 
            return array(
                'vid'=>$vid,
                'code'=>$key
                );
        }else{
            $this->code($length,$time);
        }
    }

    public function test_key($key){
        if($this->model->table('user_verify')->where('code="'.$key.'"')->count()){
            return false;
        }
        return true;
    }

    public function get_key($length = 12){
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';  
        for ( $i = 0; $i < $length; $i++ )  
        {
            $str .= substr($chars,mt_rand(0, strlen($chars) - 1),1);
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ]; 
        }
        return $str;
    }

    //查询验证码信息
    public function get_verify_code($vid){
        return $this->model->table('user_verify')->where('vid='.$vid)->find();
    }

    //查询验证过期
    public function verify_time($vid){
        $info=$this->get_verify_code($vid);
        if(empty($info)){
            return false;
        }
        if($info['stoptime']==0){
            return true;
        }
        if($info['stoptime']<time()){
            return false;
        }else{
            return true;
        }
    }

    //删除验证码
    public function del($vid){
        return $this->model->table('user_verify')->where('vid='.$vid)->delete(); 
    }

    //获取验证关系
    public function get_verify($action,$uid){
        $info=$this->model->table('user_verify_'.$action)->where('uid='.$uid)->find();
        if(empty($info)){
            return;
        }
        $verify=$this->get_verify_code($info['vid']);
        return array_merge($info,$verify);
    }

    //添加验证关系
    public function add_verify($action,$data){
        $condition['uid']=$data['uid'];
        if($this->model->table('user_verify_'.$action)->where($condition)->count()){
            $this->model->table('user_verify_'.$action)->data($data)->where($condition)->update();
        }else{
            $this->model->table('user_verify_'.$action)->data($data)->insert();
        }
    }

    //删除验证关系
    public function del_verify($action,$uid){
        $info=$this->get_verify($action,$uid);
        if(empty($info)){
            return false;
        }
        $this->model->table('user_verify_'.$action)->where('uid='.$uid)->delete();
        $this->del($info['vid']);
        return true;
    }

}