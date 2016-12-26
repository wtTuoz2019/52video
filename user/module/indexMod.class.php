<?php
class indexMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		$this->about = model('content')->about();
		//hook
        module('common')->plus_hook('index','index');
        //hook end
		$this->show();
	}


}