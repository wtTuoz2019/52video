<?php
class collectionModel extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	//获取登录列表
    public function friends_list($limit,$uid)
    {
        $sql="
        SELECT A.*,B.* 
        FROM {$this->model->pre}user_collection A 
        LEFT JOIN {$this->model->pre}content B ON A.aid = B.aid
        WHERE A.uid={$uid}
        LIMIT {$limit} 
        ";
        return $this->model->query($sql); 
    }

    //获取登录总数
    public function count($uid)
    {
        $sql="
        SELECT count(*) as num
        FROM {$this->model->pre}user_collection A 
        LEFT JOIN {$this->model->pre}content B ON A.aid = B.aid
        WHERE A.uid={$uid}
        ";
        $data=$this->model->query($sql);
        return $data[0]['num'];
    }

    //获取收藏信息
    public function info($uid,$aid)
    {
        return $this->model->table('user_collection')->where('uid='.$uid.' AND aid='.$aid)->find();

    }

    //获取内容信息
    public function content($aid)
    {
        return $this->model->table('content')->where('aid='.$aid.' AND status=1')->find();
    }

    //添加收藏
    public function add($data)
    {
        $data['aid']=$data['aid'];
        $data['uid']=$this->user_info['uid'];
        $data['remark']=Filter::xssToText($data['remark']);
        return $this->model->table('user_collection')->data($data)->insert();
    }

    public function del($uid,$aid) {
        return $this->model->table('user_collection')->where('uid='.$uid.' AND aid='.$aid)->delete();

    }



}