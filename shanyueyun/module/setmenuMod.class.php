<?php
//自定义菜单管理
class setmenuMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	 //菜单列表
    public function index()
    {
        $this->list=model('menu')->menu_lists();
		
        $this->show();
    }
    //菜单添加
    public function add()
    {
        $this->action_name='添加';
        $this->action='add';
        $this->menu_list=model('menu')->menu_list();

        $this->display('setmenu/info');
    }
  //菜单保存
    public function add_save()
    {
        

        model('menu')->save($_POST);
       
        /*hook end*/
        $this->msg('菜单添加成功！',1);
    }

     //菜单添加
    public function edit()
    {    $id=intval($_GET['id']);
        $this->action_name='编辑';
        $this->action='edit';
        $this->menu_list=model('menu')->menu_list();
        $this->info=model('menu')->info($id);

        $this->display('setmenu/info');
    }
  //菜单保存
    public function edit_save()
    {
        

        model('menu')->edit_save($_POST);
       
        /*hook end*/
        $this->msg('菜单编辑成功！',1);
    }
	
	public function send(){
		
			
			 $menu=model('menu')->menu_layer();
			$cmenu='{"button":[';
			foreach($menu as $k=>$v){
				if($v['child']){
					$cmenu.='{
           						"name":"'.$v['name'].'",
          						 "sub_button":[';
								 
						foreach($v['child'] as $key=>$value){
							$cmenu.='{
             				  "type":"'.$value['type'].'",
             				  "name":"'.$value['name'].'",
               				  "'.$this->getWeiXinKeyName($value['type']).'":"'.$value['key'].'"
           							 }';
									 
							if($key<count($v['child'])-1){
    							$cmenu.=',';
    							}
							}
						$cmenu.=']},';
					
					}else{
						
					$cmenu.='{	
        			  	"type":"'.$v['type'].'",
          				"name":"'.$v['name'].'",
         				 "'.$this->getWeiXinKeyName($v['type']).'":"'.$v['key'].'"
    					 },';
						
						}
				
				}
				
			$cmenu.=']}';
			
			
			$url_result=json_decode($this->vpost('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->config['appid'].'&secret='.$this->config["appSecret"]));
			
   			 $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$url_result->access_token;
			
			$result=json_decode($this->vpost($url,$cmenu));
			
			  if($result->errmsg=='ok'){
    			$this->msg('操作成功！',1);
    			}else{
   			
    			$this->msg('操作失败！',1);
    			}
		
		
		}

	//菜单删除
    public function del()
    {
        $this->alert_str($_POST['id'],'int',true);
       
        $class_status=model('menu')->del($_POST['id']);
        
        $this->msg('页面删除成功！',1);
    }
	
	/**
 * 获取微信菜单的类型值名称
 * @param [type] $type [要处理的类型]
 * @return [type]  $name
 * */

public function getWeiXinKeyName($type){
		
		$keyname=$type=='click'?'key':'url';
		return $keyname;
}

    //提交信息到微信
public function  vpost($url,$data=null){ // 模拟提交数据函数
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_URL, $url); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); // 模拟用户使用的浏览器
 
    curl_setopt($curl, CURLOPT_POST, 1); 
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
    curl_setopt($curl, CURLOPT_HEADER, 0); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    $tmpInfo = curl_exec($curl); 
    if (curl_errno($curl)) {
       echo 'Errno'.curl_error($curl);
    }
    curl_close($curl); 
    return $tmpInfo; 
}

}