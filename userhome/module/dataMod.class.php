<?php
class dataMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

 
    // 内容列表
    public function index()
    {$this->actionname='数据详情';
    	$this->id=$id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$kbs='1';
		$sectiondata=$wherestoptime=$where=$data=array();
       	$wherestoptime['aid']=$where['aid']=$id;
	 	$data['allcount']=model('data')->getcount($where);
		$data['allavg']=model('data')->staytimeavg($where);
		$data['allflow']=intval(model('data')->looktimesum($where));
       	$where['cid']='13';
		$data['coursecount']=model('data')->getcount($where);
		$data['courseavg']=model('data')->staytimeavg($where);
		$data['courseflow']=intval(model('data')->looktimesum($where));
		$allflow=$data['allflow']?$data['allflow']:1;
		$data['coursepre']=round($data['courseflow']/$allflow, 3)*100;
		$where['cid']='16';
		$data['livecount']=model('data')->getcount($where);
		$data['liveavg']=model('data')->staytimeavg($where);
	
		$data['liveflow']=intval(model('data')->looktimesum($where));
			
		$data['livepre']=round($data['liveflow']/$allflow, 3)*100;
	 
	 
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
		
	
		
        $this->show();
    }
public function sectiondata(){
	
			$id=intval($_POST['id']);
        $this->alert_str($id,'int');
		$wherestoptime['aid']=$where['aid']=$id;
			$section=array(
				//'0'=>array('name'=>'10分钟以下','where'=>'endtime-starttime<'.(10*60).''),
//				'1'=>array('name'=>'10分钟至30分钟','where'=>'endtime-starttime between '.(10*60).' and '.(30*60).''),	
				'1'=>array('name'=>'10分钟至30分钟','where'=>'endtime-starttime<'.(30*60).''),
				'2'=>array('name'=>'30分钟至一小时','where'=>'endtime-starttime between '.(30*60).' and '.(60*60).''),
				'3'=>array('name'=>'一小时至两小时','where'=>'endtime-starttime between '.(60*60).' and '.(120*60).''),
				'4'=>array('name'=>'两小时以上','where'=>'endtime-starttime > '.(120*60))
				);
		
		
	 
		foreach ($section as $key => $value) {
			$sectiondata['y'][]=$value['name'];
			$wherestoptime[3]=$value['where'];
			unset($wherestoptime['cid']);
			$week[$key]['allcount']=model('data')->getcount($wherestoptime);
			$sectiondata['allcount'][]=intval($week[$key]["allcount"]);
			$wherestoptime['cid']='13';
			$week[$key]['coursecount']=model('data')->getcount($wherestoptime);
			$sectiondata['coursecount'][]=intval($week[$key]);
			$wherestoptime['cid']='16';
			$week[$key]['livecount']=model('data')->getcount($wherestoptime);
			$sectiondata['livecount'][]=intval($week[$key]["livecount"]);
		}
		$this->msg($sectiondata,1);
	}
public function flowdata(){
		$id=intval($_POST['id']);
        $this->alert_str($id,'int');
		$wherestoptime['aid']=$where['aid']=$id;
	 //流量统计
	  	$videostarttime=model('data')->getstarttime(array('aid'=>$id));
	    $videoendtime=model('data')->getendtime(array('aid'=>$id));
	  $timestep=intval(($videoendtime-$videostarttime)/10);$videoflow=array();
	  $where=array('aid'=>$id);
		for($i=1;$i<=10;$i++){
			$starttime=$videostarttime+($i-1)*$timestep;
			$endtime=$videostarttime+($i)*$timestep;
			
			
			$where[1]='starttime<'.$endtime.' and endtime>'.$starttime;
		
			$videoflow['date'][]=date('m-d',$endtime);
			$videoflow['value'][]=intval(model('data')->looktimesum($where));
			
			$livenum['date'][]=date('H:i',$endtime);
			$livenum['value'][]=intval(model('data')->getcount($where));
			
			}	
		$data=array('livenum'=>$livenum,'videoflow'=>$videoflow);
		$this->msg($data,1);
	
	}
public function sexcity(){
		$id=intval($_POST['id']);
        $this->alert_str($id,'int');
		$wherestoptime['aid']=$where['aid']=$id;
		$data['city']=model('data')->city($id);
	
		$citydata1="";
		if(is_array($data['city'])){
		foreach($data['city'] as $k=>$v){
			$v['city']=empty($v['city'])? "未知":$v['city'];
			$citydata[]=array($v['city'],intval($v['count']));
		
			
			}
		}
		$data['citydata']=$citydata;
		
		$this->msg($data,1);
	
	
	
	}
}

?>