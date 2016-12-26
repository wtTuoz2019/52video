<?php
class langModel extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

    //获取当前语言
    public function langid() {

        if($this->config['LANG_OPEN']){
        $lang=__LANG__;
        }else{
            return 1;
        }
        $info=$this->model->table('lang')->where('lang="'.$lang.'"')->find();
        if(!empty($info)){
            return $info['id'];
        }else{
            return 1;
        }
    }
  //当前语言
    public function current_lang() {
        return $this->model->table('lang')->where('lang="'.__LANG__.'"')->find();
    }

    //语言列表
    public function lang_list() {
        return $this->model->table('lang')->order('id asc')->select();
    }


}