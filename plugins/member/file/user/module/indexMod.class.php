<?php
class indexMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		//hook
        module('common')->plus_hook('index','index');
        //hook end
		$this->show();
	}


}