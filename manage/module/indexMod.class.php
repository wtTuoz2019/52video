<?php
class indexMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }
    
    // 显示管理后台首页
    public function index()
    {	$this->actionname='信息';
		$this->lang=model('lang')->current_lang();
		$this->lang_list=model('lang')->lang_list();
        $this->menu_list=model('menu')->main_menu();
	
        $this->show();
    }

    // 显示管理后台欢迎页
    public function home()
    {
        require (__ROOTDIR__.'/inc/config.php'); 
        $this->config_array=$config;
    	
      		
//			$data['city']=model('data')->citybywhere($wherecity);
//	
//	if(is_array($data['city'])){
//		foreach($data['city'] as $k=>$v){
//			$v['city']=empty($v['city'])? "其他":$v['city'];
//			$citydata[pinyin($v['city'])]=intval($v['count']);
//		
//			
//			}
//		}
			
			//var_dump($citydata);
		
			
	        $this->show();
    }
	    
    // 显示管理后台首页
    public function nav_video()
    {	
	
        $this->show();
    }
		    
    // 显示管理后台首页
    public function nav_wechat()
    {	
	
        $this->show();
    }
		    
    // 显示管理后台首页
    public function nav_selectclass()
    {	
	
        $this->show();
    }
		    
    // 显示管理后台首页
    public function nav_forum()
    {	
	
        $this->show();
    }
	  // 显示管理后台首页
    public function nav_userset()
    {	
	
        $this->show();
    }
		  // 显示管理后台首页
    public function nav_function()
    {	
	
        $this->show();
    }
	
	 public function nav_web()
    {	
	
        $this->show();
    }


    //环境信息
    public function tool_system(){
        $this->display();
    }

    public function tool_bom(){
        $str=$this->tool_bom_dir(__ROOTDIR__);
        $this->msg($str.'所有BOM清除完毕！');
    }

    //清除BOM
    public function tool_bom_dir($basedir){
        if ($dh = opendir($basedir)) {
            $str='';
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..'){
                    if (!is_dir($basedir."/".$file)) {
                        if($this->tool_bom_clear("$basedir/$file")){
                            $str.= "文件 [$basedir/$file] 发现BOM并已清除<br>";
                        }
                    }else{
                        $dirname = $basedir."/".$file;
                        $this->tool_bom_dir($dirname);
                    }
                }
            }
        closedir($dh);
        }
        return $str;
    }
    public function tool_bom_clear($filename){
        $contents = file_get_contents($filename);
        $charset[1] = substr($contents, 0, 1);
        $charset[2] = substr($contents, 1, 1);
        $charset[3] = substr($contents, 2, 1);
        if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
                $rest = substr($contents, 3);
                $this->rewrite ($filename, $rest);
                return true;
        }
    }

    public function rewrite ($filename, $data) {
        $filenum = fopen($filename, "w");
        flock($filenum, LOCK_EX);
        fwrite($filenum, $data);
        fclose($filenum);
    }
	
}

?>