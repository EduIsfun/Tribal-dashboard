<?php
class Dashboard
{
	function getschoolResult(){

		global $conn;
		$query = "SELECT DISTINCT school FROM user_school_details ORDER BY school ASC";
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	function getclassResult(){
		global $conn;
		$query = "SELECT  grade FROM user_school_details GROUP BY grade";
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	function getchapterResult($cond){
		global $conn;
		//$query = "SELECT *  FROM `chapter` WHERE $cond order by chapter asc";
		$query = "SELECT DISTINCT  chapter,kct.`chapterID` AS chapterID FROM `knowledgekombat_chapter_time` kct INNER JOIN `chapter` c ON kct.`chapterID` = c.`chapterID`  WHERE $cond and kct.`learningCurrency` > 0 ORDER BY chapter ASC";
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	// function getuserResult($schoolid,$classid){
	// 	global $conn;
	// 	$query = "SELECT name,u.userID,u.mobile,u.email FROM user u INNER JOIN user_school_details usd on u.userID = usd.userID ";
	// 	$query .=" WHERE 1=1";
	// 	if (!empty($schoolid)) $query .= " AND usd.school  = '$schoolid' ";
	// 	if (!empty($classid)) $query .= " AND usd.grade = '$classid'";
		
	// 	//where LOWER(usd.school)='$schoolid' and usd.grade_id='$classid' ";
	// 	$result = mysqli_query($conn,$query);
	// 	return $result;
	// }
	
	//  $schoolid,$classid,$subchapterid,$state,$city,$board_id,$chapter_id,$subject_id
	public function getuserResult($school,$class_id,$chapter_id){
		
		global $conn;
		$sql="SELECT u.userID,u.name,usd.grade,usd.school FROM knowledgekombat_skill_attempt as ksa 
		INNER JOIN user as u ON u.userID=ksa.userID
		INNER JOIN user_school_details as usd ON usd.userID=u.userID
		WHERE ksa.skillID IN (SELECT skillID FROM knowledgekombat_skill WHERE chapterID='".$chapter_id."')  AND usd.school='".$school."' AND usd.grade='".$class_id."' GROUP BY ksa.userID";
		// $sql ="SELECT  kct.`userID`,u.userID, u.name AS student_name,usd.school,usd.grade FROM `knowledgekombat_chapter_time` kct
		// INNER JOIN `user` u ON u.userID = kct .userID
		// INNER JOIN user_school_details usd ON kct.`userID`=usd.userID";
		// if (!empty($city)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		// //if (!empty($state)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		// //if (!empty($state)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		// if (!empty($chapter_id)) $sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		// if (!empty($subchapterid)) $sql .= " INNER JOIN knowledgekombat_skill_attempt ksa ON ksa.userID = usd.userID";
		// if (!empty($subject_id)) $sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		// $sql .=" WHERE 1=1 ";
		// //if (!empty($state)) $sql .= " AND tbl_states.id='$state'";
		// if (!empty($city)) $sql .= " AND usd.city_id='$city'";
		
		// if (!empty($schoolid)) $sql .= " AND usd.school  = '$schoolid' ";
		// if (!empty($classid)) $sql .= " AND usd.grade = '$classid'";
		// if (!empty($subchapterid)) $sql .= " AND ksa.skillID='$subchapterid'";
		// if (!empty($chapter_id)) $sql .= " AND kct.chapterID='$chapter_id'";
		// if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		// if ($board_id=='All') $sql .= " AND usd.board_id IS NULL";
		// elseif(!empty($board_id))$sql .= " AND usd.board_id='$board_id'";
	
		// $sql .= " GROUP BY kct.`userID`";
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		return $result;
	}
	
	function getuserResultIDs($schoolid,$classid){
		global $conn;
		$query = "SELECT name,u.userID AS userIDs FROM user u INNER JOIN user_school_details usd on u.userID = usd.userID ";
		$query .=" WHERE 1=1";
		if (!empty($schoolid)) $query .= " AND usd.school  = '$schoolid' ";
		if (!empty($classid)) $query .= " AND usd.grade = '$classid'";
		//where LOWER(usd.school)='$schoolid' and usd.grade_id='$classid' ";
		$result = mysqli_query($conn,$query);
		$user_ids = '';
		
		while( $userlists = mysqli_fetch_assoc($result) ){
			
			if ( $user_ids == '' ) { $user_ids .= $userlists['userIDs']; }
			else { $user_ids .= ','.$userlists['userIDs']; }
			
		}
		
		return $user_ids;		
		
		// return $ids = str_replace ( ' ', '', $result['userIDs'] );
		//}
	}

	function getskillResult($chapterid){
		global $conn;
	    $query = "SELECT chapterID ,skillID,skill FROM knowledgekombat_skill WHERE chapterID='$chapterid'";
	    // echo $query;
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	function getlearningResult($skillid,$userid){
		global $conn;
		//$query = "SELECT nk.skillID,nk.chapterID,nk.skill,u.userID,MAX(nka.timestamp), nka.`learningCurrency` AS learning FROM `knowledgekombat_skill_attempt` nka INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID INNER JOIN `user` u ON u.userID = nka.userID WHERE nk.skillID='".$skillid."' and u.userID='".$userid."' ORDER BY MAX(nka.timestamp) DESC LIMIT 0,1";

		$query = "SELECT nk.skillID,nk.chapterID,nk.skill,nka.userID,nka.learningCurrency AS learning 
		FROM knowledgekombat_skill_attempt nka 
		INNER JOIN knowledgekombat_skill nk ON nk.skillID=nka.skillID 
		WHERE nk.skillID='".$skillid."' AND nka.userID='".$userid."' AND nka.learningCurrency>0";
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	function getlearningResultAverage($state,$city_id,$board_id,$schoolid,$classid,$skillid,$subject_id,$chapter_id){
		global $conn;
		$query = "SELECT  sum(nka.`learningCurrency`)/count(*) AS learningavg FROM `knowledgekombat_skill_attempt` nka 
		INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID 
		INNER JOIN `user` u ON u.userID = nka.userID 
		INNER JOIN user_school_details usd ON u.userID = usd.userID ";
		
		/*$query = "SELECT  nka.`learningCurrency` AS learningavg >0 ,nka.userID, MAX(nka.updated_timestamp), nka.`learningCurrency`,u.`name` FROM `knowledgekombat_skill_attempt` nka INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID INNER JOIN `user` u ON u.userID = nka.userID INNER JOIN user_school_details usd ON u.userID = usd.userID";*/
		
		if (!empty($city_id)) $query .= " INNER JOIN tbl_cities ON tbl_cities.id = usd.city_id";
		//if (!empty($state)) $query .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		//if (!empty($state)) $query .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		//if (!empty($chapter_id)) $query .= " INNER JOIN knowledgekombat_skill ks on nk.chapterID = ks.chapterID";
		if (!empty($subject_id)) $query .= " INNER JOIN chapter c ON c.`chapterID`= nk.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		//if (!empty($state)) $query .= " AND tbl_states.id='$state'";
		if (!empty($city_id)) $query .= " AND usd.city_id='$city_id'";
		if (!empty($schoolid)) $query .= " AND usd.school  = '$schoolid' ";
		if (!empty($classid)) $query .= " AND usd.grade = '$classid'";
		if (!empty($skillid)) $query .= " AND nk.skillID='".$skillid."'";
		if (!empty($chapter_id)) $query .= " AND nk.chapterID='$chapter_id'";
		if (!empty($subject_id)) $query .= " AND c.subjectID='$subject_id'";
		if ($board_id=='All') $query .= " AND usd.board_id IS NULL";
		elseif(!empty($board_id))$query .= " AND usd.board_id='$board_id'";
		$query .=" WHERE 1=1";
		$query .=" AND nka.`learningCurrency`>0"; 
		$query .=" GROUP BY nk.skillID ";
		$query .=" ORDER BY nka.updated_timestamp  DESC";
		//echo $query."<br><br>";
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	/*=========== Dashboard 2  ========    */
	
	
	
	
	// function getrankuserResult($skillid,$userid){
			// global $conn;
			// echo $query = "SELECT nk.skillID,nk.chapterID,nk.skill,u.userID,nka.updated_timestamp,nka.userID ,ROUND(SUM(nka.learningCurrency)/COUNT(nka.learningCurrency) ) AS  rank FROM `knowledgekombat_skill_attempt` nka 
			// INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID 
			// INNER JOIN `user` u ON u.userID = nka.userID WHERE nk.skillID='".$skillid."' AND u.userID='".$userid."'   
			// GROUP BY nka.userID ORDER BY rank DESC";
           //echo $query;		
			// $result = mysqli_query($conn,$query);
			// return $result;
	// }
	
	
		function getrankuserResult($schoolid){
				global $conn;
				$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct 
				INNER JOIN `user_school_details` usd ON kct.`userID`=usd.`userID` WHERE kct.time>0 AND usd.school = '$schoolid' GROUP BY kct.userID ORDER BY rank DESC";
				//echo $query;		
				$result = mysqli_query($conn,$query);
				return $result;
		}
		
		function getglobalrankResult($userid,$classid){
				global $conn;
				$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct 
				INNER JOIN `user_school_details` usd ON kct.`userID`=usd.`userID` WHERE kct.time>0 AND usd.grade = '$classid' GROUP BY kct.userID ORDER BY rank DESC";
				//echo $query;		
				$result = mysqli_query($conn,$query);
				return $result;
		}
		
		function getrankTimestamp($userid){
				global $conn;
				$query = "SELECT TIME_FORMAT(SEC_TO_TIME(ROUND(SUM(`time`))),'%HH : %iM : %SS') AS `time`,userID as userid FROM knowledgekombat_chapter_time where userID='".$userid."'  group by userID";
				//echo $query;		
				$result = mysqli_query($conn,$query);
				return $result;
		}
		
		
		function getoverallGrade($userid){
				global $conn;
				$query = "SELECT ROUND(SUM(learningCurrency)/COUNT(learningCurrency)) AS currency, 
				 userID AS userid,`chapterID` FROM knowledgekombat_chapter_time where userID='".$userid."'
				 GROUP BY userID ";
				//echo $query;		
				$result = mysqli_query($conn,$query);
				return $result;
		}
		
		function getsubjectResult($cond){
				global $conn;
				$query = "SELECT *  FROM `chapter` WHERE $cond order by chapter asc";
				$result = mysqli_query($conn,$query);
				return $result;
		}
	
}
?>