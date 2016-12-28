<?php

class user_center_friendsModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function del($uid)
    {
        $this->model->table('user_friends')->where('uid='.$uid.' OR fid='.$uid)->delete();
    }


}