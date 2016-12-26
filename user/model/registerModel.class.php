<?php
class registerModel extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

    //验证用户信息
    public function verify_str($action,$data){
    	$config=model('system')->config();
    	switch ($action) {
    		case 'username':
    			if(!Check::userName($data,$config['reg_username_length_min'],$config['reg_username_length_max'],'/^(?!_)(?!.*?_$)[\w]/u')||empty($data)){
					$this->msg('用户名只能为字母数字下划线组成且支持'.$config['reg_username_length_min'].'~'.$config['reg_username_length_max'].'位',0);
                    echo Check::userName($data,$config['reg_username_length_min'],$config['reg_username_length_max'],'/^(?!_)(?!.*?_$)[\w]/u')||empty($data);
		            return;
				}
    			break;
            case 'nicename':
                if(!Check::userName($data,$config['reg_username_length_min'],$config['reg_username_length_max'],'/^(?!_)(?!.*?_$)[\x{4e00}-\x{9fa5}\w\-]/u')||empty($data)){
                    $this->msg('昵称只能为中英文数字与下划线且支持'.$config['reg_username_length_min'].'~'.$config['reg_username_length_max'].'位',0);
                    return;
                }
                break;
    		case 'password':
    			if(!Check::userName($data,$config['reg_password_length_min'],$config['reg_password_length_max'],'/\S/u')||empty($data)){
					$this->msg('密码不能为空且支持'.$config['reg_password_length_min'].'~'.$config['reg_password_length_max'].'位',0);
		            return;
				}
    			break;
    		case 'email':
    			if(!Check::email($data)||empty($data)){
		            $this->msg('邮箱地址不正确！',0);
		            return;
		        }
    			break;
    	}
    }

    //注册验证
    public function reg_verify($uid){
        //获取用户信息
        $info=model('user')->info($uid);
        if(empty($info)){
            return false;
        }
        switch ($info['verify_type']) {
            case 0:
                $data['status']=1;
                break;
            case 1:
                //邮箱验证
                $data['status']=0;
                $this->email($info);
                break;
            case 2:
                //手动验证
                $data['status']=0;
                break;
        }
        $condition['uid']=$uid;
        $this->model->table('user')->data($data)->where($condition)->update();

    }


    //发送注册邮件验证
    public function email($info){
        //删除重复验证
        model('verification')->del_verify('forget',$info['uid']);
        
        //生成验证码
        $code=model('verification')->code(10);

        //录入验证数据
        $uid=intval($info['uid']);
        $data['vid']=$code['vid'];
        $data['uid']=$uid;
        model('verification')->add_verify('reg',$data);
        //发送邮件
        $url='http://'.$_SERVER["HTTP_HOST"].__APP__.'/register/verify/uid-'.$uid.'-code-'.$code['code'].'.html';
        $title=in($this->user_config['reg_activation_title']);
        $title=str_replace('{sitename}', $this->user_config['base_name'], $title);
        $content=html_out($this->user_config['reg_activation_content']);
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

    
    //添加用户
    public function add($data){
        $data['salt']=substr(cp_uniqid(),0,5);
        $data['password']=md5($data['password'].$data['salt']);
        $data['reg_time']=time();
        $data['reg_ip']=get_client_ip();
        $data['gid']=1;
        $uid=$this->model->table('user')->data($data)->insert();
        model('user_model')->save_append($data,$uid);
        return $uid;
    	
    }

    //修改激活状态
    public function audit($uid){
        $condition['uid']=$uid;
        $data['status']=1;
        $this->model->table('user')->data($data)->where($condition)->update();
        model('verification')->del_verify('reg',$uid);
    }


}