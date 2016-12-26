<?php
class messageModel extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	//获取登录列表
    public function message_list($limit,$where)
    {
        $sql="
        SELECT A.*,B.* 
        FROM {$this->model->pre}user_message_relation A 
        LEFT JOIN {$this->model->pre}user_message B ON A.mid = B.mid
        WHERE {$where}
        ORDER BY A.mid DESC
        LIMIT {$limit} 
        ";
        return $this->model->query($sql); 
    }

    //获取登录总数
    public function count($where)
    {
        $sql="
        SELECT count(*) as num
        FROM {$this->model->pre}user_message_relation A 
        LEFT JOIN {$this->model->pre}user_message B ON A.mid = B.mid
        WHERE {$where}
        ";
        $data=$this->model->query($sql);
        return $data[0]['num'];
    }

    //获取短信内容
    public function info($mid)
    {
        return $this->model
                ->field('A.*,B.*')
                ->table('user_message_relation','A')
                ->add_table('user_message','B','A.mid=B.mid')
                ->where('A.mid='.$mid)
                ->find();

    }

    //删除短信内容
    public function del($mid,$action)
    {
        $info=$this->model->table('user_message_relation')->where('mid='.$mid)->find();
        if($action=='del'){
            $action2='to_del';
        }else{
            $action2='del';
        }
        if($info[$action]==0&&$info[$action2]==1){
            $this->model->table('user_message_relation')->where('mid='.$mid)->delete();
            $this->model->table('user_message')->where('mid='.$mid)->delete();
        }else{
            $data[$action]=1;
            $this->model->table('user_message_relation')->data($data)->where('mid='.$mid)->update();
        }
        
    }

    //判断发送间隔
    public function post_time($uid)
    {
        $info = $this->model
                ->field('A.*,B.*')
                ->table('user_message_relation','A')
                ->add_table('user_message','B','A.mid=B.mid')
                ->where('A.uid='.$uid)
                ->order('B.mid DESC')
                ->find();
        if(intval($info['time'])+60>time()){
            return false;
        }else{
            return true;
        }
    }

    //添加短信
    public function add($data)
    {
        $data['content']=Filter::xssToText($data['content']);
        $data['time']=time();
        $data['ip']=get_client_ip();
        $data['view']=0;
        $mid=$this->model->table('user_message')->data($data)->insert();
        $data2['mid']=$mid;
        $data2['uid']=$this->user_info['uid'];
        $data2['to_uid']=$data['to_uid'];
        $data2['system']=$data['system'];
        $data2['del']=0;
        $data2['to_del']=0;
        return $this->model->table('user_message_relation')->data($data2)->insert();
    }

    //信息已读
    public function view($mid)
    {
        $data['view']=1;
        $this->model->table('user_message')->data($data)->where('mid='.$mid)->update();
    }


}