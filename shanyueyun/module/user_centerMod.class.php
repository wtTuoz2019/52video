<?php

class user_centerMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->config=model('user_center')->config();
        $this->show();
    }

    public function setting(){
        $this->config=model('user_center')->config();
        $this->show();
    }

    public function setting_data(){
        //模板内赋值
        model('user_center')->config_data($_POST);
        $this->msg('配置保存成功！');
    }

    public function test_email(){
        $array=model('user_center')->config();

        $config['SMTP_HOST']=$array['smtp_host'];
        $config['SMTP_PORT']=intval($array['smtp_port']);
        $config['SMTP_SSL']=intval($array['smtp_ssl']);
        $config['SMTP_USERNAME']=$array['smtp_username'];
        $config['SMTP_PASSWORD']=$array['smtp_password'];
        $config['SMTP_FROM_TO']=$array['smtp_username'];
        $config['SMTP_FROM_NAME']=$array['smtp_form_name'];
        $config['SMTP_CHARSET']='utf-8';
        ob_start();
        Email::init($config);
        Email::send($_POST['test_mail'],'DUXCMS会员中心测试邮件','欢迎使用DUXCMS会员中心，本邮件为测试邮件！');
        $msg=ob_get_contents();
        ob_end_clean();
        if(!empty($msg)){
            $this->msg('发送失败：'.$msg,0);
        }else{
            $this->msg('测试邮件已发送，请注意查收！');
        }
        
    }


}