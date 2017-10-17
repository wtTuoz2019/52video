<?php
//field显示
class xueshiMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		$this->getuserinfo();
		
		var_dump($this->userinfo);
    } 

    public function index() {
		 
		
	}
	
}