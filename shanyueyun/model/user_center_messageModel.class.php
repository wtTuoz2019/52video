<?php

class user_center_messageModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function del($uid)
    {
        $list=$this->model->table('user_message_relation')->where('uid='.$uid.' OR to_uid='.$uid)->select();
        if(!empty($list)){
            foreach ($list as $value) {
                $this->model->table('user_message')->where('mid='.$value['mid'])->delete();
            }
        }
        $this->model->table('user_message_relation')->where('uid='.$uid.' OR to_uid='.$uid)->delete();
        
    }


}