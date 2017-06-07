<?php
class forumMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		  $this->forum=$forum= model('forum')->forum_config(array('uid'=>$this->config['uid']));
	  if(!$forum['isopen'])$this->alert('该论坛未开放');
		$this->getuserinfo();
    }

	public function index(){
	
	  if(!$this->userinfo){
		  	$code=$_COOKIE[$this->config['SPOT'].'_logincode'];
		if(!$code){
		$code=md5(get_client_ip().time());
         $cookie=$code;
        setcookie($this->config['SPOT'].'_logincode',$cookie);
		}
		$this->code=$code;
		$url = "http://".$this->config['MOBILE_DOMAIN']."/index.php/login/scope/logincode-".$code.".html";
		model('login')->gsetloginQrcode($url, $code);
		  
		  }
	  
	  $this->display('forum_index.html');
		
		}
	public function post(){
		
		
		  $this->display('forum_post.html');
		}
	public function mine(){
		
		
		  $this->display('forum_mine.html');
		}
}
?>