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
		
		//var_dump($this->user);
//        		$user=$this->user;
//		if(!$user)die;
//		if($user['gid']==6){
//				$temp;$temp[]=1;
//			if($user['cid']){
//				$temp[]=$user['cid'];
//				}
//			$nextuser=model('user')->admin_list('  pid='.$user['id']);
//			if($nextuser){
//			foreach($nextuser as $key=>$val){
//				$temp[]=$val['cid'];
//				}
//			} 
//			$whereaid=" csid  in (".implode(',',$temp).") ";
//			 }else{
//		if($user['cid'])	
//	 	$whereaid="csid =".$user['cid'];
//			 }
//			
//				if($whereaid){
//			$aids=model('data')->getaids($whereaid);	
//			$where;
//			if($aids){$where['aid']=array('in','('.implode(',',$aids).')');}
//			
//				$where['cid']='16';
//			$data['livetime']=model('data')->looktimesum($where);
//			
//			//var_dump($data);die;
//	}
//		
    	

	        $this->show();
    }
	
	public function flowdata(){
		
		$user=$this->user;
		if($user['gid']==6){
				$temp;$temp[]=1;
			if($user['cid']){
				$temp[]=$user['cid'];
				}
			$nextuser=model('user')->admin_list(' and  pid='.$user['id']);
			if($nextuser){
			foreach($nextuser as $key=>$val){
				$temp[]=$val['cid'];
				}
			} 
			$whereaid=" csid  in (".implode(',',$temp).") ";
			 }else{
		if($user['cid'])	
	 	$whereaid="csid =".$user['cid'];
			 }
			 
			
			if($whereaid)
			$aids=model('data')->getaids($whereaid);	
			
			$where;
			if($aids){$where['aid']=array('in','('.implode(',',$aids).')');

			
			}
			
			
			
	  $start=time()-3600;
	  $timestep=360;$videoflow=array();
	  $where=array('aid'=>$id);
		for($i=1;$i<=10;$i++){
			$starttime=$start+($i-1)*$timestep;
			$endtime=$start+($i)*$timestep;
			
			
			$where[1]='starttime<'.$endtime.' and endtime>'.$starttime;
		
			
			$livenum['date'][]=date('H:i',$endtime);
			if($where)
			$livenum['value'][]=intval(model('data')->getuidcount($where));
			else
			$livenum['value'][]=0;
			
			}	
		
		$this->msg($livenum,1);
			
		
		
		
		}
	public function gettop(){
		$user=$this->user;
		$uid=$user['id'];
		if($user['gid']==6){
			$temp;
			$temp[]=1;
			if($user['cid']){
				$temp[]=$user['id'];
				}
			$nextuser=model('user')->admin_list(' AND pid='.$user['id']);
			if($nextuser){
			foreach($nextuser as $key=>$val){
				$temp[]=$val['id'];
				}
			}
			
			if($temp){
			 	$where='B.id in ('.implode(',',$temp).') ';	
				}
			}else{
		
			
			if($user['cid']){
			$where='B.id='.$uid;	
				}
		
     
			}
		
		   $channel = model('device')->channel_list($where,10);
		
			if(is_array($channel)){
		foreach($channel as $k=>$v){
			
			$citydata[]=array($v['name'],intval($v['count']));
		
			
			}
		}
		
		$this->msg($citydata,1);
		
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