<?php
//field显示
class xueshiMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		
		
		
    } 

    public function index() {
		 $this->getuserinfo();
		$user=model('schooluser')->xueshiuser(array('uid'=>$this->userinfo['uid'],'type'=>'xueshi'));
		if(!$user)$this->redirect("/xueshi/bind.html?".$this->urltoken);
		
		$where=array('userid'=>$user['cardno']);
		$hours=model('schooluser')->xueshihourslist($where);
			$list=array();
		foreach($hours as $key=>$value){
			$list['hours'][$value['endyear']]=$value;
			
			}
			$classes=model('schooluser')->xueshiclasslist($where);
				
			foreach($list['hours'] as $key=>$value){
				
				if(!$classes||($classes&&$key==(date('Y')-1)))
				 try {

    //解决OpenSSL Error问题需要加第二个array参数，具体参考 http://stackoverflow.com/questions/25142227/unable-to-connect-to-wsdl
    $client = new SoapClient("http://116.249.125.29/service/sdk.asmx?WSDL",
        array(
            "stream_context" => stream_context_create(
                array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    )
                )
            )
        )
    );
    //print_r($client->__getFunctions());
    //print_r($client->__getTypes());
	$parm = array('userid' =>$user['cardno'], 'pasword' => $user['password'],'year'=>$key);
	
	$result = $client->GetTeacherClass($parm);
  	$result = get_object_vars($result);  
		
	$result=(array)$result['GetTeacherClassResult'];
	 if(!is_array($result['string']))continue;
	
    foreach($result['string'] as $ke=>$val){
		$data=json_decode(str_replace("'",'"',$val),true);
		$data['year']=$key;
		$data['classname']=$data['pxxm'];
			$data['userid']=$value['userid'];
		 model('schooluser')->xueshiclassadd($data);
		}
			
		
   
} catch (SOAPFault $e) {
    print $e;
}
			}
		
		$classes=model('schooluser')->xueshiclasslist($where);
	
		if(!is_array($classes))$this->alert('接口错误，确认刷新',"/xueshi/index.html?".$this->urltoken);
		foreach($classes as $key=>$value){
				if(!$list['info'])$list['info']=array('xm'=>$value['xm'],'danwei'=>$value['title'],'userid'=>$value['userid']);
			
			$list['classes'][$value['year']+1][]=$value;
			
			}
		
		$this->info=$list;
		 $this->display('xueshi_index.html');
	}
	public  function unbind(){
		
		$user=model('schooluser')->xueshiuserdel(array('uid'=>$this->userinfo['uid'],'type'=>'xueshi'));
		$this->alert('解除成功',"/xueshi/bind.html?".$this->urltoken);
	}
	public  function bind(){
		$this->getuserinfo();
		 if($_POST){
			 if(!$_POST['userid'])$this->alert('账号必填');
			  if(!$_POST['password'])$this->alert('密码必填');
			 
			 try {

    //解决OpenSSL Error问题需要加第二个array参数，具体参考 http://stackoverflow.com/questions/25142227/unable-to-connect-to-wsdl
    $client = new SoapClient("http://116.249.125.29/service/sdk.asmx?WSDL",
        array(
            "stream_context" => stream_context_create(
                array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    )
                )
            )
        )
    );
    //print_r($client->__getFunctions());
    //print_r($client->__getTypes());
	$parm = array('userid' =>$_POST['userid'], 'pasword' => $_POST['password']);
	$result = $client->GetTeacherHour($parm);
  	$result = get_object_vars($result);  
	$result=(array)$result['GetTeacherHourResult'];
	 if(!is_array($result['string']))$this->alert($result['string']);
	 $user=array('cardno'=>$_POST['userid'], 'password' => $_POST['password'],'byuid'=>$this->config['uid'],'uid'=>$this->userinfo['uid'],'type'=>'xueshi');
	 model('schooluser')->xueshiuseradd($user);
    foreach($result['string'] as $key=>$value){
		$data=json_decode(str_replace("'",'"',$value),true);
		 model('schooluser')->xueshihoursadd($data);
		}
   
$this->redirect("/xueshi/index.html?".$this->urltoken);
} catch (SOAPFault $e) {
    print $e;
}
			 
			 }
		
		
		 $this->display('xueshi_bind.html');
		}
		public function search(){
			
			if(!$_GET['openid'])echo 0;;
			$where="A.type='xueshi' and openid='".$_GET['openid']."'";
			$user=model('schooluser')->xueshiuserbyopenid($where);
			if($user){
					
					$res['openid']=$_GET['openid'];
					model('comment')->wechat_add($res);	
				
						 try { 

    //解决OpenSSL Error问题需要加第二个array参数，具体参考 http://stackoverflow.com/questions/25142227/unable-to-connect-to-wsdl
    $client = new SoapClient("http://116.249.125.29/service/sdk.asmx?WSDL",
        array(
            "stream_context" => stream_context_create(
                array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    )
                )
            )
        )
    );
    //print_r($client->__getFunctions());
    //print_r($client->__getTypes());
	$parm = array('userid' =>$user['cardno'], 'pasword' => $user['password']);
	$result = $client->GetTeacherHour($parm);
  	$result = get_object_vars($result);  
	$result=(array)$result['GetTeacherHourResult'];
	 if(!is_array($result['string']))echo 0;

    foreach($result['string'] as $key=>$value){
		$data=json_decode(str_replace("'",'"',$value),true);
		 model('schooluser')->xueshihoursadd($data);
		}
   
} catch (SOAPFault $e) {
    print $e;
} 
				echo $user['cardno'];
				}else{
					
					echo 0;
					}
			
			}
}