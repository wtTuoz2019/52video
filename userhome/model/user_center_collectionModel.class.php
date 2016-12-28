<?php

class user_center_collectionModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function del($uid)
    {
        $this->model->table('user_collection')->where('uid='.$uid)->delete();
    }


}