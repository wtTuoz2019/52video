<?php
class loginMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    // 登录页面
    public function index()
    {
        $this->display();
    }

    //登陆检测
    public function check(){
        if(empty($_POST['user'])||empty($_POST['password'])){
            $this->msg('帐号信息输入错误!',0);
        }
        //获取帐号信息
        $info=model('login')->user_info($_POST['user']);
        //进行帐号验证
        if(empty($info)){
            $this->msg('登录失败! 无此管理员帐号!',0);
        }
        if($info['password']<>md5($_POST['password'])){
            $this->msg('登录失败! 密码错误!',0);
        }
        if($info['status']==0){
            $this->msg('登录失败! 帐号已禁用!',0);
        }
		
		if($info['status']==2){
            $this->msg('账号在审核中，请耐心等待!',0);
        }
		
		
		
		
        //更新帐号信息
        $data['logintime']=time();
        $data['ip']=get_client_ip();
        $data['loginnum']=intval($info['loginnum'])+1;
        model('login')->edit($data,intval($info['id']));
        //更新登录记录
        model('log')->login_log($info);
        //设置登录信息
        $_SESSION[$this->config['SPOT'].'_user']=$info['id'];
		$_SESSION['token']=$info['token'];
        model('user')->current_user(false);
        $this->msg('登录成功!',1);
        
    }

    //退出
     public function logout(){
        if(empty($_POST)){
            $this->redirect(__APP__);
        }
        model('user')->current_user(true,true);
        unset($_SESSION[$this->config['SPOT'].'_user']);
        $this->msg('退出成功! ',0);
     }

	public function forget(){
		
		$this->display();
		}
	public function reg(){
		
		$this->display();
		}
	
	public function regaction(){
		if($_POST['code']!=$_SESSION['mobilecode']){
			
			 $this->msg('手机验证码不对！',0);
			}
		if(!$_POST['cardimage']){
		 $this->msg('请上传营业执照！',0);
            return;	
			}
		
		  if($_POST['password']<>$_POST['repassword']){
            $this->msg('两次密码输入不同！',0);
            return;
        }
        if(model('user')->count($_POST['user'])){
            $this->msg('帐号不能重复！',0);
            return;
        }
        $_POST['password']=md5($_POST['password']);
        //录入模型处理
		$_POST['status']=2;
		$_POST['gid']=2;
		  $_POST['regtime']=time();
       $_POST['ip']=get_client_ip();
        model('user')->add($_POST);
        $this->msg('用户添加成功！',1);
		
		}
	public function forgetaction(){
		if($_POST['code']!=$_SESSION['mobilecode']){
			
			 $this->msg('手机验证码不对！',0);
			}
		$info=model('user')->getinfo($_POST['user'],$POST['mobile']);
		if($info){
			$_POST['id']=$info['id'];
			}else{
			  $this->msg('你输入的信息不正确！',0);
               return;	
				}
		  if (!empty($_POST['password']))
        {
            if (empty($_POST['repassword']))
            {
               $this->msg('未填写确认密码！',0);
               return;
            }
            if($_POST['password']<>$_POST['repassword']){
                $this->msg('两次密码输入不同！',0);
                return;
            }
            if(model('user')->count($_POST['user'],$_POST['id'])){
                $this->msg('帐号不能重复！',0);
                return;
            }
            $_POST['password']=md5($_POST['password']);
        }else{
            $this->msg('未填写密码！',0);
               return;
        }
        $data['id']=$_POST['id'];
		 $data['password']=$_POST['password'];
        //录入模型处理
        model('user')->edit($_POST);
        $this->msg('重置密码成功! ',1);
		
		}
	
    //图片上传文件
    public function _image_upload() {
        $this->id=in($_GET['id']);
        $this->editor=in($_GET['editor']);
        $this->type=$this->upload_type();
        $this->limit=intval($this->config['ACCESSPRY_NUM']);
        $this->size=intval($this->config['ACCESSPRY_SIZE']);
        $this->display();
    }

    public function get_image_upload($button,$id,$ajax=false,$editor='') {
        $html="<script>
        $(document).ready(function() {
            $('#".$button."').click(function(){
                urldialog({
                    title:'图片上传',
                    url:'".__APP__."/login/_image_upload?id=".$id."&editor=".$editor."',
                    width:620,
                    height:240
                });
            });
        });
        </script>
        ";
        return $html;
    }
	   public function upload_type() {
        $type=$this->config['ACCESSPRY_TYPE'];
        $type=explode(',',$type);
        if(!empty($type)){
            foreach ($type as $value) {
                $str.='*.'.$value.';';
            }
            $str=substr($str,0,-1);
        }
        return $str;
    }

}

?>