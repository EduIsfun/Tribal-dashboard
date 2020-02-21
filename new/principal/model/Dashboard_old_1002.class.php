<?php
class Dashboard
{
	
	
	function getuserResult($chapter_id,$schoolname,$classid,$subject_id,$state_id,$city_id)
	
	{
		
		global $conn;
		$query = "SELECT DISTINCT(u.name),u.userID,u.mobile,u.email,usd.school FROM `knowledgekombat_skill_attempt` nka 
		INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID
		INNER JOIN `user` u ON u.userID = nka.userID 
		INNER JOIN user_school_details usd ON u.userID = usd.userID  ";
		if(!empty($city_id)) $query .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $query .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`
		INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $query .= " INNER JOIN knowledgekombat_skill ks on nk.chapterID = ks.chapterID";
		if (!empty($subject_id)) $query .= " INNER JOIN chapter c ON c.`chapterID`= nk.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$query .=" WHERE 1=1";
		$sql .= " AND Date(u.created_timestamp)>='2019-01-01'";
		if (!empty($state_id)) $query .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $query .= " AND usd.city_id='$city_id'";
		if (!empty($classid)) $query .= " AND usd.grade = '$classid'";
		if (!empty($subject_id)) $query .= " AND c.subjectID='$subject_id'";
		if (!empty($chapter_id))$query .= " AND nk.chapterID='$chapter_id'";
		if (!empty($schoolname))$query .= " AND usd.school IN ( '$schoolname') "; 
		$query.="GROUP BY u.userID";
		//echo $query;
		$result = mysqli_query($conn,$query);
		return $result;


	}
	
	
	function getuserResultIDs($chapter_id,$schoolname,$classid,$subject_id,$state_id,$city_id)
	
	{
		
		global $conn;
		$query = "SELECT u.userID AS userIDs FROM `knowledgekombat_skill_attempt` nka 
		INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID
		INNER JOIN `user` u ON u.userID = nka.userID 
		INNER JOIN user_school_details usd ON u.userID = usd.userID  ";
		if(!empty($city_id)) $query .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $query .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`
		INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $query .= " INNER JOIN knowledgekombat_skill ks on nk.chapterID = ks.chapterID";
		if (!empty($subject_id)) $query .= " INNER JOIN chapter c ON c.`chapterID`= nk.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$query .=" WHERE 1=1";
			$sql .= " AND Date(u.created_timestamp)>='2019-01-01'";
		if (!empty($state_id)) $query .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $query .= " AND usd.city_id='$city_id'";
		if (!empty($classid)) $query .= " AND usd.grade = '$classid'";
		if (!empty($subject_id)) $query .= " AND c.subjectID='$subject_id'";
		if (!empty($chapter_id))$query .= " AND nk.chapterID='$chapter_id'";
		if (!empty($schoolname))$query .= " AND usd.school IN ( '$schoolname') "; 
		$query.="GROUP BY u.userID";
		//echo $query;
		$result = mysqli_query($conn,$query);
		$user_ids = '';
		
		while( $userlists = mysqli_fetch_assoc($result) ){
			
			if ( $user_ids == '' ) { $user_ids .= $userlists['userIDs']; }
			else { $user_ids .= ','.$userlists['userIDs']; }
			
		}
		
		return $user_ids;		
	}



	function getChapterid($chapter_name)
	
	{
		global $conn;
		$query = "SELECT * FROM chapter WHERE chapter='$chapter_name'";
		$result = mysqli_query($conn,$query);
		return $result;
	}

	function getskillResult($chapterid)
	
	{
		global $conn;
		$query = "SELECT chapterID ,skillID,skill FROM knowledgekombat_skill WHERE chapterID='$chapterid'";
		$result = mysqli_query($conn,$query);
		return $result;
	}

	function getlearningResult($skillid,$userid)
	
	{
		
		global $conn;
		$query = "SELECT nk.skillID,nk.chapterID,nk.skill,u.userID,nka.timestamp, 
		nka.`learningCurrency` AS learning FROM `knowledgekombat_skill_attempt` nka INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID INNER JOIN `user` u ON u.userID = nka.userID WHERE nk.skillID='".$skillid."' and u.userID='".$userid."' ORDER BY nka.timestamp DESC LIMIT 0,1";
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	
	function getGradecount($skillid,$learningCurrency)
	
	{

		global $conn;
		$query ="SELECT COUNT(learningCurrency) FROM knowledgekombat_skill_attempt WHERE skillID='$skillid' AND learningCurrency>='$learningCurrency' ";
		$result = mysqli_query($conn,$query);
		return $result;
	}	


	function getlearningResultAverage($chapter_id,$schoolname,$classid,$subject_id,$state_id,$city_id,$skillid){
		
		global $conn;
		$query = "SELECT  nka.`learningCurrency` AS learningavg FROM `knowledgekombat_skill_attempt` nka INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID INNER JOIN `user` u ON u.userID = nka.userID INNER JOIN user_school_details usd ON u.userID = usd.userID ";

		if (!empty($city_id)) $query .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $query .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`
		INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $query .= " INNER JOIN knowledgekombat_skill ks on nk.chapterID = ks.chapterID";
		if (!empty($subject_id)) $query .= " INNER JOIN chapter c ON c.`chapterID`= nk.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$query .=" WHERE 1=1";
		if (!empty($state_id)) $query .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $query .= " AND usd.city_id='$city_id'";
		if (!empty($classid)) $query .= " AND usd.grade = '$classid'";
		if (!empty($subject_id)) $query .= " AND c.subjectID='$subject_id'";
		if (!empty($chapter_id))$query .= " AND nk.chapterID='$chapter_id'";
		if (!empty($skillid))$query .= " AND nka.skillID='$skillid'";
		if (!empty($schoolname))$query .= " AND usd.school IN ( '$schoolname') "; 
		$query .= " ORDER BY MAX(nka.timestamp) DESC LIMIT 0,1";
		//$query .=" AND nka.`learningCurrency`>0";
		//$query .=" GROUP BY nk.skillID ";
		//$query .=" ORDER BY nka.timestamp  DESC";
		//echo $query;
		//echo "<br>";
		$result = mysqli_query($conn,$query);
		return $result;
		

	}


}

	
?>