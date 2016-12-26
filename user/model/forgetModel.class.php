<?php
class forgetModel extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }


    //发送注册邮件验证
    public function email($info){
        //删除重复验证
        model('verification')->del_verify('forget',$info['uid']);
        
        //生成验证码
        $code=model('verification')->code(10,2);

        //录入验证数据
        $uid=intval($info['uid']);
        $data['vid']=$code['vid'];
        $data['uid']=$uid;
        model('verification')->add_verify('forget',$data);
        //发送邮件
        $url='http://'.$_SERVER["HTTP_HOST"].__APP__.'/forget/verify/uid-'.$uid.'-code-'.$code['code'].'.html';
        $title=in($this->user_config['login_forget_title']);
        $title=str_replace('{sitename}', $this->user_config['base_name'], $title);
        $title=str_replace('{username}', $info['username'], $title);
        $content=html_out($this->user_config['login_forget_content']);
        $content=str_replace('{username}', $info['username'], $content);
        $content=str_replace('{sitename}', $this->user_config['base_name'], $content);
        $content=str_replace('{time}', date('Y年m月d日 H点i分s秒'), $content);
        $content=str_replace('{url}', '<a href="'.$url.'">'.$url.'</a>', $content);
        $mail_array=array();
        $mail_array['email']=$info['email'];
        $mail_array['title']=$title;
        $mail_array['content']=$content;
        $mail_array['key']=$this->config['KEY'];
        Http::doPost('http://'.$_SERVER["HTTP_HOST"].__APP__.'/verification/mail.html',$mail_array,1);

    }

    //修改密码
    public function edit($data){
        $condition['uid']=$data['uid'];
        $this->model->table('user')->data($data)->where($condition)->update();
    }

    
    


}