<?php
class dataMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

 
    // 内容列表
    public function index()
    {
    	$id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$sectiondata=$wherestoptime=$where=$data=array();
       	$wherestoptime['aid']=$where['aid']=$id;
		$data['allcount']=model('data')->getcount($where);
		$data['allavg']=model('data')->staytimeavg($where);
       	$where['module']='course';
		$data['coursecount']=model('data')->getcount($where);
		$data['courseavg']=model('data')->staytimeavg($where);
		$where['module']='livevideo';
		$data['livecount']=model('data')->getcount($where);
		$data['liveavg']=model('data')->staytimeavg($where);
	  	
		$section=array(
				'0'=>array('name'=>'10分钟以下','where'=>'endtime-starttime<'.(10*60).''),
				'1'=>array('name'=>'10分钟至30分钟','where'=>'endtime-starttime between '.(10*60).' and '.(30*60).''),
				'2'=>array('name'=>'30分钟至一小时','where'=>'endtime-starttime between '.(30*60).' and '.(60*60).''),
				'3'=>array('name'=>'一小时至两小时','where'=>'endtime-starttime between '.(60*60).' and '.(120*60).''),
				'4'=>array('name'=>'两小时以上','where'=>'endtime-starttime > '.(120*60))
				);
		
	 
		foreach ($section as $key => $value) {
			$sectiondata['y'].="'".$value['name']."',";
			$wherestoptime[3]=$value['where'];
			
			unset($wherestoptime['module']);
			$week[$key]['allcount']=model('data')->getcount($wherestoptime);
			$sectiondata['allcount'].=$week[$key]["allcount"].",";
			$wherestoptime['module']='course';
			$week[$key]['coursecount']=model('data')->getcount($wherestoptime);
			$sectiondata['coursecount'].=$week[$key]["coursecount"].",";
			$wherestoptime['module']='livevideo';
		
			$week[$key]['livecount']=model('data')->getcount($wherestoptime);
			$sectiondata['livecount'].=$week[$key]["livecount"].",";
		}
		$this->assign('data',$data);
		$this->assign('sectiondata',$sectiondata);
		
		
		$data['city']=model('data')->city($id);
	
		$citydata1="";
		if(is_array($data['city'])){
		foreach($data['city'] as $k=>$v){
			$v['city']=empty($v['city'])? "未知":$v['city'];
			$citydata="['".$v['city']."',".$v['count']."],";
			$citydata1.=$citydata;
			
			}
		}
		$data['citydata']=$citydata1;
		
		$sexdata=model('data')->sex($id);
		$sexnum=array();
		if(is_array($sexdata)){
		foreach($sexdata as $key=>$value){
			$sexnum[$value['sex']]=$value['count'];
		}
		}
		if($data['allcount']){
		$data['human']=round(($data['allcount']-$sexnum[1]-$sexnum[2])/$data['allcount'], 4)*100;
		$data['man']=round($sexnum[1]/$data['allcount'], 4)*100;
		$data['woman']=round($sexnum[2]/$data['allcount'], 4)*100;
		}
		
		$this->assign('data',$data);
		
		$this->assign('sectiondata',$sectiondata);
		
        $this->show();
    }
	

}

?>