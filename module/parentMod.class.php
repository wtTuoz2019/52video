<?php
class parentMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        
		
		$this->display('parents_usercenter.html');
	}
}
?>