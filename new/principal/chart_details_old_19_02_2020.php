<?php
	session_start();
	error_reporting(0);
	//error_reporting(E_ALL);
    //ini_set('display_errors', 1);
	include('db_config.php');
	include('model/Teacher.class.php');
	include('model/Dashboard.class.php');
	
	$teacher = new Teacher();
	$dashboard = new Dashboard();

	// echo $_REQUEST['action'];
	// die(__file__);
		
	if (isset($_REQUEST['action']) && ($_REQUEST['action']== "getChartDetails" )){
	
		$classid 		= isset($_POST['classid'])?$_POST['classid']:'';
		$subject_id 	= isset($_POST['subject_id'])?$_POST['subject_id']:1;
		//$schoolname 	= isset($_POST['school_id'])?$_POST['school_id']:'';
		$schoollist 	= isset($_POST['school_id'])?$_POST['school_id']:'';
		$schoolname 	= str_replace("|", "','",  $schoollist );
		$state_id 		= isset($_POST['state_id'])?$_POST['state_id']:'';
		$city_id 		= isset($_POST['city_id'])?$_POST['city_id']:'';
		$subtopic 		= isset($_POST['subtopic'])?$_POST['subtopic']:'';
		$chapter_id 	= isset($_POST['chapter_id'])?$_POST['chapter_id']:'';
		
		
		$A1_count = 0;
		$A2_count = 0;
		$B1_count = 0;
		$B2_count = 0;
		$C1_count = 0;
		$C2_count = 0;
		$D_count = 	0;
		$E1_count = 0;
		$E2_count = 0;
					
		//$user = $teacher->getuserResult($classid,$subject_id,$schoolname,$state_id,$city_id,$subtopic);
		$user = $teacher->getuserResult($classid,$subject_id,$schoolname,$state_id,$city_id,$chapter_id,$subtopic);
		// print_r($user);
		// exit;

		while($row = $user->fetch_assoc())
		{
			$set[] = $row['userID'];
			$userRecordArr[$row['userID']] = $userRecord;
		}

		$imploded = implode("','", $set);

		// print_r($imploded);
		// exit;
		$user->data_seek(0); 

		//print_r($imploded);

		if($subtopic!="")
		{
			// $overallgrade =$teacher->getlearningResultAverage($chapter_id,$schoolname,$classid,$subject_id,$state_id,$city_id,$subtopic,$userlist->userID);
		}
		else
		{
			$overallgrade = $teacher->getoverallGradeNew($subtopic,$subject_id, $imploded, $classid);	
			// print_r($overallgrade);
			// exit;
			while($allgrade = mysqli_fetch_object($overallgrade)){

				if (mysqli_num_rows($overallgrade)>0) {
					
					if ($subtopic!=""){
						$learnincurrancy = $allgrade->learningavg;
					} else {
						$learnincurrancy = $allgrade->currency;
					}	
					if (($learnincurrancy=="") || ($learnincurrancy=="0")) {
						$showlearningavg ="E2";
						$E2_count++;					
					} else {
						if ($learnincurrancy>=90){
							$showlearningavg ="A1";
							$A1_count++;	
						} else if (($learnincurrancy>=80) && ($learnincurrancy<90)){
							$showlearningavg ="A2";
							$A2_count++;	
						} else if (($learnincurrancy>=70) && ($learnincurrancy<80)){
							$showlearningavg ="B1";
							$B1_count++;	
						} else if (($learnincurrancy>=60) && ($learnincurrancy<70)){
							$showlearningavg ="B2";
							$B2_count++;	
						} else if (($learnincurrancy>=50) && ($learnincurrancy<60)){
							$showlearningavg ="C1";
							$C1_count++;	
						} else if (($learnincurrancy>=40) && ($learnincurrancy<50)){
							$showlearningavg ="C2";
							$C2_count++;	
						} else if (($learnincurrancy>=30) && ($learnincurrancy<40)){
							$showlearningavg ="D";
							$D_count++;	
						} else if (($learnincurrancy>=20) && ($learnincurrancy<30)){
							$showlearningavg ="E1";
							$E1_count++;	
						} else if (($learnincurrancy>=0) && ($learnincurrancy<20)){
							$showlearningavg ="E2";
							$E2_count++;	
						} 
					}
				} else {
					
					$showlearningavg ="E2";
					$E2_count++;		
				}	
			}
	
			$result = array(
				'A1' => $A1_count,
				'A2' => $A2_count,
				'B1' => $B1_count,
				'B2' => $B2_count,
				'C1' => $C1_count,
				'C2' => $C2_count,
				'D' => $D_count,
				'E1' => $E1_count,
				'E2' => $E2_count
			);
			 
			echo json_encode($result);
			die();
			exit;
		}

		//print_r(mysqli_fetch_assoc($overallgrade));

		

		while($userlist = mysqli_fetch_object($user)){
				
			$count = 0;
			$coloravg = '';
			if ($subtopic!=""){
				$overallgrade =$teacher->getlearningResultAverage($chapter_id,$schoolname,$classid,$subject_id,$state_id,$city_id,$subtopic,$userlist->userID);	
			} else {	
				$overallgrade = $teacher->getoverallGrade($userlist->userID,$subtopic,$subject_id);	
			}	
			$allgrade = mysqli_fetch_object($overallgrade);	
			
			if (mysqli_num_rows($overallgrade)>0) {
				
				if ($subtopic!=""){
					$learnincurrancy = $allgrade->learningavg;
				} else {
					$learnincurrancy = $allgrade->currency;
				}	
				if (($learnincurrancy=="") || ($learnincurrancy=="0")) {
					$showlearningavg ="E2";
					$E2_count++;					
				} else {
					if ($learnincurrancy>=90){
						$showlearningavg ="A1";
						$A1_count++;	
					} else if (($learnincurrancy>=80) && ($learnincurrancy<90)){
						$showlearningavg ="A2";
						$A2_count++;	
					} else if (($learnincurrancy>=70) && ($learnincurrancy<80)){
						$showlearningavg ="B1";
						$B1_count++;	
					} else if (($learnincurrancy>=60) && ($learnincurrancy<70)){
						$showlearningavg ="B2";
						$B2_count++;	
					} else if (($learnincurrancy>=50) && ($learnincurrancy<60)){
						$showlearningavg ="C1";
						$C1_count++;	
					} else if (($learnincurrancy>=40) && ($learnincurrancy<50)){
						$showlearningavg ="C2";
						$C2_count++;	
					} else if (($learnincurrancy>=30) && ($learnincurrancy<40)){
						$showlearningavg ="D";
						$D_count++;	
					} else if (($learnincurrancy>=20) && ($learnincurrancy<30)){
						$showlearningavg ="E1";
						$E1_count++;	
					} else if (($learnincurrancy>=0) && ($learnincurrancy<20)){
						$showlearningavg ="E2";
						$E2_count++;	
					} 
				}
			} else {
				
				$showlearningavg ="E2";
				$E2_count++;		
			}	
		}	
		/* 
		$result = array( 
			array("label"=>"A1", "y"=>$A1_count),
			array("label"=>"A2", "y"=>$A2_count),
			array("label"=>"B1", "y"=>$B1_count),
			array("label"=>"B2", "y"=>$B2_count),
			array("label"=>"C1", "y"=>$C1_count),
			array("label"=>"C2", "y"=>$C2_count),
			array("label"=>"D", "y"=>$D_count),
			array("label"=>"E1", "y"=>$E1_count)
		);
		 */
		$result = array(
				'A1' => $A1_count,
				'A2' => $A2_count,
				'B1' => $B1_count,
				'B2' => $B2_count,
				'C1' => $C1_count,
				'C2' => $C2_count,
				'D' => $D_count,
				'E1' => $E1_count,
				'E2' => $E2_count
			);
			 
		echo json_encode($result);
		die();
	}

?>