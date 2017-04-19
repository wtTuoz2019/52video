<?php
class apiMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
	}
	//public function index()
//    {   
//        $api = $_REQUEST['api'];
//        $t = $_REQUEST['time'] + 0;
//        if($api != "21232f297a57a5a743894a0e4a801fc3"){
//            $result = array(
//                'status' => "0",
//                'msg' => '非法操作'
//            );
//            echo json_encode($result);
//            exit;
//        }else{
//            $num = model('ajax')->getnum($t);
//            if($num <= 0){
//                $result = array(
//                    'status' => "0",
//                    'msg' => '暂无数据'
//                );
//            }else{
//                $data = model('ajax')->getres($t);
//                foreach ($data as $k => $v) {
//                    if ($v['image']) {
//                        $data[$k]['image'] = 'http://'.$_SERVER['HTTP_HOST'].$v['image'];
//                    };
//                    if ($v['videourl']) {
//                        $data[$k]['videourl'] = 'http://'.$_SERVER['HTTP_HOST'].$v['videourl'];
//                    };
//                    if($v['content']) {
//                        $subject = $v['content'];
//                        $search = 'src=&quot;/';
//                        $replace = 'src=&quot;'.'http://'.$_SERVER['HTTP_HOST'];
//                        $data[$k]['content'] = str_replace($search, $replace, $subject);
//                    }
//                }
//                $result = array(
//                    'status' => "1",
//                    'data' => $data,
//                    'num' => $num
//                );
//            };
//            echo json_encode($result);
//        };
//        
//    }
//
//    public function channel()
//    {   
//        $api = $_REQUEST['api'];
//        $t = $_REQUEST['time'] + 0;
//        if($api != "21232f297a57a5a743894a0e4a801fc3"){
//            $result = array(
//                'status' => "0",
//                'msg' => '非法操作'
//            );
//        }else{
//            $num = model('ajax')->get_channel_num($t);
//            if($num <= 0){
//                $result = array(
//                    'status' => "0",
//                    'msg' => '暂无数据'
//                );
//            }else{
//                $data = model('ajax')->get_channel_res($t);
//                $result = array(
//                    'status' => "1",
//                    'data' => $data,
//                    'num' => $num
//                );
//            };
//        };
//        echo json_encode($result);
//    }
//
//    public function live() {
//        $api = $_REQUEST['api'];
//        $t = $_REQUEST['time'] + 0;
//        if($api != "21232f297a57a5a743894a0e4a801fc3"){
//            $result = array(
//                'status' => "0",
//                'msg' => '非法操作'
//            );
//        }else{
//            $num = model('ajax')->get_live_num($t);
//            if($num <= 0){
//                $result = array(
//                    'status' => "0",
//                    'msg' => '暂无数据'
//                );
//            }else{
//                $data = model('ajax')->get_live_res($t);
//                $result = array(
//                    'status' => "1",
//                    'data' => $data,
//                    'num' => $num
//                );
//            };
//        };
//        echo json_encode($result);
//    }
//	
//		//直播推送
//	public function push()
//    {
//        //保存内容信息
//		$data = $_POST;
//		$data['image'] = model('ajax')->imgApp($_POST['image']);
//		$aid = $_POST['aid']=model('ajax')->push_add_save($data);
//		echo model('ajax')->push_add_content_save($data, $aid);
//    }
//	
//	public function push_channel(){
//		unset($_POST['id']);
//		$data = $_POST;
//		echo $aid = model('ajax')->addchannel($data);
//	}
//	
//	public function push_edit()
//	{
//		$aid = model('ajax')->getaid($_POST['oldaid']);
//		$data = $_POST;
//		$data['image'] = model('ajax')->imgApp($_POST['image']);
//        //保存内容信息
//        $status=model('ajax')->push_edit_save($data, $aid);
//		model('ajax')->push_edit_content_save($data, $aid);
//        /*hook*/
//        $this->plus_hook('live','edit');
//        /*hook end*/
//        $this->msg('内容编辑成功！',1);
//	} 
	public function getstatus(){
		
	 	$schoolname=urldecode($_GET['name']);
		$user=model('school')->user($schoolname);
		$return['code']=1;
		if($user){
			if($user['overtime'])
			$return['overtime']=$user['overtime'];
			else
			$return['overtime']='';
			}else{
				$return['overtime']=time()-1000;
				}
		echo json_encode($return);
		}
} 
?>