<?php
session_start();
include('db_config.php');
include('model/Teacher.class.php');
include('model/Dashboard.class.php');

$teacher = new Teacher();
$dashboard = new Dashboard();
	
$sql = "SELECT * FROM teacher_login WHERE userID = '".$_SESSION['uids']."'";
$result = mysqli_query($conn,$sql);
$obj = mysqli_fetch_object($result);
$school_id = $obj->school;
$data = array();
		$res = array();
		$resa1= array();
		$resa2= array();
		$resb1= array();
		$resb2= array();
		$resc1= array();
		$resc2= array();
		$resd= array();
		$rese1= array();
		$rese2= array();	
if (isset($_POST['action']) && ($_POST['action']="getchaptercounts")){
	
		$classid 		= isset($_POST['class_id'])?$_POST['class_id']:'';
		$subject_id 	= isset($_POST['subject_id'])?$_POST['subject_id']:'';
		$schoolname 	= isset($_POST['school_id'])?$_POST['school_id']:'';
		$state_id 		= isset($_POST['state_id'])?$_POST['state_id']:'';
		$city_id 		= isset($_POST['city_id'])?$_POST['city_id']:'';
		//$subtopic 		= isset($_POST['subtopic'])?$_POST['subtopic']:'';
		$chapter_id 	= isset($_POST['chapter_id'])?$_POST['chapter_id']:'';
		/*$chapter_id="10M0005";
		$schoolname="VAGAD PACE GLOBAL SCHOOL SCHOOL";
		$classid="10";*/
		$list_topic = $teacher->getuTopicid($chapter_id);
		$outputs='';
		$outputs.='<div class="container"> 
		<div class="row"> ';		
		while($topiclist = mysqli_fetch_object($list_topic)){
			
		$subtopic=$topiclist->skillID;
		$skill=$topiclist->skill;			
		//$user = $dashboard->getuserResult($classid,$subject_id,$schoolname,$state_id,$city_id,$subtopic);
		$user = $dashboard->getuserResult($chapter_id,$schoolname,$classid,$subject_id,$state_id,$city_id);

		//$user = $dashboard->getuserResult($schoolname,$classid);
		
		$A1_count = 0;
		$A2_count = 0;
		$B1_count = 0;
		$B2_count = 0;
		$C1_count = 0;
		$C2_count = 0;
		$D_count = 	0;
		$E1_count = 0;
		$E2_count = 0;
		$count = 0;
		$coloravg = '';
		while($userlist = mysqli_fetch_object($user)){
				
			//$overallgrade = $teacher->getoverallGrade($userlist->userID);	
			$resultlearning = $dashboard->getlearningResult($subtopic,$userlist->userID);
			$rowlearning = mysqli_fetch_object($resultlearning);	
			if (($rowlearning->learning=="") || ($rowlearning->learning=="0") ) {
				$learning =0;
				$showlearning ="E1";
				$color ="bluer";
				$img_grade = 'images/red.png';
				$showlearningavg = "E1";
				$E2_count++;
			} else {
				$count = $rowlearning->learning;
				if ($count>=90){
					$showlearningavg ="A1";
					$A1_count++;
				}else if (($count>=80) && ($count<90)){
					$showlearningavg ="A2";
					$A2_count++;
				} else if (($count>=70) && ($count<80)){
					$showlearningavg ="B1";
					$B1_count++;
				} else if (($count>=60) && ($count<70)){
					$showlearningavg ="B2";
					$B2_count++;
				} else if (($count>=50) && ($count<60)){
					$showlearningavg ="C1";
					$C1_count++;
				} else if (($count>=40) && ($count<50)){
					$showlearningavg ="C2";
					$C2_count++;
				} else if (($count>=30) && ($count<40)){
					$showlearningavg ="D";
					$D_count++;
				} else if (($count>=0) && ($count<30)){
					$showlearningavg = "E1";
					$E1_count++;
				} 
			}
		}
		if ($A1_count!=""){
			$resa1[] = array("y"=>$A1_count,"label"=>$skill);
		}
		if ($A2_count!=""){
			$resa2[] = array("y"=>$A2_count,"label"=>$skill);
		}
		if ($B1_count!=""){
			$resb1[] = array("y"=>$B1_count,"label"=>$skill);
		}
		if ($B2_count!=""){
			$resb2[] = array("y"=>$B2_count,"label"=>$skill);
		}
		if ($C1_count!=""){
			$resc1[] = array("y"=>$C1_count,"label"=>$skill);
		}
		if ($C2_count!=""){
			$resc2[] = array("y"=>$C2_count,"label"=>$skill);
		}
		if ($D_count!=""){
			$resd[] = array("y"=>$D_count,"label"=>$skill);
		}
		if ($E1_count!=""){
			$rese1[] = array("y"=>$E1_count,"label"=>$skill);
		}
		if ($E2_count!=""){
			$rese2[] = array("y"=>$E2_count,"label"=>$skill );
		}
//	}	
}
if(!empty($resa1)){ 	
	$res[]= array("type"=>"stackedColumn","name"=>"A1","showInLegend"=>"true","yValueFormatString"=> "#,##","dataPoints"=>$resa1,"color"=>'#0f3e0f');
}
if(!empty($resa2)){ 
	$res[] = array("type"=>"stackedColumn","name"=>"A2","showInLegend"=>"true","yValueFormatString"=> "#,##","dataPoints"=>$resa2,"color"=>'#46d246');
}
if(!empty($resb1)){
	$res[] = array("type"=>"stackedColumn","name"=>"B1","showInLegend"=>"true","yValueFormatString"=> "#,##","dataPoints"=>$resb1,"color"=>'#e6e600');
}
if(!empty($resb2)){
	$res[] = array("type"=>"stackedColumn","name"=>"B2","showInLegend"=>"rue","yValueFormatString"=> "#,##","dataPoints"=>$resb2,"color"=>'#ffff00');
}
if(!empty($resc1)){
	$res[] = array("type"=>"stackedColumn","name"=>"C1","showInLegend"=>"true","yValueFormatString"=> "#,##","dataPoints"=>$resc1,"color"=>'#ff9900');
}
if(!empty($resc2)){
	$res[] = array("type"=>"stackedColumn","name"=>"C2","showInLegend"=>"true","yValueFormatString"=> "#,##","dataPoints"=>$resc2,"color"=>'#ffb84d');
}
if(!empty($resd)){
	$res[] = array("type"=>"stackedColumn","name"=>"D","showInLegend"=>"true","yValueFormatString"=> "#,##","dataPoints"=>$resd,"color"=>'#0033cc');
}
if(!empty($rese1)){
	$res[] = array("type"=>"stackedColumn","name"=>"E1","showInLegend"=>"true","yValueFormatString"=> "#,##","dataPoints"=>$rese1,"color"=>'#b32400');
}
if(!empty($rese2)){
	$res[] = array("type"=>"stackedColumn","name"=>"E2","showInLegend"=>"true","yValueFormatString"=> "#,##","dataPoints"=>$rese2,"color"=>'#ff471a');
}

$data[]=$res;
//print_r($data);
echo json_encode($res,true);

die();			
}
?>