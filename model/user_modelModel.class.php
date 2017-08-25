<?php
class user_modelModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取字段列表
    public function field_list($where=null)
    {
        return $this->model->table('user_field')->where($where)->order('sequence asc,id asc')->select();
    }
    //获取字段内容
    public function field_info($uid,$table)
    {
        return $this->model->table('user_model_'.$table)->where('uid='.$uid)->find();
    }

    //判断必填显示
    public function get_field_must($must=0,$content=''){
        if($must){
            return $content;
        }
    }

    //获取模型样式
    public function get_field_html($info,$data=null){
        $info['default']=html_out($info['default']);

        if(!empty($data)){
            $info['default']=$data;
        }

        $html='';
        switch ($info['type']) {
            case '1':
                $html.='
                <li><label>'.$this->get_field_must($info['must'],'<span class="red">*</span> ').$info['name'].'：</label> <input class="text" name="'.$info['field'].'" id="'.$info['field'].'"  value="'.$info['default'].'" type="text" '.$this->get_field_must($info['must'],'reg="'.stripslashes(cp_decode($info['verification'])).'" msg="'.$info['verification_tip'].'"').'  > </li>
                ';
                break;
            case '2':
                $html.='
                <li><label>'.$this->get_field_must($info['must'],'<span class="red">*</span> ').$info['name'].'：</label> <textarea name="'.$info['field'].'"  id="'.$info['field'].'" class="textarea" '.$this->get_field_must($info['must'],'reg="'.stripslashes(cp_decode($info['verification'])).'" msg="'.$info['verification_tip'].'"').' >'.$info['default'].'</textarea> </li>
                ';
                break;
            case '6':
                $html.='
                <li><label>'.$this->get_field_must($info['must'],'<span class="red">*</span> ').$info['name'].'：</label> 
                ';
                $select_list='<select name="'.$info['field'].'" id="'.$info['field'].'" class="select" '.$this->get_field_must($info['must'],'reg="'.stripslashes(cp_decode($info['verification'])).'" msg="'.$info['verification_tip'].'"').' >';
                $list=explode("\n",html_out($info['config']));
                foreach ($list as $key) {
                    $value=explode('|',$key);
                    $select_list.='<option ';
                    if($info['default']==$value[1]){
                        $select_list.='selected="selected" ';
                    }
                    $select_list.=' value="'.$value[1].'">'.$value[0].'</option>';
                }
                $select_list.='</select>';
                $html.=$select_list;
                $html.='</li>';
                break;
            case '7':
                $config=explode("\n", $info['config']);
                if(empty($config[0])){
                    $config[0]='Y-m-d H:i:s';
                }
                if(empty($config[1])){
                    $config[1]='yyyy-MM-dd HH:mm:ss';
                }
                if($data){
                    $info['default']=date($config[0],intval($info['default']));
                }else{
                    $info['default']=date($config[0]);
                }
                $html.='
                <li><label>'.$this->get_field_must($info['must'],'<span class="red">*</span> ').$info['name'].'：</label> <input class="text" readonly name="'.$info['field'].'" id="'.$info['field'].'"  value="'.$info['default'].'" type="text" '.$this->get_field_must($info['must'],'reg="'.stripslashes(cp_decode($info['verification'])).'" msg="'.$info['verification_tip'].'"').'  > </li>
                ';
                $html.='<script>';
                $html.="$('#".$info['field']."').calendar({ id:'#".$info['field']."',format:'".$config[1]."'});";
                $html.='</script>';
                break;
            case '8':
                $html.='
                <li><label>'.$this->get_field_must($info['must'],'<span class="red">*</span> ').$info['name'].'：</label> 
                ';
                $list=explode("\n",html_out($info['config']));
                foreach ($list as $key) {
                    $value=explode('|',$key);
                    $select_list.='<input name="'.$info['field'].'" class="radio" type="radio" value="'.$value[1].'" ';
                    if($info['default']==''){
                        $info['default']=1;
                    }
                    if($info['default']==$value[1]){
                        $select_list.='checked="checked" ';
                    }
                    $select_list.=' /> '.$value[0].'&nbsp;&nbsp;';
                }
                $html.=$select_list;
                $html.='</li>';
                break;
            case '9':
                $html.='
                <li><label>'.$this->get_field_must($info['must'],'<span class="red">*</span> ').$info['name'].'：</label> 
                ';
                $list=explode("\n",html_out($info['config']));
                
                if(!empty($data)){
                   $default=unserialize($info['default']);
                }else{
                   $default=explode('|', $info['default']);
                }
                foreach ($list as $key) {
                    $value=explode('|',$key);
                    $select_list.='<input name="'.$info['field'].'[]" type="checkbox" value="'.$value[1].'" ';
                    if($default<>''){
                    if(in_array($value[1], $default)){
                        $select_list.='checked="checked" ';
                    }
                    }
                    $select_list.=' /> '.$value[0].'&nbsp;&nbsp;';
                }
                $html.=$select_list;
                $html.='</li>';
                break;
        }
        return $html;
    }

    //获取格式化内容
    public function field_in($value,$type,$field,$data='') {
        switch ($type) {
            case '1':
            case '4':
                return in($value);
                break;
            
            case '2':
            case '3':
                return html_in($value);
                break;
            case '6':
            case '8':
                return intval($value);
                break;
            case '7':
                return strtotime($value);
                break;
            case '9':
                return serialize($value);
                break;
            default:
                return in($value);
                break;
        }
    }

    //检测扩展字段是否必填
    public function user_check($data)
    {

        //获取字段列表
        $field_list=$this->field_list();
        if(empty($field_list)){
            return;
        }
        foreach ($field_list as $value) {
            if($value['must']==1){
                if(empty($data[$value['field']])){
                    $this->msg($value['name'].'不能为空！',0);
                }
            }else{
                if(empty($data[$value['field']])){
                    return;
                }
            }
            if(!empty($value['verification'])){
                if (!preg_match("/".stripslashes(cp_decode($value['verification']))."/", $data[$value['field']])){
                    if($value['verification_tip']){
                        $this->msg($value['verification_tip'],0);
                    }else{
                        $this->msg($value['name'].'输入错误！',0);
                    }
                }
            }
            
        }

    }

    //保存附加信息
    public function save_append($data,$uid)
    {
        //获取字段列表
        $field_list=$this->field_list();
        if(empty($field_list)){
            return true;
        }
        $data_save=array();
        foreach ($field_list as $value) {
            $data_save[$value['field']]=$this->field_in($data[$value['field']],$value['type'],$value['field'],$data);
        }
        $data_save['uid']=$uid;
        if($this->model->table('user_append')->where('uid='.$uid)->count()){
            return $this->model->table('user_append')->where('uid='.$uid)->data($data_save)->update();
        }else{
            return $this->model->table('user_append')->data($data_save)->insert();
        }
    }

}

?>