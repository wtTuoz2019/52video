<?php
//公共类
class commonMod
{
    protected $model = NULL; //数据库模型
    protected $layout = NULL; //布局视图
    protected $config = array();
    protected $user_config = array();
    protected $user_info = array();
    private $_data = array();

    protected function init(){}
    
    public function __construct(){
        global $config;
         $session_name = session_name();
        if(!isset($_COOKIE[$session_name]))
        {
            foreach($_COOKIE as $key=>$val)
            {
                $key = strtoupper($key);
                if(strpos($key,$session_name))
                {
                  session_id($_COOKIE[$key]);
                }
            }
        }
		
        @session_start();
        $config['PLUGIN_PATH']=__ROOTDIR__.'/plugins/';
        $this->config = $config;
        $this->model = self::initModel( $this->config);
        $this->init();
        $this->user_config=model('system')->config();
        $this->check_login();
        $this->user_info=model('user')->current();
		
        $this->site_status();
        $this->about = model('content')->about();
	
        Plugin::init('User',$config); 
    }


    
    //获取当前页数
    protected function pagenum($url)
    {
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        
        return $cur_page;
    }
    //站点关闭
    protected function site_status() {
        if(!intval($this->user_config['base_status'])){
            $this->error(html_out($this->user_config['base_off_reason']));
        }
    }

    //登录检测
    protected function check_login() {

        if($_GET['_module']=='editor_upload'||$_GET['_module']=='qqlogin'||$_GET['_module']=='weixinlogin'||$_GET['_module']=='sinalogin'||$_GET['_module']=='editor'||$_GET['_module']=='verification'||$_GET['_module']=='forget'||$_GET['_module']=='home'||$_GET['_module']=='comment'||($_GET['_module']=='information'&&$_GET['_action']=='set_data')){
			
            return true;
        }
        $userinfo=$_COOKIE[$this->config['SPOT'].'_duxuser'];
       
			
        $user=model('login')->check_login($userinfo);
	
		if($_GET['_module']=='login'||$_GET['_module']=='register'){
		
			 
				 return true; 
				
			
			}
		 //读取登录信息
        if(empty($userinfo)){
            $this->redirect(__APP__ . '/login');
        } 	
        if(!$user){
            $this->redirect(__APP__ . '/login');
        }
        return true;
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

    //模板显示
    protected function display($tpl = '', $return = false, $is_tpl = true ,$diy_tpl=false){
        if( $is_tpl ){
            $tpl = empty($tpl) ? $_GET['_module'] . '/'. $_GET['_action'] : $tpl;
            if( $is_tpl && $this->layout ){
                $this->__template_file = $tpl;
                $tpl = $this->layout;
            }
        }

        $this->assign("model", $this->model);
        $this->assign('sys', $this->config);
        $this->assign('config', $this->config);
        $this->assign('js', $this->load_js());
        $this->assign('css', $this->load_css());
        $this->assign('user_config', model('system')->config());
        $this->assign('user_info', $this->user_info);

        
        $this->view()->assign( $this->_data );
        return $this->view()->display($tpl, $return, $is_tpl,$diy_tpl);
    }

    //包含内模板显示
    protected function show($tpl = ''){
        $content=$this->display($tpl,true);
        $body=$this->display('index/common',true);
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

    //操作成功之后的提示
    protected function success($msg, $url = null)
    {
        if ($url == null)
            $url = 'javascript:history.go(-1);';
        $this->assign('msg', $msg);
        $this->assign('url', $url);
        $this->display('index/msg');
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
        @header("Content-type:text/html");
        echo json_encode(array('status' => $status, 'message' => $message));
        exit;
    }

    //错误提示
    public function error($meg,$title=null) {
        if(!$title){
            $title='非常抱歉，您的当前操作无法进行';
        }
        $this->title=$title;
        $this->msg=$meg;
        $this->show('index/error');
        exit;
    }

    public function error404() {
        $this->error('非常抱歉，您访问的页面不存在！','404页面不存在');
    }

    //JSUI库
    public function load_js() {
        $js='';
        $js .= '<script type=text/javascript > var app="'.__APP__.'" </script>'. PHP_EOL;;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__  . '/js/jquery.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/js/duxui.js"></script>' . PHP_EOL;
		$js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/js/common.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/dialog/jquery.artDialog.js?skin=default"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/dialog/plugins/iframeTools.js"></script>' . PHP_EOL;
		$js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/kindeditor/kindeditor-min.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/kindeditor/lang/zh_CN.js"></script>' . PHP_EOL;
        
        $js .= '<script type=text/javascript src="' . __PUBLIC__ . '/js/swfobject.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLIC__ . '/js/jquery.lhgcalendar.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLIC__ . '/js/jquery.duxnotice.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLIC__ . '/js/jquery.duxform.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLIC__ . '/js/jquery.common.js"></script>' . PHP_EOL;
        return $js;
    }
    //CSSUI库
    public function load_css()
    {
        $css='';
        $css .= '<link href="' . __PUBLIC__ . '/css/base.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
        $css .= '<link href="' . __PUBLIC__ . '/css/style.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
		$css .= '<link href="' . __PUBLICURL__ . '/kindeditor/themes/default/default.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
        return $css;
    }

    //分页 $url:基准网址，$totalRows: $listRows列表每页显示行数$rollPage 分页栏每页显示的页数
    protected function page($url, $totalRows, $listRows =20, $rollPage = 5)
    {
        $page = new Page();
        return $page->show($url, $totalRows, $listRows, $rollPage);
    }

    //获取分页条数
    protected function pagelimit($url,$listRows)
    {
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        $limit_start = ($cur_page - 1) * $listRows;
        return  $limit_start . ',' . $listRows;
    }

    //插件接口
    public function plus_hook($module,$action,$data=NULL,$return=false)
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

}