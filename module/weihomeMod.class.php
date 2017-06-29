<?php
class weihomeMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
		
		//$this->getuserinfo();
    }
	
	public function index(){
		
		$id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$this->info=$info=model('weihome')->info(array('id'=>$id));	
		$this->menus=model('weihome')->menus(array('indexid'=>$id));	
		 $this->display($info['tpl'].'.html');
		
		}
}