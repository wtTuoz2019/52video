<?php
//论坛管理
class forumMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }
	public function index(){
		
		
		$this->show();
		}
	public function config(){
		
		
		$this->show();
		}
	public function comment(){
		
		$this->show();
		}
}
?>