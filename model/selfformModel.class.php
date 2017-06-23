<?php
//表单数据处理
class selfformModel extends commonModel {

	public function __construct()
    {
        parent::__construct();
    }
	
	public function info($id){
		return $this->model->table('selfform')->where(array('id'=>$id))->find();
		
		}
    //字段列表
    public function form_inputs_list($where)
    {
		
        return $this->model->table('selfform_input')->where($where)->order('sequence asc,id asc')->select();
    }
      //获取字段HTML
    public function get_field_html($info,$data=null){
        $info['default']=html_out($info['default']);

        if(!empty($data)){
            $info['default']=$data;
        }

        $html='';
        switch ($info['type']) {
            case '1':
                $html.='
                <li class="title">
                  '.$info['name'].'</li>
                    <li class="table">
                    <input name="'.$info['field'].'" type="text" class="text_value" id="'.$info['field'].'" value="'.$info['default'].'" 
                ';
                if(!empty($info['must'])){
                    $html.=' reg="\S" msg="'.$info['name'].'不能为空！" ';
                }
                $html.=' placeholder="'.$info['tip'].'"/>
				
                    </li>
                   
              
                ';
                break;
            case '2':
                $html.='
                <li class="title">
                  '.$info['name'].'</li>
                    <li class="table"><textarea name="'.$info['field'].'" class="text_textarea" id="'.$info['field'].'"  placeholder="'.$info['tip'].'" >'.$info['default'].'</textarea>
					 
                    </li>
                  
               
                ';
                break;
            case '3':
                
                $html.='
                 <li class="title">
                  '.$info['name'].'</li>
                    <li class="textarea"><textarea name="'.$info['field'].'" style="width:100%; height:350px;" id="'.$info['field'].'">'.$info['default'].'</textarea>
                    '.module('editor')->get_editor_upload($info['field'].'_upload','editor_'.$info['field']).'
                    <input type="button" id="'.$info['field'].'_upload" class="button_small" style="margin-top:10px;" value="上传图片和文件到编辑器" />
                    </li>
              
                ';
                $html.=module('editor')->get_editor($info['field'],true);
                break;
            case '4':
                
                $html.='
                  <li class="title">
                  '.$info['name'].'</li>
                    <li class="table">
                    <input name="'.$info['field'].'" type="text"  class="text_value"  style="width:200px; float:left"  id="'.$info['field'].'" value="'.$info['default'].'" 
                ';
                if(!empty($info['must'])){
                    $html.=' reg="\S"  msg="'.$info['name'].'不能为空！" ';
                }
                $html.='/  placeholder="'.$info['tip'].'">
                &nbsp;&nbsp;<input type="button" id="'.$info['field'].'_botton" class="button_small" value="选择文件" />  
                    </li>
                    
                </tr>
                ';
                $html.=module('editor')->get_file_upload($info['field'].'_botton',$info['field'],true);
                break;
            case '10':
                
                $html.='
                   <li class="title">
                  '.$info['name'].'</li>
                    <li class="table">
                    <input name="'.$info['field'].'" type="text"  class="text_value"  style="width:200px; float:left"  id="'.$info['field'].'" value="'.$info['default'].'" 
                ';
                if(!empty($info['must'])){
                    $html.=' reg="\S"  msg="'.$info['name'].'不能为空！" ';
                }
                $html.='  placeholder="'.$info['tip'].'"/>
                &nbsp;&nbsp;<input type="button" id="'.$info['field'].'_botton" class="button_small" value="选择图片" /> 
                    </li>
                    
                ';
                $html.=module('editor')->get_image_upload($info['field'].'_botton',$info['field'],true);
                break;
            case '5':
                
                $html.='
                   <li class="title">
                  '.$info['name'].'</li>
                    <li class="table">
                    <input type="button" id="'.$info['field'].'_button" class="button_small" value="上传多图" />
                    <div class="fn_clear"></div>
                    <div class="images">
                    <ul id="'.$info['field'].'_list" class="images_list">';

                if(!empty($data)){
                $info['default']=unserialize($info['default']);
                if(!empty($info['default'])){
                foreach ($info['default'] as $value) {
                $html.="<li>
                        <div class='pic' id='images_button'>
                        <img src='".$value['url']."' width='125' height='105' />
                        <input  id='".$info['field']."[]' name='".$info['field']."[]' type='hidden' value='".$value['url']."' />
                        <input  id='".$info['field']."_original[]' name='".$info['field']."_original[]' type='hidden' value='".$value['original']."' />
                        </div>
                        <div class='title'>标题： <input name='".$info['field']."_title[]' type='text' id='".$info['field']."_title[]' value='".$value['title']."' /></div>
                        <div class='title'>排序： <input id='".$info['field']."_order[]' name='".$info['field']."_order[]' value='".$value['order']."' type='text' style='width:50px;' /> <a href='javascript:void(0);' onclick='$(this).parent().parent().remove()'>删除</a></div>
                    </li>";
                }
                }
                }

                $html.="</ul>
                    <div style='clear:both'></div>
                    </div>
                    </li>
              
                ";
                $html.=module('editor')->get_images_upload($info['field'],$ajax=true);
                break;
            case '6':
               
                $html.='    <li class="title">
                  '.$info['name'].'</li>
                    <li class="table">';
                $select_list='<select name="'.$info['field'].'" id="'.$info['field'].'">';
                $list=explode("\n",html_out($info['options']));
				$select_list.='<option value="0">请选择</option>';
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
                $config=explode("\n", $info['options']);
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
                $html.='    <li class="title">
                  '.$info['name'].'</li>
                    <li class="table">';
               
                $html.='<input name="'.$info['field'].'"  id="'.$info['field'].'" type="text" class="text_value" style="width:210px; float:left" value="'.$info['default'].'"';
                if($info['must']==1){
                    $html.=' reg="\S" msg="'.$info['name'].'不能为空" ';
                }
                $html.='/><div  id="'.$info['field'].'_button" class="time"></div></li>';
            
                $html.='<script>';
                $html.="$('#".$info['field']."_button').calendar({ id:'#".$info['field']."',format:'".$config[1]."'});";
                $html.='</script>';
                break;
            case '8':
                $html.='    <li class="title">
                  '.$info['name'].'</li>
                    <li class="table">';
              
                $list=explode("\n",html_out($info['options']));
                foreach ($list as $key) {
                    $value=explode('|',$key);
                    $select_list.='<input name="'.$info['field'].'" type="radio" value="'.$value[1].'" ';
                   // if($info['default']==''){
//                        $info['default']=1;
//                    }
                    if($info['default']==$value[1]){
                        $select_list.='checked="checked" ';
                    }
                    $select_list.=' /> '.$value[0].'&nbsp;&nbsp;';
                }
                $html.=$select_list;
                $html.='</li>';
              
                break;
            case '9':
                $html.='    <li class="title">
                  '.$info['name'].'</li>
                    <li class="table">';
             
                $list=explode("\n",html_out($info['options']));
                
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
                $html.='  </li>';
              
                break;
        }
        return $html;
    }

	public function add_value($data){
		return $this->model->table('selfform_value')->data($data)->insert();
		
		}
	public function edit_value($data){
		return $this->model->table('selfform_value')->where(array('fid'=>$data['fid'],'uid'=>$data['uid']))->data($data)->update();
		
		}
   public function input_value($where){
	   return $this->model->table('selfform_value')->where($where)->find();
	   
	   
	   }

}