<?php
class user_center_modelMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }
    

    //模型字段
    public function index() {
        $this->list=model('user_center_model')->field_list();
        $this->show();
    }

    public function field_data_check($data) {
        if(model('user_center_model')->field_check($data['mid'],$data['field'],$_POST['id'])){
            $this->msg('字段名不能重复',0);
        }
    }

    //添加字段
    public function add(){
        $this->view()->assign(module('expand_model')->data_info());
        $this->field_type=model('user_center_model')->field_type();
        $this->action_name='添加';
        $this->action='add';
        $this->show('user_center_model/info'); 
    }

    //字段添加
    public function add_save() {
        $this->field_data_check($_POST);
        //录入模型处理
        model('user_center_model')->add($_POST);
        $this->msg('字段添加成功！',1);
    }

    //修改字段
    public function edit()
    {
        $id=intval($_GET['id']);
        $this->alert_str($id,'int');
        $this->info=model('user_center_model')->field_info($id);
        $this->view()->assign(module('expand_model')->data_info());
        $this->field_type=model('user_center_model')->field_type();
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('user_center_model/info'); 
    }

    //字段数据修改
    public function edit_save()
    {
        $this->alert_str($_POST['id'],'int',true);
        $this->field_data_check($_POST);
        //录入模型处理
        model('user_center_model')->field_edit($_POST);
        $this->msg('字段修改成功！',1);
    }

    //字段删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
        //录入模型处理
        model('user_center_model')->field_del($_POST);
        $this->msg('字段删除成功！',1);
    }

}

?>