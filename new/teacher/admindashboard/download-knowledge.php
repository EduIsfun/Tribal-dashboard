<?php  
error_reporting(0);
include('db_config.php');
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename = download-report.csv');
		
include('model/Download.class.php');
$teacher = new Download();

function outputCSV($data) {
	
	$outstream = fopen("php://output", "a");
	$headers= 'Name, Mobile, Email, Knowledge Currency , School, Chapter, Time';
	$headers.= "\n";
	fwrite($outstream,$headers.$data);
	function __outputCSV(&$vals, $key, $filehandler) {
		fputcsv($filehandler, $vals); // add parameters if you want
	}
array_walk($data, "__outputCSV", $outstream);
fclose($outstream);
}

$state_id = isset($_GET['state_id'])?$_GET['state_id']:'';
$city_id = isset($_GET['city_id'])?$_GET['city_id']:'';
$schoolid =isset($_GET['schoolid'])?$_GET['schoolid']:'';
$classid =isset($_GET['classid'])?$_GET['classid']:'';
$board_id = isset($_GET['board_id'])?$_GET['board_id']:'';
$subject_id = isset($_GET['subject_id'])?$_GET['subject_id']:'';
$chapter_id = isset($_GET['chapter_id'])?$_GET['chapter_id']:'';
$subchapterid = isset($_GET['subchapterid'])?$_GET['subchapterid']:'';
				
$user = $teacher->getknowResult($state_id,$city_id,$board_id,$schoolid,$classid,$subject_id,$chapter_id,$subchapterid);
	$data='';				
while( $userlist = mysqli_fetch_object($user) ){
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
	
	$studentName = $userlist->student_name;
	$studentMobile = $userlist->mobile;
	$studentEmail = $userlist->email;
	$learningKnow = $userlist->knowledgeCurrency;
	$schoolNmae = $userlist->school;
	$ChapterNmae = $userlist->chapter;
	$ChapterID = $userlist->chapterID;
	
	$i=1;
	$rank = 0;
	$overallresult = $teacher->getrankuserResult($classid,$schoolid);
	while($classrank = mysqli_fetch_object($overallresult)){
		if($classrank->userid==$userlist->userID){
			$rank = $i;
		}
		$i++;
	}
	$rank;
										
	$i=1;				
	$globalranks = 0;				
	$globalresult = $teacher->getglobalrankResult($schoolid);	
	while($globalrank = mysqli_fetch_object($globalresult)){						
		if($globalrank->userid==$userlist->userID){						
			$globalranks = $i;					
		}					
		$i++;
	}	
	$timeresult = $teacher->getrankTimestamp($userlist->userID,$ChapterID);			    
	$Totaltime = mysqli_fetch_object($timeresult);
	$totaltime = $Totaltime->time;
	$field_name = $studentName .','. $studentMobile .','. $studentEmail .','. $learningKnow .','. $schoolNmae .','. $ChapterNmae .','. $totaltime .','; 
	if (preg_match('/\\r|\\n|,|"/', $field_name)){
		$field_name = '' . str_replace('','', $field_name) . '';
	}
	$data.=$separate . $field_name;
	$data.="\n";
}
outputCSV($data)		
?>