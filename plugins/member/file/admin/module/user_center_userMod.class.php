<?php

class user_center_userMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        //条件
        $sequence=intval($_GET['sequence']);
        switch ($sequence) {
            case '1':
                $order='A.reg_time DESC';
                $where_url='1';
                break;
            case '2':
                $order='A.reg_time ASC';
                $where_url='2';
                break;
        }
        if(!empty($order)){
        $order=$order.',';
        $where_url='-sequence-'.$where_url;
        }

        //状态
        $status=intval($_GET['status']);
        switch ($status) {
            case '1':
                $where=' A.status=1';
                $where_url='1';
                break;
            case '2':
                $where=' A.status=0';
                $where_url='2';
                break;
        }
        if(!empty($status)){
        $where_url='-status-'.$where_url;
        }

        //搜索
        $search=in(urldecode($_GET['search']));
        if(!is_utf8($search)){
            $search=auto_charset($search);
        }
        if(!empty($search)){
        $where=' A.username like "%' . $search . '%" OR  A.nicename like "%' . $search . '%"  OR  A.email like "%' . $search . '%" ';
        $where_url='-search-'.urlencode($search);
        }

        //用户组
        $gid=intval($_GET['gid']);
        if(!empty($gid)){
            $where=' B.gid='.$gid;
            $where_url='-gid-'.$gid;
        }

        //分页信息
        $url = __URL__ . '/index/page-{page}'.$where_url.'.html'; //分页基准网址
        $listRows = 20;
        $limit=$this->pagelimit($url,$listRows);
        //内容列表
        $this->list=model('user_center_user')->user_list($where,$limit,$order);
        $this->group_list=model('user_center_group')->admin_list();
        //统计总内容数量
        $count=model('user_center_user')->count($where);
        $this->assign('page', $this->page($url, $count, $listRows));
        $this->show();
    }

    //用户修改
    public function edit() {
        $uid=$_GET['uid'];
        $this->alert_str($uid,'int');
        $this->user_group=model('user_center_group')->admin_list();
        $this->info=model('user_center_user')->info($uid);
        $this->info_group=model('user_center_group')->info($this->info['gid']);
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('user_center_user/info');
    }

    //用户修改
    public function edit_save() {

        if (!empty($_POST['password']))
        {
            if (empty($_POST['password2']))
            {
               $this->msg('未填写确认密码！',0);
               return;
            }
            if($_POST['password']<>$_POST['password2']){
                $this->msg('两次密码输入不同！',0);
                return;
            }
            if(model('user_center_user')->info_count($_POST['username'],$_POST['uid'])){
                $this->msg('帐号不能重复！',0);
                return;
            }
            $_POST['password']=md5($_POST['password']);
        }else{
            unset($_POST['password']);
        }
        
        //录入模型处理
        model('user_center_user')->edit($_POST);
        $this->msg('用户修改成功! ',1);
    }

    //修改资料
    public function edit_info() {

        $uid=$_GET['uid'];
        $this->alert_str($uid,'int');
        //获取用户组
        $this->user_group=model('user_center_group')->admin_list();
        //获取用户及组信息
        $this->user_info=model('user_center_user')->info($uid);
        $this->info_group=model('user_center_group')->info($this->user_info['gid']);
        //获取附加字段信息
        $this->field_list=model('user_center_model')->field_list_data();
        $this->info=model('user_center_user')->info_append($uid);
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('user_center_user/edit_info');
    }

    public function data_check($data)
    {
        //获取所有字段
        $field_list=model('user_center_model')->field_list_data();
        if(empty($field_list)){
            $this->msg('未发现附加字段！',0);
        }
        foreach ($field_list as $value) {
            if($value['must']==1){
                if(empty($data[$value['field']])){
                    $this->msg($value['name'].'不能为空！',0);
                }
            }
            $data[$value['field']]=model('expand_model')->field_in($data[$value['field']],$value['type'],$value['field']);
        }
        return $data;
    }

    //用户修改
    public function edit_info_save() {
        $this->alert_str($_POST['uid'],'int',true);
        $data=$this->data_check($_POST);
        //录入模型处理
        model('user_center_user')->info_edit($data);
        $this->msg('用户资料修改成功! ',1);
    }

    //用户删除
    public function del() {
        $uid=intval($_POST['uid']);
        $this->alert_str($uid,'int',true);
        //录入模型处理
        model('user_center_user')->del($uid);
        model('user_center_friends')->del($uid);
        model('user_center_message')->del($uid);
        model('user_center_collection')->del($uid);
        $this->msg('用户删除成功！',1);
    }


}