<?php

class extendclassMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	 
    public function index(){
		
		 $this->show();
		
		}
		
	public function classes(){
		$this->actionname='班级管理';
		$this->action='inputs_add';
		 $fid=intval($_GET['fid']);
        $this->alert_str($fid,'int');
		 $this->show();
		
		}
}
?>