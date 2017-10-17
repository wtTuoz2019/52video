<?php
//field显示
class xueshiMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		$this->getuserinfo();
		
		
    } 

    public function index() {
		 
		$user=model('schooluser')->xueshiuser(array('uid'=>$this->config['uid'],'type'=>'xueshi'));
		if(!$user)$this->redirect("/xueshi/bind.html?".$this->urltoken);
	}
	
	public  function bind(){
		
		 $this->display('xueshi_bind.html');
		}
}