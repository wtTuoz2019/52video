<?php
//会员组数据处理
class user_center_groupModel extends commonModel {

	public function __construct()
    {
        parent::__construct();
    }

    //获取会员组列表
    public function admin_list()
    {
        return $this->model->table('user_group')->order('gid asc')->select();
    }

    //会员组数量
    public function count()
    {
        return $this->model->table('user_group')->count();
    }


    //获取会员组内容
    public function info($gid)
    {
        return $this->model->table('user_group')->where('gid='.$gid)->find();
    }

    //添加会员组内容
    public function add($data)
    {
        return $this->model->table('user_group')->data($data)->insert();
    }

    //编辑会员组内容
    public function edit($data)
    {
        $condition['gid']=intval($data['gid']);
        return $this->model->table('user_group')->data($data)->where($condition)->update();
    }

    //删除会员组内容
    public function del($gid)
    {
        return $this->model->table('user_group')->where('gid='.intval($gid))->delete(); 
    }

    //获取菜单项目
    public function menu_list($pid=0) {
        return $this->model->table('user_menu')->where('pid='.$pid)->order('id asc')->select();
    }

    //获取子权限表
    public function user_power($pid)
    {
        return $this->model->table('user_power')->where('pid='.$pid)->order('sequence asc')->select();
    }

    


}