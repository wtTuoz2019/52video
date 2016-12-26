<?php
class  user_center_modelModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function field_list_data()
    {
        return $this->model->table('user_field')->order('sequence asc,id asc')->select();
    }

    //字段列表
    public function field_list()
    {
        $list=$this->field_list_data();
        if(empty($list)){
            return;
        }
        foreach ($list as $key=>$vo) {
            $data[$key]=$vo;
            $data[$key]['type_name']=model('expand_model')->field_type($vo['type'],true);
        }
        return $data;

    }

    //字段信息
    public function field_info($id){
        $condition['id']=$id;
        return $this->model->table('user_field')->where($condition)->find();
    }

    //检测字段重复
    public function field_check($mid,$field,$id=null){
        if(!empty($id)){
            $condition=' AND id<>'.$id;
        }
        return $this->model->table('user_field')->where('field="'.$field.'"'.$condition)->count();
    }

    //字段添加
    public function add($data)
    {
        $property=model('expand_model')->field_property($data['property']);
        $data['verification']=cp_encode($data['verification']);
        $data=model('expand_model')->field_data($data);
        //添加真实字段
        $sql="
        ALTER TABLE {$this->model->pre}user_append ADD {$data['field']} {$property['name']}({$data['len']}{$data['decimal_len']}) DEFAULT NULL
        ";
        $this->model->query($sql);
        return $this->model->table('user_field')->data($data)->insert();
    }

    //字段修改
    public function field_edit($data)
    {
        $info=$this->field_info($data['id']);
        $property=model('expand_model')->field_property($data['property']);
        $data['verification']=cp_encode($data['verification']);
        $data=model('expand_model')->field_data($data);
        //修改真实字段
        $sql="
        ALTER TABLE {$this->model->pre}user_append CHANGE {$info['field']} {$data['field']} {$property['name']}({$data['len']}{$data['decimal_len']})
        ";
        $this->model->query($sql);
        $condition['id']=intval($data['id']);
        return $this->model->table('user_field')->data($data)->where($condition)->update(); 
    }

    //字段删除
    public function field_del($data)
    {
        $info=$this->field_info($data['id']);
        $sql="ALTER TABLE {$this->model->pre}user_append DROP {$info['field']}";
        $this->model->query($sql);
        $condition['id']=intval($info['id']);
        return $this->model->table('user_field')->where($condition)->delete(); 
    }

    //获取字段类型名称
    public function field_type($id=null,$name=false)
    {
        $list=array(
            1=> array(
                'name'=>'文本框'
                ),
            2=> array(
                'name'=>'多行文本'
                ),
            6=> array(
                'name'=>'下拉菜单'
                ),
            7=> array(
                'name'=>'日期和时间'
                ),
            8=> array(
                'name'=>'单选'
                ),
            9=> array(
                'name'=>'多选'
                ),
        );
        if(!empty($id)){
            if($name){
                return $list[$id]['name'];
            }else{
                return $list[$id];
            }
        }else{
            return $list;
        }
    }

}

?>