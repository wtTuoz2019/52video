<?php
class systemModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取配置文件
    public function config(){
        $list=$this->model->table('user_config')->select();

        if(!empty($list)){
            foreach ($list as $value) {
                $config[$value['name']]=$value['value'];
            }
        }
        return $config;
    }

    //发送邮件
    public function email($email,$title,$content){
        @ignore_user_abort(true); 
        @set_time_limit(0);
        $user_config=$this->config();
        $config['SMTP_HOST']=$user_config['smtp_host'];
        $config['SMTP_PORT']=intval($user_config['smtp_port']);
        $config['SMTP_SSL']=intval($user_config['smtp_ssl']);
        $config['SMTP_USERNAME']=$user_config['smtp_username'];
        $config['SMTP_PASSWORD']=$user_config['smtp_password'];
        $config['SMTP_FROM_TO']=$user_config['smtp_username'];
        $config['SMTP_FROM_NAME']=$user_config['smtp_form_name'];
        $config['SMTP_CHARSET']='utf-8';
        ob_start();
        Email::init($config);
        Email::send($email,$title,$content);
        $msg=ob_get_contents();
        ob_end_clean();

        if(!empty($msg)){
            return $msg;
        }else{
            return true;
        }
    }

    //URL加解密
    public function str_encode($str){
        $str=cp_encode($str,$this->config['KEY']);
        $str=str_replace('\\','{1}', $str);
        $str=str_replace('/','{2}', $str);
        $str=str_replace('+','{3}', $str);
        return urlencode($str);
    }

    public function str_decode($str){
        $str=urldecode($str);
        $str=str_replace('{1}','\\', $str);
        $str=str_replace('{2}','/', $str);
        $str=str_replace('{3}','+', $str);
        return cp_decode($str,$this->config['KEY']);
    }

}

?>