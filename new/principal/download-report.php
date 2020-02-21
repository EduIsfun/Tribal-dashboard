<?php  
error_reporting(0);
session_start();
include('db_config.php');
include('functions.php');
//$sql = "SELECT * FROM teacher_login WHERE userID = '".$_SESSION['uids']."'";
$sql = "SELECT tl.*,uas.class_rank,uas.global_rank,uas.alias_rank FROM `teacher_login` tl INNER JOIN `user_admin_settings` uas ON tl.`id`=uas.`user_id` WHERE tl.`userID`= '".$_SESSION['uids']."'";
$result = mysqli_query($conn,$sql);
$obj = mysqli_fetch_object($result);
$mainschool = $obj->main_school;
$schoollist = $mainschool."|". $obj->school;
$schoolarray = str_replace("|", "','",  $schoollist );
//$schoolarray = implode("','",$schoolarr);
$alias = $obj->alias;
$class_rank = $obj->class_rank;
$global_rank = $obj->global_rank;
$alias_rank = $obj->alias_rank;


$downloadtype = isset($_GET['downloadtype'])?$_GET['downloadtype']:'';
$state_id = isset($_GET['state_id'])?$_GET['state_id']:'';
$city_id = isset($_GET['city_id'])?$_GET['city_id']:'';
$schoolname =isset($_GET['schoolname'])?$_GET['schoolname']:'';
$classid =isset($_GET['classid'])?$_GET['classid']:'';
$subject_id =isset($_GET['subject_id'])?$_GET['subject_id']:'';
$subtopic =isset($_GET['subtopic'])?$_GET['subtopic']:'';
$chapter_id =isset($_GET['chapter_id'])?$_GET['chapter_id']:'';
	
if ($downloadtype =="csv"){
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment;filename = download-report_'.time().'.csv');
	$outstream = fopen("php://output", "a");
	$headers= 'Class, Name';
	if ($class_rank=="on"){
		$headers.= ', Class Rank';
	}	
	if ($global_rank=="on"){
		$headers.= ', Global Rank';
	}	
	if ($alias_rank=="on"){
		$headers.= ', '.$alias.' Rank';
	}	
	$headers.= ', Time Spent,Overall Grade';
	$headers.= "\n";
	fwrite($outstream,$headers);
	function __outputCSV(&$vals, $key, $filehandler) {
		fputcsv($filehandler, $vals); // add parameters if you want
	}
	array_walk($data, "__outputCSV", $outstream);
	fclose($outstream);
	//outputCSV($data);
}
if ($downloadtype =="xls"){
	header('Content-Disposition: attachment; filename=download-report_'.time().'.xls');
	header('Content-Type: application/vnd.ms-excel');
	//outputCSV($data);
}	
if ($downloadtype =="pdf"){
	$htmls.='<!DOCTYPE html><html lang="en"><head><title> Dashboard PDF</title>
	<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
	<body><div style="width:100%;max-width:992pt;display:block;margin:0 auto;font-family: "Lato", sans-serif;">
	<table style="width:100%;" cellpadding="0" cellspacing="5" border="1px solid #000">
		<tbody>
			<tr> 
				<td style="text-align:left;width:15%">Class</td>
				<td style="text-align:left;width:15%">Name</td>';
				if ($class_rank=="on"){
					$htmls.= '<td style="text-align: left;width:15%">Class Rank</td>';
				}	
				if ($global_rank=="on"){
				$htmls.= '<td style="text-align: left;width:15%">Global Rank</td>';
				}	
				if ($alias_rank=="on"){
					$htmls.= '<td style="text-align: left;width:15%">'.$alias.' Rank</th>';
				}	
				$htmls.= '<td style="text-align: left;width:15%">Time Spent</td>  
				<td style="text-align: left;width:15%">Overall Grade</td>
			</tr>';
}			
include("pdf/mpdf.php");	
include('model/Teacher.class.php');
include('model/Dashboard.class.php');
include('model/Download.class.php');
//$teacher = new Download();
$teacher = new Teacher();

	
$gradeid = Romannumeraltonumber($classid);
$overallresult = $teacher->getrankuserResult($gradeid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id);
$classrankar =  array();
$totalcrank =0;
while($classrank = mysqli_fetch_object($overallresult)){
	$classrankar[]=array('userid'=>$classrank->userid,'rank'=>$classrank->rank);
	$totalcrank++;
}	
		
$globalresult = $teacher->getglobalrankResult($gradeid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id);	
$globalrankar =  array();
$totalgrank =0;
while($globalrank = mysqli_fetch_object($globalresult)){
	$globalrankar[]=array('userid'=>$globalrank->userid,'rank'=>$globalrank->rank);
	$totalgrank++;
}

$aliasresult = $teacher->getaliasrankResult($gradeid,$schoolarray,$city_id,$chapter_id,$subtopic,$subject_id);	
$aliasrankar =  array();
$totalarank =0;
while($aliasrank = mysqli_fetch_object($aliasresult)){
	$aliasrankar[]=array('userid'=>$aliasrank->userid,'rank'=>$aliasrank->rank);
	$totalarank++;
}
			
			
$user = $teacher->getuserResult($classid,$subject_id,$schoolname,$state_id,$city_id,$chapter_id,$subtopic);
while($userlist = mysqli_fetch_object($user)){
	$learnincurrancy =0;
	$coloravg='';
	$img_grade = '';
	$overallgrade = $teacher->getoverallGrade($userlist->userID);	
	$allgrade = mysqli_fetch_object($overallgrade);	
	$learnincurrancy =$allgrade->currency;
	if (mysqli_num_rows($overallgrade)>0) {
		if (($allgrade->currency=="") || ($allgrade->currency=="0")) {
			$learnincurrancy =0;
			$showlearningavg ="E1";
			$coloravg ="redr";
            $img_grade = 'images/red.png';									
        } else {
			if ($learnincurrancy>=90){
				$showlearningavg ="A1";
				$coloravg ="greenr";
				$img_grade = 'images/green.png';	
			} else if (($learnincurrancy>=80) && ($learnincurrancy<90)){
				$showlearningavg ="A2";
				$coloravg ="greenr";
				$img_grade = 'images/green.png';	
			} else if (($learnincurrancy>=70) && ($learnincurrancy<80)){
				$showlearningavg ="B1";
				$coloravg ="yellowr";
				$img_grade = 'images/yellow.png';
			} else if (($learnincurrancy>=60) && ($learnincurrancy<70)){
				$showlearningavg ="B2";
				$coloravg ="yellowr";
				$img_grade = 'images/yellow.png';
			} else if (($learnincurrancy>=50) && ($learnincurrancy<60)){
				$showlearningavg ="C1";
				$coloravg ="blue";
				$img_grade = 'images/blue.png';
			} else if (($learnincurrancy>=40) && ($learnincurrancy<50)){
				$showlearningavg ="C2";
				$coloravg ="blue";
				$img_grade = 'images/blue.png';
			} else if (($learnincurrancy>=30) && ($learnincurrancy<40)){
				$showlearningavg ="D";
				$coloravg ="blue";
				$img_grade = 'images/blue.png';
			} else if (($learnincurrancy>=20) && ($learnincurrancy<30)){
				$showlearningavg ="E1";
				$coloravg ="redr";
				$img_grade = 'images/red.png';
			} else if (($learnincurrancy>=0) && ($learnincurrancy<20)){
				$showlearningavg ="E1";
				$coloravg ="redr";
				$img_grade = 'images/red.png';
			}
		}
	}
	$class = $userlist->grade;
	$studentName = $userlist->name;
	$studentMobile = $userlist->mobile;
	$studentEmail = $userlist->email;
	$studentCurrancy = $userlist->rank;
	
	$globalrankskey = array_search($userlist->userID, array_column($globalrankar, 'userid'));
	$globalranks =$globalrankar[$globalrankskey]['rank'];
							
	$classrankkey = array_search($userlist->userID, array_column($classrankar, 'userid'));
	$classrankVAL =$classrankar[$classrankkey]['rank'];

	$aliasrankkey = array_search($userlist->userID, array_column($aliasrankar, 'userid'));
	$aliasranks =$aliasrankar[$aliasrankkey]['rank'];
			
	$timeresult = $teacher->getrankTimestamp($userlist->userID);			    
	$Totaltime = mysqli_fetch_object($timeresult);
	$totaltime = $Totaltime->time;
	if ($downloadtype =="csv"){
		$field_name = $class.','. $studentName ;
		
		if ($class_rank=="on"){
			$field_name.=','. $classrankVAL;
		}
		if ($global_rank=="on"){
			$field_name.=','. $globalranks ;
		}	
		if ($alias_rank=="on"){
			$field_name.=','. $aliasranks ;
		}	
		
		$field_name.=','. $totaltime .','. $showlearningavg .','; 
									
		if (preg_match('/\\r|\\n|,|"/', $field_name)){
			$field_name = '' . str_replace('','', $field_name) . '';
		}
		echo $separate . $field_name;
		echo "\n";
	}
	if ($downloadtype =="xls"){
		
		if ($class_rank=="on"){
			$classrank=array(' Class Rank'=>$classrankVAL );
		}	else {
			$classrank=array();
		}	
		if ($global_rank=="on"){
			$globalrank=array('Global Rank'=>$globalranks) ;
		}	else {
			$globalrank=array();
		}
		if ($alias_rank=="on"){
			$aliasrank=array($alias.' Rank ' =>$aliasranks) ;
		}	else {
			$aliasrank=array();
		}
		$data1 = array('Class'=>$class,'Name'=>$studentName);
		$data2 = array('Time Spent'=>$totaltime,'Overall Grade'=>$showlearningavg); 
		$data[] =array_merge($data1,$classrank,$globalrank,$aliasrank,$data2 );
	}
	if ($downloadtype =="pdf"){
		$htmls.='<tr> 
				<td style="text-align: left;">'.$class.'</td>
				<td style="text-align: left;">'.$studentName.'</td>';
				if ($class_rank=="on"){
					$htmls.='<td style="text-align: left;">'.$classrankVAL.'</td> ';
				}	
				if ($global_rank=="on"){
					$htmls.='<td style="text-align: left;">'.$globalranks.'</td>';
				}
				if ($alias_rank=="on"){
					$htmls.='<td style="text-align: left;">'.$aliasranks.'</td>';
				}
			
				$htmls.='<td style="text-align: left;">'.$totaltime.'</td>  
				<td style="text-align: left;">'.$showlearningavg.'</td>
				</tr>';
	}	
}
if ($downloadtype =="pdf"){
	$htmls.='</tbody></table>';
	$mpdf = new mPDF ('c','A4','','',10,10,10,10,8); 
	$mpdf->AddPage('L');
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($htmls);
	$file= rand().$m_code.".pdf";
	$mpdf->Output($file,'D');	
}	
if ($downloadtype =="xls"){
	foreach($data as $row) {
		if(!$flag) {
		  // display field/column names as first row
		  echo implode("\t", array_keys($row)) . "\r\n";
		  $flag = true;
		}
		echo implode("\t", array_values($row)) . "\r\n";
	}
}	
  
function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }	
?>