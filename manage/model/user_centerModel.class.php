<?php

class user_centerModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function config(){
        $list=$this->model->table('user_config')->select();

        if(!empty($list)){
            foreach ($list as $value) {
                $config[$value['name']]=$value['value'];
            }
        }
        return $config;
    }

    public function config_data($data){
        if(is_array($data)){
            foreach ($data as $key=>$value) {
                $config=array();
                $config['value']=$value;
                $this->model->table('user_config')->data($config)->where('name="'.$key.'"')->update();
            }
        }
        return true;

    }


}