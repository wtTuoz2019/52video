<?php
class informationModel extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

    public function base_save($data) {

    	unset($data['username']);
    	$where['uid']=$data['uid'];
        $uid=$this->model->table('user')->data($data)->where($where)->update();
        model('user_model')->save_append($data,$data['uid']);
        return $uid;
	}


}