<?php
//公共类
class commonModel
{
    protected $model = NULL; //数据库模型
    protected $config = array();
    private $_data = array();

    protected function init(){}
    
    public function __construct(){
        global $config;
        session_start();
        $config['PLUGIN_PATH']=__ROOTDIR__.'/plugins/';
        $this->config = $config;
        $this->model = self::initModel( $this->config);
        $this->cache = self::initCache( $this->config);
        $this->init();
        Plugin::init('Admin',$config);
        $langCon=Lang::langCon();
        $this->config = array_merge((array)$config,(array)$langCon);
    }


    //初始化缓存
    static public function initCache($config){
        static $cache = NULL;
        if( empty($cache) ){
            $cache = new cpCache($config);
        }
        return $cache;
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

    //提示
    public function msg($message,$status=1) {
        echo json_encode(array('status' => $status, 'message' => $message));
        exit;
    }
	
	 public function load_js() {
        $js='';
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/js/jquery.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/js/duxui.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/dialog/jquery.artDialog.js?skin=default"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/dialog/plugins/iframeTools.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/kindeditor/kindeditor-min.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/kindeditor/lang/zh_CN.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/js/common.js"></script>' . PHP_EOL;
        return $js;
    }
    //CSSUI库
    public function load_css()
    {
        $css='';
        $css .= '<link href="' . __PUBLICURL__ . '/css/base.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
        $css .= '<link href="' . __PUBLICURL__ . '/css/style.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
        $css .= '<link href="' . __PUBLICURL__ . '/kindeditor/themes/default/default.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
        return $css;
    }
	 
	
public function config_save($name,$value,$expire){
		
		if($this->model->table('config')->where(array('name'=>$name))->find()){
			$this->model->table('config')->where(array('name'=>$name))->data(array('name'=>$name,'value'=>$value,'time'=>$expire+time()))->update();
			}else{
			$this->model->table('config')->data(array('name'=>$name,'value'=>$value,'time'=>$expire+time()))->insert();	
				}
		
		}	
	public function config_get($name){
		$data=$this->model->table('config')->where(array('name'=>$name,'time'=>array('>',time())))->find();
		if($data)return $data['value'];
		else return '';
		}
}