<?php

class user_center_userModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function user_list($where=null,$limit,$order){
        if(!empty($where)){
            $where='WHERE '.$where;
        }

        $sql="
        SELECT A.*,B.name as gname
        FROM {$this->model->pre}user A 
        LEFT JOIN {$this->model->pre}user_group B ON A.gid = B.gid
        {$where}
        ORDER BY {$order}A.uid DESC
        LIMIT {$limit} 
        ";
        return $this->model->query($sql); 
    }

    public function count($where=null){
        if(!empty($where)){
            $where='WHERE '.$where;
        }
        $sql="
        SELECT count(*) as num
        FROM {$this->model->pre}user A 
        LEFT JOIN {$this->model->pre}user_group B ON A.gid = B.gid
        {$where}
        ";
        $data=$this->model->query($sql);
        return $data[0]['num'];
    }

    //内容信息
    public function info($uid){
        //读取模型表
        return $this->model->table('user')->where('uid='.$uid)->find();
    }

     //检测重复用户
    public function info_count($user,$uid=null)
    {
        if(!empty($uid)){
            $where=' AND uid<>'.$uid;
        }
        return $this->model->table('user')->where('username="'.$user.'"'.$where)->count(); 
    }

    //编辑用户内容
    public function edit($data)
    {
        $condition['uid']=intval($data['uid']);
        return $this->model->table('user')->data($data)->where($condition)->update();
    }

    //获取用户资料
    public function info_append($uid)
    {
        return $this->model->table('user_append')->where('uid='.$uid)->find();
    }

    //编辑用户资料
    public function info_edit($data)
    {
        $condition['uid']=intval($data['uid']);
        if($this->model->table('user_append')->where($condition)->count()){
            return $this->model->table('user_append')->data($data)->where($condition)->update();
        }else{
            $this->info_add($data);
        }
    }

    //添加用户资料
    public function info_add($data)
    {
        return $this->model->table('user_append')->data($data)->insert();
    }

    //删除用户内容
    public function del($uid)
    {
        $this->model->table('user_append')->where('uid='.intval($uid))->delete();
        return $this->model->table('user')->where('uid='.intval($uid))->delete(); 
    }

}