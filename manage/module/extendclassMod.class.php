<?php

class extendclassMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
		$this->times=array('1'=>'周一','2'=>'周二','3'=>'周三','4'=>'周四','5'=>'周五','6'=>'周六','7'=>'周日');
    }

	 
    public function index(){
		
		 $this->show();
		
		}
		
	  public function website(){
		
		 $this->show();
		
		}
		
	public function classes(){
	
		$where['uid']= $this->user['id'];
		 $this->list=model('extendclass')->classes_list($where);
		 $this->show();
		
		}
	public function classes_add(){
		$this->actionname='添加';
		$this->action='classes_add';
		
		 $this->show('extendclass/classes_info');
		}
	public function classes_add_save(){
		
		$_POST['uid']= $this->user['id'];
		model('extendclass')->classes_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function classes_edit(){
		$this->actionname='编辑';
		$this->action='classes_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		
		 $this->info=model('extendclass')->classes_info(array('id'=>$id));
		
		 $this->show('extendclass/classes_info');
		}
	
	public function classes_edit_save(){
		
		model('extendclass')->classes_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function classes_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->classes_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
	public function grade_up(){
		model('extendclass')->grade_up( $this->user['id']);
		 $this->msg('操作成功！',1);
		}
	public function scoretype(){
	
		$where['uid']= $this->user['id'];
		 $this->list=model('extendclass')->scoretype_list($where);
		 $this->show();
		
		}
	public function scoretype_add(){
		$this->actionname='添加';
		$this->action='scoretype_add';
		
		 $this->show('extendclass/scoretype_info');
		}
	public function scoretype_add_save(){
		
		$_POST['uid']= $this->user['id'];
		model('extendclass')->scoretype_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function scoretype_edit(){
		$this->actionname='编辑';
		$this->action='scoretype_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		
		 $this->info=model('extendclass')->scoretype_info(array('id'=>$id));
		
		 $this->show('extendclass/scoretype_info');
		}
	
	public function scoretype_edit_save(){
		
		model('extendclass')->scoretype_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function scoretype_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->scoretype_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
	public function teacher(){
			$user=$this->user;
			
			if($user['gid']==6){
			$temp;
		
		
				$temp[]=$user['id'];
				
			$nextuser=model('user')->admin_list(' AND pid='.$user['id']);
			if($nextuser){
			foreach($nextuser as $key=>$val){
				$temp[]=$val['id'];
				}
			}
			
			if($temp){
				$where='uid in ('.implode(',',$temp).') ';	
				}
			}else{
		
			
			if($user['cid']){
				$where='uid='.$uid;	
				}
		
     
			}
		
		  	if($_FILES['file']['name']){
		$return=module('editor_upload')->upload();
			
			if($return['error'])$this->error($return['msg']);
			$data = new Spreadsheet_Excel_Reader();
			// 设置输入编码 UTF-8/GB2312/CP936等等
			$data->setOutputEncoding('UTF-8');
			$data->read('..'.$return['url']);
			$sheet=$data->sheets[0];
			$rows=$sheet['cells'];
			$temp=array();
		
			foreach($rows as  $key=>$val){
				if($key>1){
						$array=array('name'=>$val[1],'mobile'=>$val[2],'title'=>$val[3],'SXTEACHNUMBER'=>$val[4],'des'=>$val[5],'uid'=>$this->user['id']);
						$teacher[]=$array;
						
						}
				
			
				}
			
					if($teacher)
				model('extendclass')->teacher_add_saveall($teacher);
			}
		  
		if($_GET['download']){
			$list=model('extendclass')->teacher_list($where);
			
			require(CP_PATH . 'ext/PHPExcel.php');
			require(CP_PATH . 'ext/PHPExcel/IOFactory.php');
			$objPHPExcel = new PHPExcel();
	        $objPHPExcel = new PHPExcel(); 
      	  $objPHPExcel->getProperties()->setCreator("PHPExcel")
                                     ->setLastModifiedBy("PHPExcel")
                                     ->setTitle("PHPExcel reports")
                                     ->setSubject("PHPExcel reports")
                                     ->setDescription("PHPExcel document for Office 2003 XLS, generated at ".date('Y-m-d'))
                                     ->setKeywords("PHPExcel reports")
                                     ->setCategory("PHPExcel");
			
			$keynames=array('名称','手机','职称','师训号','介绍');
		$keys = array_keys($keynames);
		 $xlsx[] = $keynames;	
		foreach($list as $key=>$value){
		$temp=array( $value['name'], $value['mobile'], $value['title'], $value['SXTEACHNUMBER'], $value['des']);
		$xlsx[]=$temp;
	
      		  
     	   }	
		   
		   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+1))->setWidth(20);
			 foreach($xlsx as $index => $row){
            $i = $index + 1;
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            foreach($keys as $key => $val){
				if($key<26){
               $ascii = chr(65+$key);
				}else{
				$ascii = chr(65).chr(65+($key-26));	 
					}
			$sheet->setCellValueExplicit($ascii.$i, $row[$val],PHPExcel_Cell_DataType::TYPE_STRING);
              
			   
				
            }
     
		
			}
			
        $objPHPExcel->getActiveSheet()->setTitle('老师数据');
        $objPHPExcel->setActiveSheetIndex(0);ob_end_clean() ;
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=teacher.xlsx");
		  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
            $objWriter->save('php://output');  die;
		
			}
		
		
		 	   $url = __URL__ . '/teacher/page-{page}.html';
        $listRows = 20;
        $limit=$this->pagelimit($url,$listRows);
		if($_GET['s']){
			 $where['name']=array('like',"'%".$_GET['s']."%'");
			  }
			  
			 
		 $this->list=model('extendclass')->teacher_list($where,$limit);
		 
		   $count=model('extendclass')->teacher_count($where);
		   $this->page = $this->page($url, $count, $listRows);
		 $this->show();
		
		}
	public function teacher_add(){
		$this->actionname='添加';
		$this->action='teacher_add';
		
		 $this->show('extendclass/teacher_info');
		}
	public function teacher_add_save(){
		
		$_POST['uid']= $this->user['id'];
		model('extendclass')->teacher_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function teacher_edit(){
		$this->actionname='编辑';
		$this->action='teacher_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		
		 $this->info=model('extendclass')->teacher_info(array('id'=>$id));
		
		 $this->show('extendclass/teacher_info');
		}
	
	public function teacher_edit_save(){
		
		model('extendclass')->teacher_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function teacher_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->teacher_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
	 public function teacher_batch(){
        if(empty($_POST['status'])||empty($_POST['id'])){
            $this->msg('请先选择内容！',0);
        }
        $id_array=substr($_POST['id'],0,-1);
        $id_array=explode(',', $id_array);
        switch ($_POST['status']) {
            case '1':
                //审核
                foreach ($id_array as $value) {
                //    model('forum')->comment_status($value);
                }
				break;
			 case '2':
                //审核
                foreach ($id_array as $value) {
                    model('extendclass')->teacher_del($value);
                }
                break;
           
        }
        $this->msg('操作执行完毕！',1);

    }
	public function student(){
		
		  $where['uid']= $this->user['id'];
		  $this->bj=$bj=model('extendclass')->classes_list($where);
		  	if($_FILES['file']['name']){
		$return=module('editor_upload')->upload();
			
			if($return['error'])$this->error($return['msg']);
		//	$data = new Spreadsheet_Excel_Reader();
//			// 设置输入编码 UTF-8/GB2312/CP936等等
//			$data->setOutputEncoding('UTF-8');
//			
//			$data->read('..'.$return['url']);
//			
				require(CP_PATH . 'ext/PHPExcel/IOFactory.php');
$inputFileName='..'.$return['url'];

 $inputFileType = PHPExcel_IOFactory::identify($inputFileName);  
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);  
    $objPHPExcel = $objReader->load($inputFileName);  
$sheet = $objPHPExcel->getSheet(0); // 读取第一個工作表
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumm = $sheet->getHighestColumn(); // 取得总列数
 $highestColumm= PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
 $temp=array();
		
/** 循环读取每个单元格的数据 */
for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
 		
		$name=$sheet->getCellByColumnAndRow(0, $row)->getValue();
	
		$mobile=$sheet->getCellByColumnAndRow(1, $row)->getValue();
	
		$grade=$sheet->getCellByColumnAndRow(2, $row)->getValue();
		$class=$sheet->getCellByColumnAndRow(3, $row)->getValue();
		$schoolcode=$sheet->getCellByColumnAndRow(4, $row)->getValue();
		$codenumber=$sheet->getCellByColumnAndRow(5, $row)->getValue();
						$array=array('name'=>preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/","",$name),'mobile'=>preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/","",$mobile),'schoolcode'=>preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/","",$schoolcode),'codenumber'=>preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/","",$codenumber),'uid'=>$this->user['id']);
						foreach($bj as $k=>$v){
							if($v['grade']==intval($grade)&&$v['class']==intval($class)){
								$array['bj_id']=$v['id'];
								$students[]=$array;;break;
								}
							}
						
						
						
       
				
		
       
    
}
	
			
					if($students)
				model('extendclass')->student_add_saveall($students);
			}
		  
		  
		  
		if($_GET['download']){
			$list=model('extendclass')->student_list($where);
				
			require(CP_PATH . 'ext/PHPExcel.php');
			require(CP_PATH . 'ext/PHPExcel/IOFactory.php');
			$objPHPExcel = new PHPExcel();
	        $objPHPExcel = new PHPExcel(); 
      	  $objPHPExcel->getProperties()->setCreator("PHPExcel")
                                     ->setLastModifiedBy("PHPExcel")
                                     ->setTitle("PHPExcel reports")
                                     ->setSubject("PHPExcel reports")
                                     ->setDescription("PHPExcel document for Office 2003 XLS, generated at ".date('Y-m-d'))
                                     ->setKeywords("PHPExcel reports")
                                     ->setCategory("PHPExcel");
			
			$keynames=array('姓名','联系手机','学籍号','学号','班级');
		$keys = array_keys($keynames);
		 $xlsx[] = $keynames;	
		foreach($list as $key=>$value){
		$temp=array( $value['name'], $value['mobile'], $value['schoolcode'], $value['codenumber'],  $bj[$value['bj_id']]['grade'].'年级'.$bj[$value['bj_id']]['class'].'班');
		$xlsx[]=$temp;
	
      		  
     	   }	
		   
		   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+1))->setWidth(20);
		     $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+2))->setWidth(30);
			   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+3))->setWidth(30);
			 foreach($xlsx as $index => $row){
            $i = $index + 1;
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            foreach($keys as $key => $val){
				if($key<26){
               $ascii = chr(65+$key);
				}else{
				$ascii = chr(65).chr(65+($key-26));	 
					}
		 $sheet->setCellValueExplicit($ascii.$i, $row[$val],PHPExcel_Cell_DataType::TYPE_STRING);
               //$sheet->setCellValue($ascii.$i, $row[$val]);
			   
				
            }
     
		
			}
			
        $objPHPExcel->getActiveSheet()->setTitle('学生数据');
        $objPHPExcel->setActiveSheetIndex(0);ob_end_clean() ;
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=student.xlsx");
		  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
            $objWriter->save('php://output');  die;
			
			
	
			}
		  
		   $url = __URL__ . '/student/page-{page}.html';
        $listRows = 20;
         $limit=$this->pagelimit($url,$listRows);
		if($_GET['s']){
			 $where['name']=array('like',"'%".$_GET['s']."%'");
			  }
		 $this->list=model('extendclass')->student_list($where,$limit);
		   $count=model('extendclass')->student_count($where);
		   $this->page = $this->page($url, $count, $listRows);
		 $this->show();
		
		}
	public function student_add(){
		$this->actionname='添加';
		$this->action='student_add';
		$where['uid']= $this->user['id'];
		$this->bj=model('extendclass')->classes_list($where);
		 $this->show('extendclass/student_info');
		}
	public function student_add_save(){
		
		$_POST['uid']= $this->user['id'];
		model('extendclass')->student_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function student_edit(){
		$this->actionname='编辑';
		$this->action='student_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$where['uid']= $this->user['id'];
		$this->bj=model('extendclass')->classes_list($where);
		 $this->info=model('extendclass')->student_info(array('id'=>$id));
		
		 $this->show('extendclass/student_info');
		}
	
	public function student_edit_save(){
		
		model('extendclass')->student_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function student_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->student_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
	
	 public function student_batch(){
        if(empty($_POST['status'])||empty($_POST['id'])){
            $this->msg('请先选择内容！',0);
        }
        $id_array=substr($_POST['id'],0,-1);
        $id_array=explode(',', $id_array);
        switch ($_POST['status']) {
            case '1':
                //审核
                foreach ($id_array as $value) {
                //    model('forum')->comment_status($value);
                }
				break;
			 case '2':
                //审核
                foreach ($id_array as $value) {
                    model('extendclass')->student_del($value);
                }
                break;
           
        }
        $this->msg('操作执行完毕！',1);

    }
	public function batch(){
	
		$where['uid']= $this->user['id'];
		 $this->list=model('extendclass')->batch_list($where);
		 $this->show();
		
		}
	public function batch_add(){
		$this->actionname='添加';
		$this->action='batch_add';
		
		 $this->show('extendclass/batch_info');
		}
	public function batch_add_save(){
		
		$_POST['uid']= $this->user['id'];
		
		$_POST['endtime']=strtotime($_POST['endtime']);
		model('extendclass')->batch_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function batch_edit(){
		$this->actionname='编辑';
		$this->action='batch_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		
		 $this->info=model('extendclass')->batch_info(array('id'=>$id));
		
		 $this->show('extendclass/batch_info');
		}
	
	public function batch_edit_save(){
		
		$_POST['endtime']=strtotime($_POST['endtime']);
		model('extendclass')->batch_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function batch_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->batch_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
	public function exportnosign(){
		$bid=intval($_GET['bid']);
		$teacher=model('extendclass')->teacher_list($where);
		$bj=model('extendclass')->classes_list($where);
		if($bid){
		 $list=model('extendclass')->nosignup_course_list($this->user['id'],$bid);	
		if($list){
			
				require(CP_PATH . 'ext/PHPExcel.php');
			require(CP_PATH . 'ext/PHPExcel/IOFactory.php');
			$objPHPExcel = new PHPExcel();
	        $objPHPExcel = new PHPExcel(); 
      	  $objPHPExcel->getProperties()->setCreator("PHPExcel")
                                     ->setLastModifiedBy("PHPExcel")
                                     ->setTitle("PHPExcel reports")
                                     ->setSubject("PHPExcel reports")
                                     ->setDescription("PHPExcel document for Office 2003 XLS, generated at ".date('Y-m-d'))
                                     ->setKeywords("PHPExcel reports")
                                     ->setCategory("PHPExcel");
			$keynames=array('姓名','联系手机','学籍号','学号','班级');
		$keys = array_keys($keynames);
		 $xlsx[] = $keynames;	
		foreach($list as $key=>$value){
		$temp=array( $value['name'], $value['mobile'], $value['schoolcode'], $value['codenumber'],  $bj[$value['bj_id']]['grade'].'年级'.$bj[$value['bj_id']]['class'].'班');
		$xlsx[]=$temp;
	
      		  
     	   }	
		   
		   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+1))->setWidth(20);
		     $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+2))->setWidth(30);
			   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+3))->setWidth(30);
			 foreach($xlsx as $index => $row){
            $i = $index + 1;
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            foreach($keys as $key => $val){
				if($key<26){
               $ascii = chr(65+$key);
				}else{
				$ascii = chr(65).chr(65+($key-26));	 
					}
		 $sheet->setCellValueExplicit($ascii.$i, $row[$val],PHPExcel_Cell_DataType::TYPE_STRING);
               //$sheet->setCellValue($ascii.$i, $row[$val]);
			   
				
            }
     
		
			}
			
        $objPHPExcel->getActiveSheet()->setTitle('学生数据');
        $objPHPExcel->setActiveSheetIndex(0);ob_end_clean() ;
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=student.xlsx");
		  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
            $objWriter->save('php://output');  die;
			
			
	
			
			
			
			
			}	
		
		}
		}
	public function course(){
		$where['uid']= $this->user['id'];
		$this->bid=$bid=intval($_GET['bid']);
		 $this->teacher=$teacher=model('extendclass')->teacher_list($where);
	
		  $this->bj=$bj=model('extendclass')->classes_list($where);
		if($_GET['export']){
			if($_POST['bj_id']){
			
		 $list=model('extendclass')->signup_course_list('A.uid='.$this->user['id'].' and C.bid='.$bid.' and A.bj_id='.$_POST['bj_id']);	
		if($list){
			require(CP_PATH . 'ext/PHPExcel.php');
			require(CP_PATH . 'ext/PHPExcel/IOFactory.php');
			$objPHPExcel = new PHPExcel();
	        
      	  $objPHPExcel->getProperties()->setCreator("PHPExcel")
                                     ->setLastModifiedBy("PHPExcel")
                                     ->setTitle("PHPExcel reports")
                                     ->setSubject("PHPExcel reports")
                                     ->setDescription("PHPExcel document for Office 2003 XLS, generated at ".date('Y-m-d'))
                                     ->setKeywords("PHPExcel reports")
                                     ->setCategory("PHPExcel");
			$keynames=array('姓名','联系手机','学籍号','学号','班级','课程名称','任课老师','上课地点','上课时间','第几节','分数');
		$keys = array_keys($keynames);
		 $xlsx[] = $keynames;	
		foreach($list as $key=>$value){
		$temp=array( $value['name'], $value['mobile'], $value['schoolcode'], $value['codenumber'],  $bj[$value['bj_id']]['grade'].'年级'.$bj[$value['bj_id']]['class'].'班',$value['title'],$teacher[$value['tid']]['name'],$value['place'],$this->times[$value['classtime']],$value['jie'],$value['score']);
		$xlsx[]=$temp;
	
      		  
     	   }	
		   
		   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+1))->setWidth(20);
		     $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+2))->setWidth(30);
			   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+3))->setWidth(30);
			 foreach($xlsx as $index => $row){
            $i = $index + 1;
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            foreach($keys as $key => $val){
				if($key<26){
               $ascii = chr(65+$key);
				}else{
				$ascii = chr(65).chr(65+($key-26));	 
					}
		 $sheet->setCellValueExplicit($ascii.$i, $row[$val],PHPExcel_Cell_DataType::TYPE_STRING);
               //$sheet->setCellValue($ascii.$i, $row[$val]);
			   
				
            }
     
		
			}
			
        $objPHPExcel->getActiveSheet()->setTitle('学生数据');
        $objPHPExcel->setActiveSheetIndex(0);ob_end_clean() ;
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=student.xlsx");
		  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
            $objWriter->save('php://output');  
				die;
			
			}	
			}else{
				
			$list=model('extendclass')->signup_course_bj_list('A.uid='.$this->user['id'].' and C.bid='.$bid);		
		
					if($list){
						$filename = __ROOTDIR__."/upload/extendclass/" .$this->user['id']. ".zip"; // 最终生成的文件名（含路径）
// 生成文件
$zip = new ZipArchive (); // 使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
if ($zip->open ($filename ,\ZipArchive::OVERWRITE) !== true) {
	if($zip->open ($filename ,\ZipArchive::CREATE) !== true){
		
		exit ( '无法打开文件，或者文件创建失败' );}
}				

		require(CP_PATH . 'ext/PHPExcel.php');
			require(CP_PATH . 'ext/PHPExcel/IOFactory.php');
		
			$keynames=array('姓名','联系手机','学籍号','学号','班级','课程名称','任课老师','上课地点','上课时间','第几节','分数');
			$keys = array_keys($keynames);
		foreach($list as $k=>$v){
			
			$objPHPExcel = new PHPExcel();
	        
      	  $objPHPExcel->getProperties()->setCreator("PHPExcel")
                                     ->setLastModifiedBy("PHPExcel")
                                     ->setTitle("PHPExcel reports")
                                     ->setSubject("PHPExcel reports")
                                     ->setDescription("PHPExcel document for Office 2003 XLS, generated at ".date('Y-m-d'))
                                     ->setKeywords("PHPExcel reports")
                                     ->setCategory("PHPExcel");
		 $xlsx=array();
		 $xlsx[] = $keynames;	
		foreach($v as $key=>$value){
		$temp=array( $value['name'], $value['mobile'], $value['schoolcode'], $value['codenumber'],  $bj[$value['bj_id']]['grade'].'年级'.$bj[$value['bj_id']]['class'].'班',$value['title'],$teacher[$value['tid']]['name'],$value['place'],$this->times[$value['classtime']],$value['jie'],$value['score']);
		$xlsx[]=$temp;
	
      		  
     	   }	
		   
		   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+1))->setWidth(20);
		     $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+2))->setWidth(30);
			   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+3))->setWidth(30);
			 foreach($xlsx as $index => $row){
            $i = $index + 1;
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            foreach($keys as $key => $val){
				if($key<26){
               $ascii = chr(65+$key);
				}else{
				$ascii = chr(65).chr(65+($key-26));	 
					}
		 $sheet->setCellValueExplicit($ascii.$i, $row[$val],PHPExcel_Cell_DataType::TYPE_STRING);
               //$sheet->setCellValue($ascii.$i, $row[$val]);
			   
				
            }
      
		
			}
			
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	  $file= __ROOTDIR__."/upload/extendclass/".$bj[$k]['grade'].'-'.$bj[$k]['class'].'.xls';
		$objWriter->save($file);
		$zip->addFile( $file ,basename($file));
			}
		
		$zip->close ();
		
		header ( "Cache-Control: max-age=0" );
header ( "Content-Description: File Transfer" );
header ( 'Content-disposition: attachment; filename=' . basename ( $filename ) ); // 文件名
header ( "Content-Type: application/zip" ); // zip格式的
header ( "Content-Transfer-Encoding: binary" ); // 告诉浏览器，这是二进制文件
header ( 'Content-Length: ' . filesize ( $filename ) ); // 告诉浏览器，文件大小
@readfile ( $filename );//输出文件;
die;
						
		}
		
		
			
				}
			
			
	
			}
		
		
		
		
		  if($_GET['s']){
			 $where['name']=array('like',"'%".$_GET['s']."%'");
			  }
			 $where['bid']=$bid;
		   $list=model('extendclass')->course_list($where);
		   if($list){
			   foreach($list as $key=>$val){
				   $list[$key]['signupnum']=model('extendclass')->signup_num(array('cid'=>$val['id']));
				   
				   }
			   
			   }
			   
		if($_GET['download']){
				require(CP_PATH . 'ext/PHPExcel.php');
			require(CP_PATH . 'ext/PHPExcel/IOFactory.php');
			$objPHPExcel = new PHPExcel();
	        $objPHPExcel = new PHPExcel(); 
      	  $objPHPExcel->getProperties()->setCreator("PHPExcel")
                                     ->setLastModifiedBy("PHPExcel")
                                     ->setTitle("PHPExcel reports")
                                     ->setSubject("PHPExcel reports")
                                     ->setDescription("PHPExcel document for Office 2003 XLS, generated at ".date('Y-m-d'))
                                     ->setKeywords("PHPExcel reports")
                                     ->setCategory("PHPExcel");
			$keynames=array('教师','课程代码','课程名称','选修课组','课程模块','招生数','每班限额','报名人数','场地','报名开始时间','报名结束时间','课程时间','第几节','可选班级','课程说明','特别说明');
		$keys = array_keys($keynames);
		 $xlsx[] = $keynames;	$times=array('1'=>'周一','2'=>'周二','3'=>'周三','4'=>'周四','5'=>'周五','6'=>'周六','7'=>'周日');
		foreach($list as $key=>$val){
			
			$bj_ids=unserialize($val['bj_ids']);
			$bjs='';
			if($bj_ids){
				foreach($bj_ids as $v){
				$bjs.=$bj[$v]['grade'].'年级'.$bj[$v]['class']."班|";
				}
				}
		$temp=array($teacher[$val['tid']]['name'], $val['code'], $val['name'], $val['group'], $val['title'],$val['number'],$val['limitnum'],$val['signupnum'],$val['place'],date('Ymd H:i:s',$val['starttime']),date('Ymd H:i:s',$val['endtime']),$times[$val['classtime']],$val['jie'],$bjs?$bjs:'全部',$val['info'],$val['des']);
		$xlsx[]=$temp;
	
      		  
     	   }	
		   
		   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+1))->setWidth(20);
		     $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+2))->setWidth(30);
			   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+3))->setWidth(30);
			 foreach($xlsx as $index => $row){
            $i = $index + 1;
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            foreach($keys as $key => $val){
				if($key<26){
               $ascii = chr(65+$key);
				}else{
				$ascii = chr(65).chr(65+($key-26));	 
					}
		 $sheet->setCellValueExplicit($ascii.$i, $row[$val],PHPExcel_Cell_DataType::TYPE_STRING);
               //$sheet->setCellValue($ascii.$i, $row[$val]);
			   
				
            }
     
		
			}
			
        $objPHPExcel->getActiveSheet()->setTitle('选课数据');
        $objPHPExcel->setActiveSheetIndex(0);ob_end_clean() ;
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=course.xlsx");
		  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
            $objWriter->save('php://output');  
			
			die;}	   
		$this->list=$list;
		 $this->show();
		
		}
	public function course_add(){
		$this->actionname='添加';
		$this->action='course_add';
		$bid=intval($_GET['bid']);
		$info['bid']=$bid;
		$this->info=$info;
		$where['uid']= $this->user['id'];
		 $this->bj=model('extendclass')->classes_list($where);
	   $this->teacher=model('extendclass')->teacher_list($where);
		 $this->show('extendclass/course_info');
		}
	public function course_add_save(){
		
		$_POST['uid']= $this->user['id'];
		$_POST['bj_ids']=serialize($_POST['bj_ids']);
		$_POST['starttime']=strtotime($_POST['starttime']);
		$_POST['endtime']=strtotime($_POST['endtime']);
		model('extendclass')->course_add_save($_POST);
    	
    	$this->msg('添加成功！',1);
		
		}
	public function course_edit(){
		$this->actionname='编辑';
		$this->action='course_edit';
		
		 $id=intval($_GET['id']);
        $this->alert_str($id,'int');
		$where['uid']= $this->user['id'];
		 $this->bj=model('extendclass')->classes_list($where);
		  $this->teacher=model('extendclass')->teacher_list($where);
		 $info=model('extendclass')->course_info(array('id'=>$id));
		$info['bj_ids']=unserialize($info['bj_ids']);
	
		$this->info=$info;
		 $this->show('extendclass/course_info');
		}
	
	public function course_edit_save(){
		
	 
		$_POST['bj_ids']=serialize($_POST['bj_ids']);
		$_POST['starttime']=strtotime($_POST['starttime']);
		$_POST['endtime']=strtotime($_POST['endtime']);
		model('extendclass')->course_edit_save($_POST);
    	
    	$this->msg('编辑成功！',1);
		
		}
	public function attendance(){
		
		$cid=intval($_GET['cid']);
		$info=model('extendclass')->course_info(array('id'=>$cid));
		$list=model('extendclass')->attendance_list(array('cid'=>$info['id']));	
		
		$students=model('extendclass')->signup_course_list('A.uid='.$this->user['id'].' and B.cid='.$cid);	
		if($list){
			$teacher=model('extendclass')->teacher_list($where);
				require(CP_PATH . 'ext/PHPExcel.php');
			require(CP_PATH . 'ext/PHPExcel/IOFactory.php');
			$objPHPExcel = new PHPExcel();
	        $objPHPExcel = new PHPExcel(); 
      	  $objPHPExcel->getProperties()->setCreator("PHPExcel")
                                     ->setLastModifiedBy("PHPExcel")
                                     ->setTitle("PHPExcel reports")
                                     ->setSubject("PHPExcel reports")
                                     ->setDescription("PHPExcel document for Office 2003 XLS, generated at ".date('Y-m-d'))
                                     ->setKeywords("PHPExcel reports")
                                     ->setCategory("PHPExcel");
			$keynames=array('序号','学号','姓名');
			foreach($list as $key=>$value){
				$keynames[]=$value['day'];
				}
				
		$keys = array_keys($keynames);
		 $xlsx[] = $keynames;	
		 $i=1;
			foreach($students as $key=>$value){
		$temp=array($i++, $value['codenum'], $value['name']);
		
					foreach($list as $k=>$v){
					$sids=unserialize($v['sids']);
					
					if(in_array($value['id'],$sids))$temp[]='×';
					else $temp[]='√';
					}
				
		$xlsx[]=$temp;
			 }	
			
			 foreach($xlsx as $index => $row){
            $i = $index + 1;
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            foreach($keys as $key => $val){
				if($key<26){
               $ascii = chr(65+$key);
				}else{
				$ascii = chr(65).chr(65+($key-26));	 
					}
		 $sheet->setCellValueExplicit($ascii.$i, $row[$val],PHPExcel_Cell_DataType::TYPE_STRING);
               //$sheet->setCellValue($ascii.$i, $row[$val]);
			   
				
            }
     
		
			}
			
        $objPHPExcel->getActiveSheet()->setTitle('签到数据');
        $objPHPExcel->setActiveSheetIndex(0);ob_end_clean() ;
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=".$info['name']."签到数据.xlsx");
		  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
            $objWriter->save('php://output');  die;
			
			}else{
			$this->alert('暂无签到记录');	
				
				}
		}
	public function course_del(){
		 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->course_del($id,$fid);
      
        $this->msg('删除成功！',1);
		
		}
		
	public function signup(){
		$where['uid']= $this->user['id'];
		  $this->bj=$bj=model('extendclass')->classes_list($where);
		 $cid=intval($_GET['cid']);
        $this->alert_str($cid,'int');
	 $this->info=$info=model('extendclass')->course_info(array('id'=>$cid));
	 
	 if($_POST['signup']){
		 $list=model('extendclass')->signup_course_list('A.uid='.$this->user['id'].' and B.cid='.$cid);	
		if($list){
		 $this->teacher=$teacher=model('extendclass')->teacher_list($where);
				require(CP_PATH . 'ext/PHPExcel.php');
			require(CP_PATH . 'ext/PHPExcel/IOFactory.php');
			$objPHPExcel = new PHPExcel();
	        $objPHPExcel = new PHPExcel(); 
      	  $objPHPExcel->getProperties()->setCreator("PHPExcel")
                                     ->setLastModifiedBy("PHPExcel")
                                     ->setTitle("PHPExcel reports")
                                     ->setSubject("PHPExcel reports")
                                     ->setDescription("PHPExcel document for Office 2003 XLS, generated at ".date('Y-m-d'))
                                     ->setKeywords("PHPExcel reports")
                                     ->setCategory("PHPExcel");
			$keynames=array('姓名','联系手机','学籍号','学号','班级','课程名称','任课老师','上课地点','上课时间','第几节','分数');
		$keys = array_keys($keynames);
		 $xlsx[] = $keynames;	
		foreach($list as $key=>$value){
		$temp=array( $value['name'], $value['mobile'], $value['schoolcode'], $value['codenumber'],  $bj[$value['bj_id']]['grade'].'年级'.$bj[$value['bj_id']]['class'].'班',$value['title'],$teacher[$value['tid']]['name'],$value['place'],$this->times[$value['classtime']],$value['jie'],$value['score']);
		$xlsx[]=$temp;
	
      		  
     	   }	
		   
		   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+1))->setWidth(20);
		     $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+2))->setWidth(30);
			   $objPHPExcel->getActiveSheet()->getColumnDimension( chr(65+3))->setWidth(30);
			 foreach($xlsx as $index => $row){
            $i = $index + 1;
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            foreach($keys as $key => $val){
				if($key<26){
               $ascii = chr(65+$key);
				}else{
				$ascii = chr(65).chr(65+($key-26));	 
					}
		 $sheet->setCellValueExplicit($ascii.$i, $row[$val],PHPExcel_Cell_DataType::TYPE_STRING);
               //$sheet->setCellValue($ascii.$i, $row[$val]);
			   
				
            }
     
		
			}
			
        $objPHPExcel->getActiveSheet()->setTitle('学生数据');
        $objPHPExcel->setActiveSheetIndex(0);ob_end_clean() ;
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=student.xlsx");
		  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
            $objWriter->save('php://output');  
			
			
			
			}	
		die;
			}
	 
	 
	   	if($_FILES['file']['name']){
		$return=module('editor_upload')->upload();
			
			if($return['error'])$this->error($return['msg']);
			$data = new Spreadsheet_Excel_Reader();
			// 设置输入编码 UTF-8/GB2312/CP936等等
			$data->setOutputEncoding('UTF-8');
			$data->read('..'.$return['url']);
			$sheet=$data->sheets[0];
			$rows=$sheet['cells'];
			$temp=array();
		
			foreach($rows as  $key=>$val){
				if($key>1){
						$array=array('name'=>$val[1],'mobile'=>$val[2],'schoolcode'=>$val[5],'codenumber'=>$val[6],'uid'=>$this->user['id']);
						foreach($bj as $k=>$v){
							if($v['grade']==intval($val['3'])&&$v['class']==intval($val['4'])){
								$array['bj_id']=$v['id'];break;
								}
							}
					
					$sid=model('extendclass')->student($array);
							
						$temp=array('cid'=>$cid,'time'=>time(),'sid'=>$sid,'bid'=> $info['bid'],'lock'=>1);
					
						model('extendclass')->signup_add_save($temp);
						}
				
			
				}
		
			}
	 if($_GET['s']){
			 $whereadd=" and A.name like '%".$_GET['s']."%'";
			  }
	  $this->list=model('extendclass')->signup_list('A.uid='.$this->user['id'].' and B.cid='.$cid.$whereadd);
			 $this->show();
		
		}
	public function signup_add(){
		$this->actionname='添加';
		$this->action='signup_add';
		$where['uid']= $this->user['id'];
		$this->bj=model('extendclass')->classes_list($where);
		$this->cid=intval($_GET['cid']);
		 $this->show('extendclass/signup_info');
		}
	public function signup_add_save(){
		foreach($_POST as  $key=>$value){
			$_POST[$key]=trim($value);
			
			}
		$_POST['uid']= $this->user['id'];
		
		
		$sid=model('extendclass')->student($_POST);
		if($sid){ $cid=intval($_GET['cid']);
		 $info=model('extendclass')->course_info(array('id'=>$cid));
			$temp=array('cid'=>$cid,'time'=>time(),'sid'=>$sid,'bid'=> $info['bid'],'lock'=>1);
				model('extendclass')->signup_add_save($temp);
				$this->msg('添加成功！',1);
			}else{
				
			$this->msg('学生信息错误！',0);	
				}
	
    	
    	
		
		}

	public function signup_del(){
	 $id=$_POST['id'];
     
        $this->alert_str($_POST['id'],'int',true);
        model('extendclass')->signup_del($id); 
      
        $this->msg('删除成功！',1);
		
		}
}
?>