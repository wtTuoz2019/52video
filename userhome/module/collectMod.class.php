<?php
//采集管理
class collectMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	 //采集列表
    public function index()
    {
        $this->list=model('collect')->model_list();
		$this->subject=model('diyfield')->field_list_data(2);
		$this->grade=model('diyfield')->field_list_data(1);
		
        $this->show();
    }
    //采集添加
    public function add()
    {
        $this->action_name='添加';
        $this->action='add';
        $this->subject=model('diyfield')->field_list_data(2);
		$this->grade=model('diyfield')->field_list_data(1);
        $this->display('collect/info');
    }
  //采集保存
    public function add_save()
    {
        model('collect')->save($_POST);
        /*hook end*/
        $this->msg('直播通道添加成功！',1);
    }

     //采集添加
    public function edit()
    {    $id=intval($_GET['id']);
        $this->action_name='编辑';
        $this->action='edit';
       $this->subject=model('diyfield')->field_list_data(2);
		$this->grade=model('diyfield')->field_list_data(1);
        $this->info=model('collect')->info($id);

        $this->display('collect/info');
    }

    //采集添加
    public function collect()
    {    
        $id=intval($_REQUEST['id']);
        $info = model('collect')->collect($id);
        if(!$info){
            $this->msg('操作错误',0);
        }else{
            $url = $info['url'];
            $data['api'] = '21232f297a57a5a743894a0e4a801fc3';
            $data['time'] = $info['updatetime'];
            //采集内容信息(点播、直播、新闻)
            $result = model('collect')->vpost($url,$data);
            $result = json_decode($result, ture);
            $arr = $result['data'];
            if($result['status']){
                foreach ($arr as $k => $v) {
                    $arr[$k]['oldaid'] = $v['aid'];
                    $arr[$k]['source'] = $id;
                    unset($arr[$k]['aid']);
                    $r = model('collect')->collect_query($arr[$k]['oldaid'], $id);
                    if (!$r) {
                        $aid = model('collect')->collect_add($arr[$k]);
                        model('collect')->collect_data_add($arr[$k], $id, $aid);
                    }else{
                        model('collect')->collect_edit($arr[$k]);
                        model('collect')->collect_data_edit($arr[$k]);
                    }
                }
            }

            // // //采集直播通道
            $channel_url = $url.'/channel';
            $channel_result = model('collect')->vpost($channel_url,$data);
            $channel_result = json_decode($channel_result, ture);
            $channel_arr = $channel_result['data'];
            if($channel_result['status']){
                foreach ($channel_arr as $k => $v) {
                    $channel_arr[$k]['oldid'] = $v['id'];
                    $channel_arr[$k]['source'] = $id;
                    unset($channel_arr[$k]['id']);
                    $r = model('collect')->channel_query($channel_arr[$k]['oldid'], $id);
                    if (!$r) {
                        model('collect')->channel_add($channel_arr[$k]);
                    }else{
                        model('collect')->channel_edit($channel_arr[$k]);
                    }
                }
            }

            //采集直播附属信息
            $live_url = $url.'/live';
            $live_result = model('collect')->vpost($live_url,$data);
            $live_result = json_decode($live_result, ture);
            $live_arr = $live_result['data'];
            if($live_result['status']){
                foreach ($live_arr as $k => $v) {
                    $live_arr[$k]['oldid'] = $v['id'];
                    $live_arr[$k]['source'] = $id;
                    unset($live_arr[$k]['id']);
                    $r = model('collect')->live_query($live_arr[$k]['oldid'], $id);            
                    if (!$r) {
                        model('collect')->live_add($live_arr[$k]);
                    }else{
                        model('collect')->live_edit($live_arr[$k]);
                    }
                }
            }


            //更新时间
            $res = model('collect')->collect_update($id);
            if ($res) {
               $this->msg('采集成功',1);
            }else{
                $this->msg('操作错误',0);
            }
        }
        
    }
  //采集保存
    public function edit_save()
    {
        

        model('collect')->edit_save($_POST);
       
        /*hook end*/
        $this->msg('直播通道编辑成功！',1);
    }
	

	//采集删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('collect')->del($_POST['id']);
        
        $this->msg('页面删除成功！',1);
    }

}