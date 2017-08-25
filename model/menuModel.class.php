<?php
class menuModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取主菜单
    public function main_menu(){

        $user=model('user')->current();
        $menu_power=unserialize($user['menu_power']);
        $menu_list=$this->model->table('user_menu')->where('pid=0')->order('id asc')->select();
        foreach ($menu_list as $value) {
            if(in_array($value['id'], (array)$menu_power)){
                $list[]=$value;
            }
        }
        return $list;
    }

    //获取子菜单权限
    public function menu_list($pid=0) {
        $list=$this->model->table('user_menu')->where('pid='.$pid)->order('id asc')->select();
        $data=array();
        if(!empty($list)){
            foreach ($list as $value) {
               if($this->model_power($value['module'],'visit')){
                    $data[]=$value;
               }
            }
        }
        return $data;
    }

    //权限判断
    public function model_power($module,$action)
    {
        $user=model('user')->current();
        $info=$this->model->table('user_menu')->where('module="'.$module.'"')->find();
        if(empty($info)){
            return false;
        }
        $model_power=unserialize($user['model_power']);
        if(empty($model_power)){
            return false;
        }
        if(in_array($action,(array)$model_power[$info['id']])||in_array($info['id'],(array)$model_power[$info['id']])){
            return true;
        }
        return false;
    }

}

?>