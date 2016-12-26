<?php
class homeMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		$uid=intval($_GET[0]);
		if(empty($uid)){
			$this->error404();
		}
		$this->info=model('user')->info($uid);
		if(!$this->info){
			$this->error404();
		}
		$this->friends_list=model('collection')->friends_list(40,$uid);
		$this->show();
	}


}