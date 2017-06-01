<?php
class parentMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		$this->getuserinfo();
		$student=model('user')->student('A.uid='.$this->userinfo['uid']);

		if(!$student){
		$this->redirect('/login/parent');
			}else{
		if(!$student['mobile']||!$student['relation']){
		$this->redirect('/login/relation');	
			}		
				}
    }

	public function index() {
        
		
		$this->display('parents_usercenter.html');
	}
	
	public function kclist(){
		$this->display('parents_kclist.html');
		}
}
?>