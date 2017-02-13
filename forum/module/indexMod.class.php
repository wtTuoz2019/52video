<?php
class indexMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		//$this->getuserinfo();
    }

	public function index() {
		
		$this->common=model('pageinfo')->media();
		$this->display('index.html');
	}
}