<?php
class friendsModel extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	//获取登录列表
    public function friends_list($limit,$uid)
    {
        $sql="
        SELECT A.*,B.* 
        FROM {$this->model->pre}user_friends A 
        LEFT JOIN {$this->model->pre}user B ON A.fid = B.uid
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
        FROM {$this->model->pre}user_friends A 
        LEFT JOIN {$this->model->pre}user B ON A.fid = B.uid
        WHERE A.uid={$uid}
        ";
        $data=$this->model->query($sql);
        return $data[0]['num'];
    }

    //获取好友信息
    public function info($uid,$fid)
    {
        return $this->model->table('user_friends')->where('uid='.$uid.' AND fid='.$fid)->find();

    }

    //添加好友
    public function add($data)
    {
         $data['fid']=$data['fid'];
         $data['uid']=$this->user_info['uid'];
         $data['remark']=Filter::xssToText($data['remark']);
        return $this->model->table('user_friends')->data($data)->insert();
    }

    //修改备注
    public function edit($data)
    {
        $data['remark']=Filter::xssToText($data['remark']);
        return $this->model->table('user_friends')->data($data)->where('uid='.$this->user_info['uid'].' AND fid='.$data['fid'])->update();
    }

    public function del($uid,$fid) {
        return $this->model->table('user_friends')->where('uid='.$uid.' AND fid='.$fid)->delete();

    }



}