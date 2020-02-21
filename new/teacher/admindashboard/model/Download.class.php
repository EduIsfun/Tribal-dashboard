<?php
class  Download
{
	
	
	public function getuserResult($state_id,$city_id,$board_id,$schoolid,$classid,$subject_id,$chapter_id,$subchapterid){
		
		global $conn;

		$sql ="SELECT  kct.`userID`, ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS learningCurrency ,u.userID, u.name AS student_name,usd.school,usd.grade,u.mobile,u.email FROM `knowledgekombat_chapter_time` kct
		INNER JOIN `user` u ON u.userID = kct .userID
		INNER JOIN user_school_details usd ON kct.`userID`=usd.userID";
		if (!empty($city)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		if (!empty($subchapterid)) $sql .= " INNER JOIN knowledgekombat_skill_attempt ksa ON ksa.userID = usd.userID";
		if (!empty($subject_id)) $sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$sql .=" WHERE 1=1 ";
		if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
		
		if (!empty($schoolid)) $sql .= " AND usd.school  = '$schoolid' ";
		if (!empty($classid)) $sql .= " AND usd.grade = '$classid'";
		if (!empty($subchapterid)) $sql .= " AND ksa.skillID='$subchapterid'";
		if (!empty($chapter_id)) $sql .= " AND kct.chapterID='$chapter_id'";
		if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		if ($board_id=='All') $sql .= " AND usd.board_id IS NULL";
		elseif(!empty($board_id))$sql .= " AND usd.board_id='$board_id'";
	
		$sql .= " GROUP BY kct.`userID` ";
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		return $result;
		//}
	}
	
	public function getuserResultIDs($schoolid,$classid,$subchapterid,$state,$city,$board_id,$chapter_id,$subject_id){
		
		global $conn;

		$sql ="SELECT kct.userID AS userIDs FROM `knowledgekombat_chapter_time` kct
		INNER JOIN `user` u ON u.userID = kct .userID
		INNER JOIN user_school_details usd ON kct.`userID`=usd.userID
		INNER JOIN school_master sm ON sm.id = usd.school_id";
		if (!empty($city)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = sm.city_id";
		if (!empty($state)) $sql .= " INNER JOIN tbl_cities tc ON sm.`city_id`= tc.`id`";
		if (!empty($state)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		if (!empty($subchapterid)) $sql .= " INNER JOIN knowledgekombat_skill_attempt ksa ON ksa.userID = usd.userID";
		if (!empty($subject_id)) $sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$sql .=" WHERE 1=1 ";
		if (!empty($state)) $sql .= " AND tbl_states.id='$state'";
		if (!empty($city)) $sql .= " AND sm.city_id='$city'";
		
		if (!empty($schoolid)) $sql .= " AND usd.school_id  = '$schoolid' ";
		if (!empty($classid)) $sql .= " AND usd.grade = '$classid'";
		if (!empty($subchapterid)) $sql .= " AND ksa.skillID='$subchapterid'";
		if (!empty($chapter_id)) $sql .= " AND kct.chapterID='$chapter_id'";
		if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		if ($board_id=='All') $sql .= " AND sm.board_id IS NULL";
		elseif(!empty($board_id))$sql .= " AND sm.board_id='$board_id'";
	
		$sql .= " GROUP BY kct.`userID` ";
		// echo $sql;
		$result = mysqli_query($conn,$sql);
		
		$user_ids = '';
		
		while( $userlists = mysqli_fetch_assoc($result) ){
			
			if ( $user_ids == '' ) { $user_ids .= $userlists['userIDs']; }
			else { $user_ids .= ','.$userlists['userIDs']; }
			
		}
		
		return $user_ids;		
		// return $ids = str_replace ( ' ', '', $result['userIDs'] );
		//}
	}
	
	
	public function getrankuserResult($schoolid)
	{
		global $conn;
		$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct 
		INNER JOIN `user_school_details` usd ON kct.`userID`=usd.`userID` WHERE kct.time>0 AND usd.school = '$schoolid'
		GROUP BY kct.userID ORDER BY rank DESC";
		//echo $query;		
		$result = mysqli_query($conn,$query);
		return $result;
		
	} 
	
	public function getrankTimestamp($userid,$chapterID)
	{
		global $conn;
		$query = "SELECT TIME_FORMAT(SEC_TO_TIME(ROUND(SUM(`time`))),'%HH : %iM : %SS') AS `time`,userID as userid FROM knowledgekombat_chapter_time where userID='".$userid."' AND chapterID ='".$chapterID."'  group by userID";
		//echo $query;		
		$result = mysqli_query($conn,$query);
		return $result;
	} 
	
	public function getglobalrankResult($classid)
	{
		global $conn;
		$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct 
		INNER JOIN `user_school_details` usd ON kct.`userID`= usd.`userID` WHERE kct.time>0 AND usd.grade = '$classid' AND kct.learningCurrency !=0  GROUP BY kct.userID ORDER BY rank DESC";
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
	
	
	
	public function getknowResult($state_id,$city_id,$board_id,$schoolid,$classid,$subject_id,$chapter_id,$subchapterid){
		
		global $conn;
		$sql ="SELECT kta.`userID`,u.userID,u.mobile,u.email, u.name AS student_name,usd.school,usd.grade,c.chapter,c.chapterID,kta.`knowledgeCurrency` FROM `knowledgekombat_treasure_attempt` kta
		INNER JOIN `user` u ON u.userID = kta .userID 
		INNER JOIN user_school_details usd ON kta.`userID`=usd.userID
		INNER JOIN chapter c ON c.`chapterID`=kta.chapterID";
		if (!empty($city)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $sql .= " INNER JOIN knowledgekombat_skill ks on kta.chapterID = ks.chapterID";
		if (!empty($subchapterid)) $sql .= " INNER JOIN knowledgekombat_skill_attempt ksa ON ksa.userID = usd.userID";
		if (!empty($subject_id)) $sql .= " INNER JOIN chapter c ON c.`chapterID`=kta.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$sql .=" WHERE 1=1 ";
		if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
		
		if (!empty($schoolid)) $sql .= " AND usd.school  = '$schoolid' ";
		if (!empty($classid)) $sql .= " AND usd.grade = '$classid'";
		if (!empty($subchapterid)) $sql .= " AND ksa.skillID='$subchapterid'";
		if (!empty($chapter_id)) $sql .= " AND kta.chapterID='$chapter_id'";
		if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		if ($board_id=='All') $sql .= " AND usd.board_id IS NULL";
		elseif(!empty($board_id))$sql .= " AND usd.board_id='$board_id'";
	
		$sql .= " GROUP BY kta.`userID` ";
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		return $result;
		//}
	}
	
	

	
}


?>