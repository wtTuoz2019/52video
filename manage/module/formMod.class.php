<?php
class formMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }
    //表单首页
    public function index()
    {
        $this->list=model('form')->form_list();
        $this->show();
    }

    //表单添加
    public function add() {
        $this->action_name='添加';
        $this->action='add';
        $this->show('form/info'); 
    }

    public function data_check($data) {
        if(model('form')->table_info($data['table'],$data['id'])){
            $this->msg('表名不能重复！',0);
        }
    }

    //表单添加数据处理
    public function add_save() {
        $this->data_check($_POST);
        model('form')->add($_POST);
        $this->msg('表单添加成功！',1);
    }

    //表单修改
    public function edit() {
        $id=$_GET['id'];
        $this->alert_str($id,'int');
        $this->info=model('form')->info($id);
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('form/info'); 
    }

    //表单修改
    public function edit_save() {
        $this->data_check($_POST);
        //录入模型处理
        model('form')->edit($_POST);
        $this->msg('表单修改成功! ',1);
    }

   
    //表单删除
    public function del() {
        $id=intval($_POST['id']);
        $this->alert_str($id,'int',true); 
        //录入模型处理
        model('form')->del($id);
        $this->msg('表单删除成功！',1);
    }

    //表单字段
    public function field_list() {
        $id=$_GET['id'];
        $this->alert_str($id,'int');
        $this->info=model('form')->info($id);
        $this->list=model('form')->field_list($id);
        $this->show();
    }

    public function field_data_check($data) {
        if(model('form')->field_check($data['fid'],$data['field'],$_POST['id'])){
            $this->msg('字段名不能重复',0);
        }
    }

    //添加字段
    public function field_add(){
        $fid=$_GET['fid'];
        $this->alert_str($fid,'int');
        $this->table_info=model('form')->info($fid);
        $this->view()->assign(module('expand_model')->data_info());
        $this->action_name='添加';
        $this->action='add';
        $this->show('form/field_info'); 
    }

    //字段添加
    public function field_add_save() {
        $fid=$_POST['fid'];
        $this->alert_str($fid,'int',true);
        $this->field_data_check($_POST);
        //录入模型处理
        model('form')->field_add($_POST);
        $this->msg('字段添加成功！',1);
    }

    //修改字段
    public function field_edit()
    {
        $id=intval($_GET['id']);
        $this->alert_str($id,'int');
        $this->info=model('form')->field_info($id);
        $this->table_info=model('form')->info($this->info['fid']);
        $this->view()->assign(module('expand_model')->data_info());
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('form/field_info'); 
    }

    //字段数据修改
    public function field_edit_save()
    {
        $this->alert_str($_POST['fid'],'int',true);
        $this->alert_str($_POST['id'],'int',true);
        $this->field_data_check($_POST);
        //录入模型处理
        model('form')->field_edit($_POST);
        $this->msg('字段修改成功！',1);
    }

    //字段删除
    public function field_del()
    {
        $this->alert_str($_POST['id'],'int',true);
        //录入模型处理
        model('form')->field_del($_POST);
        $this->msg('字段删除成功！',1);
    }

}

?>