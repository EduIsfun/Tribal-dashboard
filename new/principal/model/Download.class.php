<?php
class  Download
{
	
	
	public function getuserResult($schoolname,$classid,$state_id,$city_id,$subject_id,$chapter_id,$subtopic){
		
		global $conn;

		$sql ="SELECT  kct.`userID`,u.userID,ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS learningCurrencys, u.name AS student_name,usd.school,usd.grade,u.email,u.mobile FROM `knowledgekombat_chapter_time` kct
		INNER JOIN `user` u ON u.userID = kct .userID
		INNER JOIN user_school_details usd ON kct.`userID`=usd.userID";
		if (!empty($city)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		if (!empty($subtopic)) $sql .= " INNER JOIN knowledgekombat_skill_attempt ksa ON ksa.userID = usd.userID";
		if (!empty($subject_id)) $sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$sql .=" WHERE 1=1 ";
		if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
		
		if (!empty($schoolname)) $sql .= " AND usd.school  = '$schoolname' ";
		if (!empty($classid)) $sql .= " AND usd.grade = '$classid'";
		if (!empty($subtopic)) $sql .= " AND ksa.skillID='$subtopic'";
		if (!empty($chapter_id)) $sql .= " AND kct.chapterID='$chapter_id'";
		if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		$sql .= " AND Date(u.created_timestamp)>='2019-01-01'";
		$sql .= " GROUP BY kct.`userID` ORDER BY u.name DESC";
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		return $result;
		//}
	} 
	
	
	
	/* public function getuserResult($schoolname,$classid,$state_id,$city_id,$subject_id,$subtopic){
		
		global $conn;

		$sql ="SELECT  kct.`userID`,u.userID, u.name AS student_name,usd.school,usd.grade FROM `knowledgekombat_chapter_time` kct
		INNER JOIN `user` u ON u.userID = kct .userID
		INNER JOIN user_school_details usd ON kct.`userID`=usd.userID";
		if (!empty($city)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		if (!empty($subtopic)) $sql .= " INNER JOIN knowledgekombat_skill_attempt ksa ON ksa.userID = usd.userID";
		if (!empty($subject_id)) $sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$sql .=" WHERE 1=1 ";
		if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
		
		if (!empty($schoolname)) $sql .= " AND usd.school  = '$schoolname' ";
		if (!empty($classid)) $sql .= " AND usd.grade = '$classid'";
		if (!empty($subtopic)) $sql .= " AND ksa.skillID='$subtopic'";
		if (!empty($chapter_id)) $sql .= " AND kct.chapterID='$chapter_id'";
		if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		$sql .= " GROUP BY kct.`userID` ";
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		return $result;
		//}
	} */
	
	
	public function getusermainpageResult($schoolname,$state_id,$city_id)
		
	{
			
		global $conn;
		$sql ="SELECT DISTINCT kct.`userID`,u.userID, u.name,usd.school,usd.grade FROM `knowledgekombat_chapter_time` kct
		INNER JOIN `user` u ON u.userID = kct .userID
		INNER JOIN user_school_details usd ON kct.`userID`=usd.userID";
		if (!empty($city_id)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		$sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		$sql .=" WHERE 1=1 ";
		$sql .= " AND usd.grade = 'I'";
		$sql .= " AND c.subjectID='1'";
		$sql .= " AND Date(u.created_timestamp)>='2019-01-01'";
		if (!empty($schoolname)) $sql .= " AND usd.school='$schoolname'";
		if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
		//echo $sql;
		$sql .= " GROUP BY kct.`userID` ";
		$result = mysqli_query($conn, $sql);
		return $result;
			
	}
		
		
		public function getuserResultIDss($schoolname,$state_id,$city_id)
		
		{
			
			global $conn;
			$sql ="SELECT  kct.userID AS userIDs FROM `knowledgekombat_chapter_time` kct
			INNER JOIN `user` u ON u.userID = kct .userID
			INNER JOIN user_school_details usd ON kct.`userID`=usd.userID";
			if (!empty($city_id)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
			if (!empty($state_id)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
			if (!empty($state_id)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
			$sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
			$sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
			$sql .=" WHERE 1=1 ";
			$sql .= " AND usd.grade = 'I'";
			$sql .= " AND c.subjectID='1'";
			$sql .= " AND Date(u.created_timestamp)>='2019-01-01'";
			if (!empty($schoolname)) $sql .= " AND usd.school='$schoolname'";
			if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
			if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
			$sql .= " GROUP BY kct.`userID` ";
			//echo $sql;
			$result = mysqli_query($conn, $sql);
			$user_ids = '';
		
			while( $userlists = mysqli_fetch_assoc($result) ){
			
				if ( $user_ids == '' ) { $user_ids .= $userlists['userIDs']; }
				else { $user_ids .= ','.$userlists['userIDs']; }
			
			}
		
				return $user_ids;		
		
		// return $ids = str_replace ( ' ', '', $result['userIDs'] );
		//}
	}
	
	
	
	public function getrankuserResult($schoolname)
	{
		global $conn;
		$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct 
		INNER JOIN `user_school_details` usd ON kct.`userID`=usd.`userID` WHERE kct.time>0 AND usd.school = '$schoolname'GROUP BY kct.userID ORDER BY rank DESC";
		//echo $query;		
		$result = mysqli_query($conn,$query);
		return $result;
		
	} 
	
	public function getrankTimestamp($userid)
	{
		global $conn;
		$query = "SELECT TIME_FORMAT(SEC_TO_TIME(ROUND(SUM(`time`))),'%HH : %iM : %SS') AS `time`,userID as userid FROM knowledgekombat_chapter_time where userID='".$userid."'  group by userID";
		//echo $query;		
		$result = mysqli_query($conn,$query);
		return $result;
	} 
	
	public function getglobalrankResult($classid,$schoolname)
		
		{
			global $conn;
			$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct 
			INNER JOIN `user_school_details` usd ON kct.`userID`= usd.`userID` WHERE kct.time>0 AND usd.grade = '$classid' AND usd.school='$schoolname'  GROUP BY kct.userID ORDER BY rank DESC";
			//echo $query;		
			$result = mysqli_query($conn,$query);
			return $result;
			
		}
	
	public function getoverallGrade($userid)
	{
		global $conn;
		$query = "SELECT ROUND(SUM(learningCurrency)/COUNT(learningCurrency)) AS currency, 
		userID AS userid,`chapterID` FROM knowledgekombat_chapter_time where userID='".$userid."'
		GROUP BY userID ";
		//echo $query;		
		$result = mysqli_query($conn,$query);
		return $result;
	}
	 
	public function getBoardResult()
	
	{
		global $conn;
		$query = "SELECT  * FROM board";
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	public function getStudentresult($id)
	
	{
		
		global $conn;
		$sql = "SELECT * FROM user WHERE userID = '$id'";
		$studentresult = mysqli_query($conn,$sql);
		return $studentresult;
	}
	

	
}


?>