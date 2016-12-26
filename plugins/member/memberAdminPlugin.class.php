<?php

class memberAdminPlugin extends common_pluginMod
{
    public function __construct()
    {
        $_GET['_module']='member';
		
        parent::__construct();
    }
    
    //插件附加表信息
    public function plugin_ini_data()
    {
        return array(
            'user',
            'user_append',
            'user_collection',
            'user_config',
            'user_field',
            'user_friends',
            'user_group',
            'user_menu',
            'user_message',
            'user_message_relation',
            'user_power',
            'user_verify',
            'user_verify_forget',
            'user_verify_reg',
        );
    }


    //插件卸载
    public function uninstall()
    {
        $dir=array(
            'admin/template/user_center/',
            'admin/template/user_center_group/',
            'admin/template/user_center_model/',
            'admin/template/user_center_user/',
            'user',
            
            );
        $file=array(
            'admin/module/user_center_groupMod.class.php',
            'admin/module/user_center_modelMod.class.php',
            'admin/module/user_center_userMod.class.php',
            'admin/module/user_centerMod.class.php',

            'admin/model/user_center_collectionModel.class.php',
            'admin/model/user_center_friendsModel.class.php',
            'admin/model/user_center_groupModel.class.php',
            'admin/model/user_center_messageModel.class.php',
            'admin/model/user_center_modelModel.class.php',
            'admin/model/user_center_userModel.class.php',
            'admin/model/user_centerModel.class.php',
            );
        foreach ($dir as $value) {
            @del_dir(__ROOTDIR__.'/'.$value);
        }
        foreach ($file as $value) {
            @unlink(__ROOTDIR__.'/'.$value);
        }

    }

    //插件安装
    public function install()
    {
        $dir=__PLUGINDIR__.'/file/';
        @copy_dir($dir,__ROOTDIR__);
    }

    //菜单接口
    public function hook_index_nav_tpl(){
        echo '<li><a href="'.__APP__.'/member/menu">会员</a></li>';
    }

    

    //菜单显示
    public function menu(){
        $this->display('menu.html');
    }

    //首页
    public function index()
    {
        echo '请通过顶部菜单进入管理';
    }


}