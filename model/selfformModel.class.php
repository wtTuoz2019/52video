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
	public function form_value_list($where,$limit=null){
		return $this->model->table('selfform_value')->where($where)->limit($limit)->order('time desc')->select();
		}
	public function inputs_list($where){
		return $this->model->table('selfform_input')->where($where)->select();
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
					<button class="scbtn scwjbtn">文件上传</button>
                    <input name="'.$info['field'].'" type="text"  class="text_value wjval"   id="'.$info['field'].'" value="'.$info['default'].'" 
                ';
                if(!empty($info['must'])){
                    $html.=' reg="\S"  msg="'.$info['name'].'不能为空！" ';
                }
                $html.='/  placeholder="'.$info['tip'].'">
                <input type="file" id="'.$info['field'].'_botton" class="sc scwj" value="" />  
				
                    </li>
                    
                </tr>
                ';
                $html.='
					<script>
					$("#'.$info['field'].'_botton").change(function() {
						var file = this.files[0];
					
						img=file;
						var r = new FileReader();
						r.readAsDataURL(file);
						
						
						$(r).load(function() {
							
							  $.post("/selfform/upload", { img: this.result},function(ret){
								  if(ret.status=="1"){
									  	$("#'.$info['field'].'").val(ret.message.pic);
									  }else{
										  
										   tip({msg:ret.message});
										  }
								  
							  },"json")
							
						})
				 	})
					
					</script>
				';
                break;
            case '10':
                
                $html.='
                   <li class="title">
                  '.$info['name'].'</li>
                    <li class="table">
					
                    <input name="'.$info['field'].'" type="hidden"  class="text_value"  style=""  id="'.$info['field'].'" value="'.$info['default'].'" 
                ';
                if(!empty($info['must'])){
                    $html.=' reg="\S"  msg="'.$info['name'].'不能为空！" ';
                }
                $html.='  placeholder="'.$info['tip'].'"/>
                <input type="file" id="'.$info['field'].'_botton"  class="sc" value="" />
				<button class="scbtn"><img src="/public/images/dantu.png"/>单图片上传</button>
				<div id="imgDiv">
					<div class="userimgk">
						<img src="'.$this->config['imageurl'].$info['default'].'"/>
					</div>
					</div
                    </li>
                    
                ';
                $html.='
					<script>
					$("#'.$info['field'].'_botton").change(function() {
						var file = this.files[0];
						img=file;
						var r = new FileReader();
						r.readAsDataURL(file);
						$("#imgDiv").html("<div class=\'userimgk loading\'><img src=\'/public/images/timg.gif\' /></div>");
						$(r).load(function() {
							
							  $.post("/selfform/upload", { img: this.result},function(ret){
								  if(ret.status=="1"){
									  $(".loading").remove();
									 $("#imgDiv").html("<div class=\'userimgk\'><img src=\'"+ret.message.pic+"\'/></div>");
									  $("#'.$info['field'].'").val(ret.message.pic);
									  }else{
										  
										   tip({msg:ret.message});
										  }
								  
							  },"json")
							
						})
				 	})
					
					</script>
				';
                break;
            case '5':
                
                $html.='
                   <li class="title">
                  '.$info['name'].'</li>
                    <li class="table">
					
                    <input type="file" id="'.$info['field'].'_button" class="sc" value="" />
					<button class="scbtn"><img src="/public/images/duotu.png"/>组图上传</button>
					<div id="imgDiv2">';
					
					   if(!empty($data)){
                $info['default']=unserialize($info['default']);
                if(!empty($info['default'])){
                foreach ($info['default'] as $value) {
                $html.='<div class="userimgk" ><img src="'.$this->config['imageurl'].$value['url'].'"/><span onclick="$(this).parent().remove()">X</span><input  id="'.$info['field'].'[]" name="'.$info['field'].'[]" type="hidden" value="'.$value['url'].'" /></div>';
                }
                }
                }
					
					
					$html.='</div>
					 </li>
                    ';

                

                
                $html.='
					<script>
					$("#'.$info['field'].'_button").change(function() {
						var file = this.files[0];
						img=file;
						var r = new FileReader();
						r.readAsDataURL(file);
						$("#imgDiv2").append("<div class=\'userimgk loading\'><img src=\'/public/images/timg.gif\' /></div>");
						
						$(r).load(function() {
							
							  $.post("/selfform/upload", { img: this.result},function(ret){
								  if(ret.status=="1"){
									  $(".loading").remove();
									  $("#imgDiv2").append(\'<div class="userimgk" ><img src="\'+ret.message.pic+\'"/><span onclick="$(this).parent().remove()">X</span><input  id="'.$info['field'].'[]" name="'.$info['field'].'[]" type="hidden" value="\'+ret.message.pic+\'" /></div>\');
									  }else{
										  
										   tip({msg:ret.message});
										  }
								  
							  },"json")
							
						})
				 	})
					
					</script>
				
				';
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
                    $select_list.='<div><input name="'.$info['field'].'" type="radio" value="'.$value[1].'" ';
                   // if($info['default']==''){
//                        $info['default']=1;
//                    }
                    if($info['default']==$value[1]){
                        $select_list.='checked="checked" ';
                    }
                    $select_list.=' /> '.$value[0].'&nbsp;&nbsp;</div>';
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
                    $select_list.='<div><input name="'.$info['field'].'[]" type="checkbox" value="'.$value[1].'" ';
                    if($default<>''){
                    if(in_array($value[1], $default)){
                        $select_list.='checked="checked" ';
                    }
                    }
                    $select_list.=' /> '.$value[0].'&nbsp;&nbsp;</div>';
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