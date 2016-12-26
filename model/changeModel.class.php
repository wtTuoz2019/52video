<?php
class changeModel extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    public function praise($aid)
    {
        $result = $this->model->table('content')->where('aid=' . $aid)->find(); 
        $time=time();
        $laud = $result['laud']+1;
        $sql="UPDATE {$this->model->pre}content SET laud = $laud, updatetime =  $time WHERE aid = $aid ";
        $data=$this->model->query($sql);
        return $data;
    }

    public function wechat($openid)
    {
        $sql="SELECT id, nickname, sex, headimgurl, openid FROM tp_wechat_group_list where openid='$openid'";
        $data=$this->model->query($sql);
        return $data;
    }

    public function getres($t)
    {
        $sql="SELECT  a.*, b.content FROM {$this->model->pre}content as a, {$this->model->pre}content_data as b where a.aid=b.aid and updatetime>$t";
        $data=$this->model->query($sql);
        return $data;
    }

    public function getnum($t)
    {
        $sql="SELECT count(*) as num FROM {$this->model->pre}content as a, {$this->model->pre}content_data as b where a.aid=b.aid and updatetime>$t";
        $data=$this->model->query($sql);
        return $data[0]['num'];
    }

    public function status_c($fid, $uid){
        $sql="SELECT * FROM {$this->model->pre}user_collection where aid='$fid' and uid='$uid'"; 
        $data=$this->model->query($sql);
        return $data;
    }

    public function collection($fid, $uid){
        $data['aid'] = $fid;
        $data['uid'] = $uid;
        return $this->model->table('user_collection')->data($data)->insert();
    }

    public function collection_number($aid){
        $sql="SELECT count(*) as num FROM {$this->model->pre}user_collection where aid='$aid'";
        $data=$this->model->query($sql);
        return $data[0]['num'];
    }




}

?>