<?php
//tag显示
class tagsModel extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }


    public function tag_index_list($limit) {
        $lang=model('lang')->langid();
        return $this->model->table('tags')->where('lang='.$lang)->order('id desc')->limit($limit)->select();
    }

    public function tag_index_count() {
        $lang=model('lang')->langid();
        return $this->model->table('tags')->where('lang='.$lang)->count();
    }

    public function tag_info($tag) {
        $condition['name']=$tag;
        return $this->model->table('tags')->where($condition)->find();        
    }

    public function tags_relation_aid($aid) {
        $condition['aid']=$aid;
        return $this->model->table('tags_relation')->where($condition)->select();         
    }

    public function tags_relation_tid($tid) {
        return $this->model->table('tags_relation')->where('tid in('.$tid.')')->select();       
    }

	public function tag_list($tid,$limit) {
        if(empty($tid)){
        return;
        }
        $lang=model('lang')->langid();
        return $this->model
        ->field('A.*,B.name as cname,B.subname as csubname,B.mid')
        ->table('content','A')
        ->add_table('category','B','A.cid=B.cid')
        ->add_table('tags_relation','C','A.aid=C.aid')
        ->where('C.tid='.$tid.' AND B.lang='.$lang)
        ->order('A.updatetime desc')
        ->limit($limit)
        ->select();
	}

    public function tag_count($tid) {
        if(empty($tid)){
        return;
        }
        $lang=model('lang')->langid();
        return $this->model
        ->table('content','A')
        ->add_table('category','B','A.cid=B.cid')
        ->add_table('tags_relation','C','A.aid=C.aid')
        ->where('C.tid='.$tid.' AND B.lang='.$lang)
        ->count();
    }

    //访问计数
    public function views_content($id,$views){
        $data['click'] = $views + 1;
        $condition['id'] = $id;
        $this->model->table('tags')->data($data)->where($condition)->update();
    }
	
  //添加tag
    public function content_save($keywords,$aid)
    {
        if(empty($keywords)){
            return false;
        }
        $str = $keywords;
        $str = str_replace('，', ',', $str);
        $str = str_replace(' ', ',', $str);
        $strArray = explode(",", $str);
        $this->model->table('tags_relation')->where('aid='.$aid)->delete();
        foreach ($strArray as $list)
        {
            if(!empty($list)){
            $condition['name']=$list;
            $info=$this->model->table('tags')->where($condition)->find();
            if (empty($info))
            {
                $lang=model('lang')->current_lang();
                //添加tag
                $data2 = array();
                $data2['name'] = $list;
                $data2['aid'] = $aid;
                $data2['aid'] = $aid;
                $data2['lang']=$lang['id'];
                $tid=$this->model->table('tags')->data($data2)->insert();
                $data_relation['aid']=$aid;
                $data_relation['tid']=$tid;
                $this->model->table('tags_relation')->data($data_relation)->insert();
            }
            else
            {
                $condition2['aid']=$aid;
                $condition2['tid']=$info['id'];
                $info_relation=$this->model->table('tags_relation')->where($condition2)->find();

                if(empty($info_relation)){
                    $data_relation['aid']=$aid;
                    $data_relation['tid']=$info['id'];
                    $this->model->table('tags_relation')->data($data_relation)->insert();
                }
            }

            }

        }
        return true;
    }

    //删除tag
    public function del_content($aid)
    {

        $list=$this->model->table('tags_relation')->where('aid='.$aid)->select();
        if(empty($list)){
            return;
        }
        //删除该内容的TAG关系
        $this->model->table('tags_relation')->where('aid='.$aid)->delete();
        //查找其他TAG关系
        foreach ($list as $value) {
            $info=$this->model->table('tags_relation')->where('tid='.$value['tid'])->find();
            if(empty($info)){
                $this->model->table('tags')->where('id='.$value['tid'])->delete();
            }

        }
        
    }
}