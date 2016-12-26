<?php
class  collectModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function model_list($where=null) {
	
      $data= $this->model->table('collect')->where($where)->select();
		$temp=array();
		if($data){
		foreach($data as $k=>$v){
			$temp[$v['id']]=$v;
			}
		}
        return $temp;
    }
	
	

    //获取科室树形列表
    public function teacher_list($id = 0) {
       
        $data=$this->model_list(); 
        
        $cat = new Category(array('id', 'pid', 'name', 'cname'));
        return $cat->getTree($data, $id);
    }

    //获取模型信息
    public function table_info($table,$mid=null) {
        $where="`table`='".$table."'";
        if(!empty($mid)){
        $where.=' AND mid<>'.$mid;
        }
        return $this->model->table('teacher')->where($where)->find();
    }
    //获取模型信息
    public function info($id) {
        $where['id']=$id;

        return $this->model->table('collect')->where($where)->find();
    }
  

     //保存
    public function save($data) {
        
        return $this->model->table('collect')->data($data)->insert();
    }

     //保存
    public function edit_save($data) {
        
        return $this->model->table('collect')->data($data)->where('id='.$data['id'])->update();
    }




    //字段删除
    public function del($id)
    {
        $status=$this->model->table('collect')->where('id='.$id)->delete(); 
       
        return $status;
       
    }

    //数据采集
    public function collect($id)
    {
        $where['id']=$id;
        return $this->model->table('collect')->where($where)->find();
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

    //查询采集状态
    public function collect_query($oldaid, $source) {
        $data = $this->model->table('content')->where('oldaid='.$oldaid.' AND source='.$source)->find();
        if (!$data) {
            return 0;
        }else{
            return 1;
        }
    }

    public function channel_query($oldid, $source) {
        $data = $this->model->table('channel')->where('oldid='.$oldid.' AND source='.$source)->find();
        if (!$data) {
            return 0;
        }else{
            return 1;
        }
    }

    public function live_query($oldid, $source) {
        $data = $this->model->table('expand_content_livestream')->where('oldid='.$oldid.' AND source='.$source)->find();
        if (!$data) {
            return 0;
        }else{
            return 1;
        }
    }

    //content表数据添加
    public function collect_add($data)
    {
        $data['updatetime']=time();
        return $this->model->table('content')->data($data)->insert();
    }
    //content_data表数据添加
    public function collect_data_add($data, $id, $aid)
    {
        $arr['content'] = $data['content'];
        $arr['source'] = $id;
        $arr['aid'] = $aid;
        $arr['oldaid'] = $data['oldaid'];
        return $this->model->table('content_data')->data($arr)->insert();
    }

    //content表数据修改
    public function collect_edit($data) {
        return $this->model->table('content')->data($data)->where('oldaid='.$data['oldaid'].' AND source='.$data['source'])->update();
    }
    //content_data表数据修改
    public function collect_data_edit($data) {
        $arr['content'] = $data['content'];
        return $this->model->table('content_data')->data($data)->where('oldaid='.$data['oldaid'].' AND source='.$data['source'])->update();
    }

    //channel表数据添加
    public function channel_add($data)
    {
        return $this->model->table('channel')->data($data)->insert();
    }

    //channel表数据修改
    public function channel_edit($data) {
        return $this->model->table('channel')->data($data)->where('oldid='.$data['oldid'].' AND source='.$data['source'])->update();
    }

    //expand_content_livestream表数据添加
    public function live_add($data)
    {
        return $this->model->table('expand_content_livestream')->data($data)->insert();
    }

    //expand_content_livestream表数据修改
    public function live_edit($data) {
        return $this->model->table('expand_content_livestream')->data($data)->where('oldid='.$data['oldid'].' AND source='.$data['source'])->update();
    }

    //更新collect采集时间
    public function collect_update($id) {
        $data['updatetime'] = time();
        return $this->model->table('collect')->data($data)->where('id='.$id)->update();
    }



    
 
    
}

?>