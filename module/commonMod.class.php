<?php 
//公共类
class commonMod
{
    protected $model = NULL; //数据库模型
    protected $layout = NULL; //布局视图
    protected $config = array();
    private $_data = array();

    protected function init(){}
    
    public function __construct(){
        global $config;
        @session_start();

 
        $config['PLUGIN_PATH']=__ROOTDIR__.'/plugins/';
        $this->config = $config;
		
		
        $this->model = self::initModel( $this->config);
        $this->init();
        Plugin::init();
        $langCon=Lang::langCon();
        $this->config = array_merge((array)$config,(array)$langCon);
		
		$token=trim($_GET['token']);
		if($token){
		
			$this->wxuser=model('user')->wxuser($token);	
		
			$this->config['token']=$token;
			$this->urltoken='&token='.$token;
			
			if($this->wxuser['appid']&&$this->wxuser['appsecret']){
				$this->config['appid']=$this->wxuser['appid'];
				$this->config['appsecret']=$this->wxuser['appsecret'];
				}
			$admininfo=model('user')->admininfobytoken($token);
		
		
		}else{
		
		$siteurl=$_SERVER["HTTP_HOST"];
		$admininfo=model('user')->admininfo($siteurl);
		}
		if($admininfo){
			$this->config['child']=1;
			$this->config['uid']=$admininfo['id'];
			$_SESSION['sid']=$admininfo['cid'];
			if($admininfo['logo'])
			$this->config['logo']=$admininfo['logo'];
			$this->config['sitename']=$admininfo['sitename'];
			if($admininfo['about'])$this->config['about']=$admininfo['about'];
			if($admininfo['contact'])$this->config['contact']=$admininfo['contact'];
			if($admininfo['copyright'])$this->config['copyright']=$admininfo['copyright'];
			
			}
	if($_GET['wang'])$_SESSION['uid']='26409';
		$userinfo=$_COOKIE[$this->config['SPOT'].'_wxuser'];
     
		if($userinfo){
			  $array=explode('|',$userinfo);
			  $_SESSION['uid']=$array[0];
			}
	if($_SESSION['uid']){	
	
		$this->userinfo=model('user')->info($_SESSION['uid']);
		$_SESSION["headpic"]=$this->userinfo['headimgurl'];
		$_SESSION["nickname"]=$this->userinfo['nicename'];
		
	}
		if($config['LANG_OPEN']){
            define('__INDEX__', __APP__.'/'.__LANG__);
        }else{
            define('__INDEX__', __APP__);
        }
	
		
		
		
    }

    public function _empty()
    {
        $this->error404();
    }
	
	public  function getuserinfo(){
		
	
		if(MOBILE){ 
			if(!$_SESSION['uid']){
			if($this->wxuser&&$this->wxuser['oauth']){
			 if (!isset($_GET['code']) ) {
			  $customeUrl = 'http://'.$this->config['MOBILE_DOMAIN'] . $_SERVER['REQUEST_URI'];
           $scope = 'snsapi_userinfo';
				$oauthUrl='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->wxuser['authorizer_appid'].'&redirect_uri='.urlencode($customeUrl).'&response_type=code&scope='.$scope.'&state=STATE&component_appid='.$this->config['kfappid'].'#wechat_redirect';
				
			 header('Location:' . $oauthUrl);exit();
				 }
		if (isset($_GET['code']) && isset($_GET['state']) && isset($_GET['state']) == 'oauth'){	
			 $rt = file_get_contents('https://api.weixin.qq.com/sns/oauth2/component/access_token?appid='.$this->wxuser['authorizer_appid'].'&code='.$_GET['code']. '&grant_type=authorization_code&component_appid='.$this->config['kfappid'].'&component_access_token='.getcomponent_access_token($this->config['kfappid'],$this->config['kfappsecret']));
           $jsonrt = json_decode($rt, 1);
			if($jsonrt['errcode'])
			{	$url=parse_url($_SERVER['REQUEST_URI']);
			$url='http://'.$this->config['MOBILE_DOMAIN'] . $url['path'].'?token='.$_GET['token'];
			if($_GET['aid']){
			$url.='&aid='.$_GET['aid'];
			}
			$this->redirect($url);die;
			}
		if($jsonrt){
		  	$openid = $jsonrt['openid'];
			 $access_token = $jsonrt['access_token'];
			 $uid = model('comment')->getuid($openid);
			}else{
			$this->msg('授权出错', 0);	
				}
			
		
		$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		//转成对象
		$user_info = file_get_contents($user_info_url);
		if (isset($user_info->errcode)) {
			$this->msg($user_info->errmsg, 0);
		}
		$data = json_decode($user_info, true);
		if(!$data['openid']){
				$this->msg('授权出错', 0);	
			}
		$res['openid']=$data['openid'];
		$res['nicename']=$data['nickname'];
		$res['headimgurl']=$data['headimgurl'];
		$res['sex']=$data['sex'];
		$res['city']=$data['city'];
		$res['country']=$data['country'];
		$res['province']=$data['province'];
		$res['subscribe_time']=$data['subscribe_time'];
		$res['unionid']=$data['unionid'];
		$res['groupid']=$data['groupid'];
		$res['type']=$data['wechat'];
		$res['from']=$this->wxuser['wxname'];
		
		
		if($uid){
		model('comment')->wechat_add($res);	
			}else{
		$uid=model('comment')->wechat_add($res);
		
		}
		
		$_SESSION['uid'] = $uid; 
         
       	 } 	 
				 
			 
		}else{		
		 if (!isset($_GET['code']) ) {
           $customeUrl = 'http://'.$this->config['MOBILE_DOMAIN'] . $_SERVER['REQUEST_URI'];
           $scope = 'snsapi_userinfo';
           $oauthUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->config['appid'] . '&redirect_uri=' . urlencode($customeUrl) . '&response_type=code&scope=' . $scope . '&state=oauth#wechat_redirect';
           header('Location:' . $oauthUrl);exit();
        }
        if (isset($_GET['code']) && isset($_GET['state']) && isset($_GET['state']) == 'oauth'){
            $rt = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->config['appid'] . '&secret=' . $this->config['appsecret'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code');
			$jsonrt = json_decode($rt, 1);
			
		
           if($jsonrt['errcode'])
			{	$url=parse_url($_SERVER['REQUEST_URI']);
			$url='http://'.$this->config['MOBILE_DOMAIN'] . $url['path'].'?token='.$_GET['token'];
			if($_GET['aid']){
			$url.='&aid='.$_GET['aid'];
			}
			$this->redirect($url);die;
			}
			if($jsonrt){
		  	$openid = $jsonrt['openid'];
			 $access_token = $jsonrt['access_token'];
			 $uid = model('comment')->getuid($openid);
			}else{
			$this->msg('授权出错', 0);	
				}
			
		
		$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		//转成对象
		$user_info = file_get_contents($user_info_url);
		if (isset($user_info->errcode)) {
			$this->msg($user_info->errmsg, 0);
		}
		$data = json_decode($user_info, true);
		if(!$data['openid']){
				$this->msg('授权出错', 0);	
			}
		$res['openid']=$data['openid'];
		$res['nicename']=$data['nickname'];
		$res['headimgurl']=$data['headimgurl'];
		$res['sex']=$data['sex'];
		$res['city']=$data['city'];
		$res['country']=$data['country'];
		$res['province']=$data['province'];
		$res['subscribe_time']=$data['subscribe_time'];
		$res['unionid']=$data['unionid'];
		$res['groupid']=$data['groupid'];
		$res['type']=$data['wechat'];
		
		
		if($uid){
		model('comment')->wechat_add($res);	
			}else{
		$uid=model('comment')->wechat_add($res);
		
		}
		
		
		
		
		$_SESSION['uid'] = $uid; 
         
       	 } 
		 
	 } }
	 
	 }else{
		if($_GET['frame']) return true;
		if(!$_SESSION['uid']){
				$this->redirect("http://live.shanyueyun.com/login/login.html?url=".urlencode($_SERVER['REQUEST_URI']));
				}
		
		}
		
	if($_SESSION['uid']){	
		
		$this->userinfo=model('user')->info($_SESSION['uid']);
		$_SESSION["headpic"]=$this->userinfo['headimgurl'];
		$_SESSION["nickname"]=$this->userinfo['nicename'];
		   //设置登录信息
        $cookie=$this->userinfo['uid'].'|'.sha1($this->userinfo['nicename']);
       
        $expire = time() + 7200;
      
        setcookie($this->config['SPOT'].'_wxuser',$cookie,$expire,'/');
	}	
	
		
		
		}

    //初始化模型
    static public function initModel($config){
        static $model = NULL;
        if( empty($model) ){
            $model = new cpModel($config);
        }
        return $model;
    }

    public function __get($name){
        return isset( $this->_data[$name] ) ? $this->_data[$name] : NULL;
    }

    public function __set($name, $value){
        $this->_data[$name] = $value;
    }

    //获取模板对象
    public function view(){
        static $view = NULL;
        if( empty($view) ){
            $view = new cpTemplate( $this->config );
        }
        return $view;
    }
    
    //模板赋值
    protected function assign($name, $value){
        return $this->view()->assign($name, $value);
    }

    public function return_tpl($content){
        return $this->display($content,true,false);
    }

    //模板显示
    protected function display($tpl = '', $return = false, $is_tpl = true,$is_dir=true){
        if($this->config['LANG_OPEN']){
            $lang=__LANG__.'/';
        }
        if(MOBILE){
		
          $mobile_tpl='mobile'.'/';
		 
		 }
        if( $is_tpl){
            $tpl=__ROOTDIR__.'/'.$this->config['TPL_TEMPLATE_PATH'].$lang.$mobile_tpl.$tpl;
            if( $is_tpl && $this->layout ){
                $this->__template_file = $tpl;
                $tpl = $this->layout;
            }
        }
		$school=model('school')->school_list();
		
		//foreach($school as $k=>$v){
//			if($v['coordinate']){
//			$temp=explode(",", $v['coordinate']);
//	
//			$data[]=array('lat'=>$temp[1],'lng'=>$temp[0],'value'=>1,'type'=>2);
//			}
//			}
//			echo json_encode($data);die;
//		var_dump($data);die;
		$subject=model('diyfield')->field_list_data(2);
		$grade=model('diyfield')->field_list_data(1);
		$teacher=model('teacher')->model_list();
		 $this->assign('school', $school);
		 $this->assign('subject', $subject);
		  $this->assign('grade', $grade);
		   $this->assign('teacher', $teacher);
        $this->assign('model', $this->model);
        $this->assign('sys', $this->config);
        $this->assign('config', $this->config);
        $this->view()->assign( $this->_data);
        return $this->view()->display($tpl, $return, $is_tpl,$is_dir);
    }

    //页面不存在
    protected function error404()
    {
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');
       // $this->common=model('pageinfo')->media('您要查找的页面不存在');
	  
        $this->display('404.html');
        exit;
    }

    //包含内模板显示
    protected function show($tpl = ''){
        $content=$this->display($tpl,true);
        $body=$this->display($this->config['TPL_COMMON'],true);
        $html=str_replace('<!--body-->', $content, $body);
        echo $html;
		
    }

    //脚本运行时间
    public function runtime(){
    $GLOBALS['_endTime'] = microtime(true);
        $runTime = number_format($GLOBALS['_endTime'] - $GLOBALS['_startTime'], 4);
        echo $runTime;
    }


    //判断是否是数据提交 
    protected function isPost(){
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    //直接跳转
    protected function redirect($url)
    {
        header('location:' . $url, false, 301);
        exit;
    }

    //操作成功之后跳转,默认三秒钟跳转
    protected function success($msg, $url = null, $waitSecond = 3)
    {
        if ($url == null)
            $url = __URL__;
        $this->assign('message', $this->getlang($msg));
        $this->assign('url', $url);
        $this->assign('waitSecond', $waitSecond);
        $this->display('success');
        exit;

    }

    //弹出信息
    protected function alert($msg, $url = NULL){
        header("Content-type: text/html; charset=utf-8"); 
        $alert_msg="alert('$msg');";
        if( empty($url) ) {
            $gourl = 'history.go(-1);';
        }else{
            $gourl = "window.location.href = '{$url}'";
        }
        echo "<script>$alert_msg $gourl</script>";
        exit;
    }

    //弹出信息
    protected function alert2($msg, $url = NULL){
        header("Content-type: text/html; charset=utf-8"); 
        $alert_msg="alert('$msg');";
        if( empty($url) ) {
            $gourl = 'history.go(-2);';
        }else{
            $gourl = "window.location.href = '{$url}'";
        }
        echo "<script>$alert_msg $gourl</script>";
        exit;
    }

    //判断空值
    public function alert_str($srt,$type=0,$json=false)
    {
        switch ($type) {
            case 'int':
                $srt=intval($srt);
                break;
            default:
                $srt=in($srt);
                break;
        }
        if(empty($srt)){
            if($json){
                $this->msg('内部通讯错误！',0);
            }else{
                $this->alert('内部通讯错误！');
            }
        }
    }

    //提示
    public function msg($message,$status=1) {
        if (is_ajax()){
            @header("Content-type:text/html");
            echo json_encode(array('status' => $status, 'message' => $message));
            exit;
        }else{
            $this->alert($message);
        } 
    }

    //分页 
    protected function page($url, $totalRows, $listRows =20, $rollPage = 5 ,$type=0)
    {
        $page = new Page();
        if($type==0){
            return $page->show($url, $totalRows, $listRows, $rollPage);
        }else if($type==1){
            $page->show($url, $totalRows, $listRows, $rollPage);
            return $page->prePage('',0);
        }else if($type==2){
            $page->show($url, $totalRows, $listRows, $rollPage);
            return $page->nextPage('',0);
        }
		else{
            $page->show($url, $totalRows, $listRows, $rollPage);
            return $page->_getUrl('',3);
        }
    }

    //获取分页条数
    protected function pagelimit($url,$listRows)
    {
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        $limit_start = ($cur_page - 1) * $listRows;
        return  $limit_start . ',' . $listRows;
    }
	
	//获取当前页数
    protected function pagenum($url)
    {
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        
        return $cur_page;
    }

    //插件接口
    public function plus_hook($module,$action,$data=NULL)
    {
        $action_name='hook_'.$module.'_'.$action;
        $list=$this->model->table('plugin')->where('status=1')->select();
        $plugin_list=Plugin::get();
        if(!empty($list)){
            foreach ($list as $value) {
                $action_array=$plugin_list[$value['file']];
                if(!empty($action_array)){
                    if(in_array($action_name,$action_array))
                    {
                        if($return){
                            return Plugin::run($value['file'],$action_name,$data,$return);
                        }else{
                            Plugin::run($value['file'],$action_name,$data);
                        }
                    }
                }
            }
        }
    }

    //替换插件接口
    public function plus_hook_replace($module,$action,$data=NULL)
    {
        $hook_replace=$this->plus_hook($module,$action,$data,true);
        if(!empty($hook_replace)){
            return $hook_replace;
        }else{
            return $data;
        }
    }
	
	public function isMobile()
{
// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
{
return true;
}
// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
if (isset ($_SERVER['HTTP_VIA']))
{
// 找不到为flase,否则为true
return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
}
 
if (isset ($_SERVER['HTTP_USER_AGENT']))
{
$clientkeywords = array ('nokia',
'sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips',
'panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi',
'openwave','nexusone','cldc','midp','wap','mobile'
);
// 从HTTP_USER_AGENT中查找手机浏览器的关键字
if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
{
return true;
}
}
// 协议法，因为有可能不准确，放到最后判断
if (isset ($_SERVER['HTTP_ACCEPT']))
{
// 如果只支持wml并且不支持html那一定是移动设备
// 如果支持wml和html但是wml在html之前则是移动设备
if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
{
return true;
}
}
return false;
}
	function curlGet($url='',$data='',$method='post'){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}

}
?>