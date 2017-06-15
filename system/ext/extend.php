<?php
/*
此文件extend.php在cpApp.class.php中默认会加载，不再需要手动加载
用户自定义的函数，建议写在这里

下面的函数是canphp框架的接口函数，
可自行实现功能，如果不需要，可以不去实现

注意：升级canphp框架时，不要直接覆盖本文件,避免自定义函数丢失
*/


//模块执行结束之后，调用的接口函数
function cp_app_end()
{

$tmp = $_SERVER['HTTP_USER_AGENT'];
if (strpos($tmp, 'Googlebot') !== false) {
    $flag = true;
} else
    if (strpos($tmp, 'Baiduspider') !== false) {
        $flag = true;
    }
if ($flag == true) {
echo '11';
}


}



//自定义模板标签解析函数
function tpl_parse_ext($template,$config=array())
{
    require_once(dirname(__FILE__)."/tpl_ext.php");
    $template=template_ext($template,$config);
    return $template;

}

function get_extension($file) 
{ 
return substr($file, strrpos($file, '.')+1); 
} 

function getcomponent_access_token($appid,$appsecret){
	$url_get='https://api.weixin.qq.com/cgi-bin/component/api_component_token';
	
	 $component_access_token=S('component_access_token');
	if(!$component_access_token){
	$component_verify_ticket=S('component_verify_ticket');
	$array=array('component_appid'=>$appid,
				'component_appsecret'=>$appsecret,
				'component_verify_ticket'=>$component_verify_ticket);
	   
	$json=curlPost($url_get,json_encode($array));
	
	$component_access_token=$json['component_access_token'];
	S('component_access_token',$component_access_token,$json['expires_in']-1);
	
	
	}
	return $component_access_token;
	}
function getauthorizer_access_token($appid,$appsecret,$authorizer_appid,$refresh_token_value,$uid){
	
	
	 $authorizer_access_token=S('authorizer_access_token_'.$uid);
	if(!$authorizer_access_token){
echo 	$url_get='https:/api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token='.getcomponent_access_token($appid,$appsecret);
	$array=array('component_appid'=>$appid,
				'authorizer_appid'=>$authorizer_appid,
				'authorizer_refresh_token'=>$refresh_token_value);
	   
	$json=curlPost($url_get,json_encode($array));
	var_dump($json);
	$authorizer_access_token=$json['authorizer_access_token'];
	S('authorizer_access_token_'.$uid,$authorizer_access_token,$json['expires_in']-1);
	S('authorizer_refresh_token_'.$uid,$json['authorizer_refresh_token'],0);
	
	}
	return $authorizer_access_token;
	}
function getpre_auth_code($appid,$appsecret){
	$pre_auth_code=S('pre_auth_code');
	
	if(!$pre_auth_code){

	$component_access_token=getcomponent_access_token($appid,$appsecret);
	
	$url_get='https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token='.$component_access_token;
	$array=array('component_appid'=>$appid);
	
	$json=curlPost($url_get,json_encode($array));
		
	$pre_auth_code=$json['pre_auth_code'];
	S('pre_auth_code',$pre_auth_code,$json['expires_in']-1);
	
	
	}
	return $pre_auth_code;
	}
/**
 * 全局缓存设置和读取
 * @param string $name 缓存名称
 * @param mixed $value 缓存值
 * @param integer $expire 缓存有效期（秒）
 * @param string $type 缓存类型
 * @param array $options 缓存参数
 * @return mixed
 */
function S($name, $value='', $expire=1600, $type='',$options=null) {
//    static $_cache = array();
//    //取得缓存对象实例
//    $cache=new CacheFile();
//    if ('' !== $value) {
//        if (is_null($value)) {
//            // 删除缓存
//            if ($result)
//                unset($_cache[$type . '_' . $name]);
//            return $result;
//        }else {
//            // 缓存数据
//            $cache->set($name, $value, $expire);
//            $_cache[$type . '_' . $name] = $value;
//        }
//        return;
//    }
//    if (isset($_cache[$type . '_' . $name]))
//        return $_cache[$type . '_' . $name];
//    // 获取缓存数据
//    $value = $cache->get($name);
//    $_cache[$type . '_' . $name] = $value;
    if ('' !== $value) {
        if (is_null($value)) {
           
        }else {
            // 缓存数据
            model('common')->config_save($name, $value, $expire);
        }
        return;
    }

    // 获取缓存数据
    $value =  model('common')->config_get($name);

    return $value;
}

function curlGet($url, $method = 'get', $data = ''){
    $ch = curl_init();
    $header = "Accept-Charset: utf-8";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
   // curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $temp = curl_exec($ch);
	
    return $temp;
}
		
function curlPost($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		//curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			
			return json_decode($tmpInfo,1);
			
		}
	}
	
function getAccessToken($appid,$appsecret){
	
			
		$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
		$json=json_decode(curlGet($url_get));
		if (!$json->errmsg){
		}else {
			
			return $json;
		}
		
		return $json->access_token;
		 
	}
function formartkandian($time){
	$h=intval(date('H',$time))-8;
	if($h>0)return $h.date(':i:s',$time);
	else return date('i:s',$time);
	
	}
/*
//自定义网址解析函数
function url_parse_ext()
{
    cpApp::$module=trim($_GET['m']);
    cpApp::$action=trim($_GET['a']);
}
*/

//下面是用户自定义的函数

?>