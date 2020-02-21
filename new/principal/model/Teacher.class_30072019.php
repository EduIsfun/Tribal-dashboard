<?php
class  Teacher
{
	public function getSchool(){
		
		global $conn;
		$sql = "SELECT * FROM teacher_login";
		$result = mysqli_query($conn,$sql);
		return $result;
	}
		
	public function getStateResult($countryid){
		global $conn;
		$sql = "SELECT * FROM tbl_states  where country_id = '".$countryid."' order by name asc";
		$result = mysqli_query($conn,$sql);
		return $result;
	}
		
	public function getclassResult(){
		global $conn;
		$query = "SELECT id,class FROM grade GROUP BY class";
		$result = mysqli_query($conn,$query);
		return $result;
	}
		
	public function getBoardResult(){
		
		global $conn;
		$query = "SELECT  * FROM board";
		$result = mysqli_query($conn,$query);
		return $result;
	}
		
	public function getClass($userID){
		
		global $conn;
		$query = "SELECT  *  FROM user_school_details WHERE userID='$userID'";
		$result = mysqli_query($conn,$query);
		$row = mysqli_fetch_object($result);
		return $row;
	}
		
	public function getSubjectResult($board_id,$class_id){
		
		global $conn;
		$query = "SELECT  * FROM  subject WHERE subjectID IN('1','2','3','8') ORDER BY subjectID ASC";
		$result = mysqli_query($conn,$query);
		return $result;	
	}
		
	public function getuserResult($classid,$subject_id,$schoolname,$state_id,$city_id,$chapter_id,$subtopic){
			
		global $conn;
		$sql ="SELECT DISTINCT(kct.userID),u.userID,u.name,u.mobile,u.email,usd.school,usd.grade,usd.class_enabled,
		ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank  FROM knowledgekombat_chapter_time 
		kct INNER JOIN user u ON u.userID = kct .userID INNER JOIN user_school_details usd ON kct.userID=usd.userID";
		if (!empty($city_id)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		if (!empty($subject_id)) $sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		if (!empty($subtopic))$sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		if (!empty($subtopic)) $sql .= " INNER JOIN knowledgekombat_skill_attempt ksa ON ksa.userID = usd.userID";
		$sql .=" WHERE 1=1 ";
		// if ($classid=='all' || empty($classid)) {
			// 	$sql .= " AND usd.grade IN ('I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII')";
		// }
		if (!empty($classid) && $classid!='all') { 
		$sql .= " AND (usd.grade = '$classid' OR FIND_IN_SET(".Romannumeraltonumber($classid).",usd.class_enabled))"; }
		if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		if (!empty($schoolname)) $sql .= " AND usd.school='$schoolname'";
		if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
		if (!empty($chapter_id)) $sql .= " AND kct.chapterID='$chapter_id'";
		if (!empty($subtopic)) $sql .= " AND ks.skillID='$subtopic'";
			//$sql .= " AND ((Date(u.created_timestamp)>='2019-01-01') OR (Date(u.updated_timestamp)>='2019-01-01'))";
		$sql .= " GROUP BY kct.`userID` ORDER BY u.name DESC";
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		return $result;
	}
	
	public function getuserclassenabled($classid,$subject_id,$schoolname,$state_id,$city_id,$chapter_id,$subtopic){
			
		global $conn;
		$sql ="SELECT GROUP_CONCAT(DISTINCT(usd.`class_enabled`)) as grpclsenabled  FROM knowledgekombat_chapter_time 
		kct INNER JOIN user u ON u.userID = kct .userID INNER JOIN user_school_details usd ON kct.userID=usd.userID";
		if (!empty($city_id)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		if (!empty($subject_id)) $sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		if (!empty($subtopic))$sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		if (!empty($subtopic)) $sql .= " INNER JOIN knowledgekombat_skill_attempt ksa ON ksa.userID = usd.userID";
		$sql .=" WHERE 1=1 ";
		// if ($classid=='all' || empty($classid)) {
			// 	$sql .= " AND usd.grade IN ('I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII')";
		// }
		if (!empty($classid) && $classid!='all') { 
		$sql .= " AND (usd.grade = '$classid' OR FIND_IN_SET(".Romannumeraltonumber($classid).",usd.class_enabled))"; }
		
		if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		if (!empty($schoolname)) $sql .= " AND usd.school='$schoolname'";
		if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
		if (!empty($chapter_id)) $sql .= " AND kct.chapterID='$chapter_id'";
		if (!empty($subtopic)) $sql .= " AND ks.skillID='$subtopic'";
			//$sql .= " AND ((Date(u.created_timestamp)>='2019-01-01') OR (Date(u.updated_timestamp)>='2019-01-01'))";
		//$sql .= " GROUP BY kct.`userID` ORDER BY u.name DESC";
		
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_object($result);
		return $row;
	}
	
	public function getuserResultIDs($classid,$subject_id,$schoolname,$state_id,$city_id,$subtopic){
		
		global $conn;
		$sql ="SELECT  DISTINCT(kct.userID) AS userIDs FROM `knowledgekombat_chapter_time` kct
		INNER JOIN `user` u ON u.userID = kct .userID INNER JOIN user_school_details usd ON kct.`userID`=usd.userID";
		if (!empty($city_id)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
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
		$sql .= " AND ((Date(u.created_timestamp)>='2019-01-01') OR (Date(u.updated_timestamp)>='2019-01-01'))";
		//$sql .= " GROUP BY kct.`userID` ";
		//echo "2-".$sql;
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
		
	public function getusermainpageResult($schoolname,$state_id,$city_id){
			
		global $conn;
		$sql ="SELECT DISTINCT kct.`userID`,u.userID, u.name,usd.school,usd.grade FROM `knowledgekombat_chapter_time` kct INNER JOIN `user` u ON u.userID = kct .userID	INNER JOIN user_school_details usd ON kct.`userID`=usd.userID";
		if (!empty($city_id)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		$sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		$sql .=" WHERE 1=1 ";
		$sql .= " AND usd.grade = 'I'";
		$sql .= " AND c.subjectID='1'";
		$sql .= " AND ((Date(u.created_timestamp)>='2019-01-01') OR (Date(u.updated_timestamp)>='2019-01-01'))'";
		if (!empty($schoolname)) $sql .= " AND usd.school='$schoolname'";
		if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		return $result;
	}
	
	public function getrankuserResult($classid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id)
	{
		global $conn;
		$query="SELECT userid, totalcount, rank FROM (
				SELECT userid, totalcount, 
				@r := IF(@c = totalcount, @r, @r + 1) rank, @c := totalcount  FROM (
				SELECT  ksa.userID, ROUND(SUM(ksa.learningCurrency)/COUNT(ksa.learningCurrency))
				AS totalcount FROM knowledgekombat_skill_attempt ksa 
				INNER JOIN `user_school_details` usd ON ksa.`userID`= usd.`userID` ";
				if ((!empty($classid)) || (!empty($subject_id))){	
				$query.=" INNER JOIN knowledgekombat_skill ks ON ksa.skillID=ks.skillID INNER JOIN chapter c ON
				c.chapterID=ks.chapterID ";  
				}
				$query.=" WHERE 1=1 "; 
				/*if (!empty($classid)){	
					$query.=" AND c.grade='$classid' "; 
				}*/
				if (!empty($classid)) { 
					$query .= " AND (usd.grade = '".ConverToRoman($classid)."' OR FIND_IN_SET(".$classid.",usd.class_enabled))";
				}
	
				if (!empty($subject_id)){	
					$query.=" AND c.subjectID='$subject_id' "; 
				}
				if (!empty($schoolname)){	
						$query.= "AND usd.school = '$schoolname' ";			
					}
					
				if (!empty($city_id)){	
					$query.= " AND usd.city_id = '$city_id' ";
				}	
				if (!empty($subtopic)){	
					$query.= " AND ksa.skillID = '$subtopic' ";
				}
				$query.= " GROUP BY userid ORDER BY totalcount DESC  ) t CROSS JOIN  (  SELECT @r := 0, @c := NULL ) i ) q";
			//echo $query;
			//echo "<br>";
			$result = mysqli_query($conn,$query);
			return $result;
	}	
	
	public function getrankTimestamp($userid){
		
		global $conn;
		$query = "SELECT TIME_FORMAT(SEC_TO_TIME(ROUND(SUM(`time`))),'%HH : %iM') AS `time`,userID as userid FROM knowledgekombat_chapter_time where userID='".$userid."'  group by userID";
		//echo $query;		
		$result = mysqli_query($conn,$query);
		return $result;
	}
		
	public function getglobalrankResult($classid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id)
	{
		global $conn;
		$query="SELECT userid, totalcount, rank FROM (
				SELECT userid, totalcount, 
				@r := IF(@c = totalcount, @r, @r + 1) rank, @c := totalcount  FROM (
				SELECT  ksa.userID, ROUND(SUM(ksa.learningCurrency)/COUNT(ksa.learningCurrency))
				AS totalcount FROM knowledgekombat_skill_attempt ksa 
				INNER JOIN `user_school_details` usd ON ksa.`userID`= usd.`userID` ";
		if (!empty($subject_id)){	
			$query.=" INNER JOIN knowledgekombat_skill ks ON ksa.skillID=ks.skillID INNER JOIN chapter c ON
			c.chapterID=ks.chapterID ";  
		}			
		$query.=" WHERE 1=1 "; 
		if (!empty($classid)){	
			//$query.=" AND usd.grade='$classid' "; 
		}
		if (!empty($subject_id)){	
			$query.=" AND c.subjectID='$subject_id' "; 
		}
		if (!empty($city_id)){	
			$query.= " AND usd.city_id = '$city_id' ";
		}	
		if (!empty($schoolname)){	
			$query.= "AND usd.school = '$schoolname' ";			
		}
					
		if (!empty($subtopic)){	
			$query.= " AND ksa.skillID = '$subtopic' ";
		}
		$query.= " GROUP BY userid ORDER BY totalcount DESC  ) t CROSS JOIN  (  SELECT @r := 0, @c := NULL ) i ) q";
		
		//echo $query;
		//mysqli_query($conn,$query1);	
		$result = mysqli_query($conn,$query);
		return $result;
		
	}
	
	public function getglobalrankResults($schoolname){
		
		global $conn;
		$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct INNER JOIN `user_school_details` usd ON kct.`userID`= usd.`userID` WHERE kct.time>0 AND usd.grade = 'I' AND usd.school='$schoolname'  GROUP BY kct.userID ORDER BY rank DESC";
		//echo $query;		
		$result = mysqli_query($conn,$query);
		return $result;
	}
		
	public function getoverallGrade($userid){
		
		global $conn;
		$query = "SELECT ROUND(SUM(learningCurrency)/COUNT(learningCurrency)) AS currency, 
		userID AS userid,`chapterID` FROM knowledgekombat_chapter_time where userID='".$userid."'
		GROUP BY userID ";
		//echo $query."<br><br>";
		$result = mysqli_query($conn,$query);
		$rowcount=mysqli_num_rows($result);
		return $result;
	}
		
	public function getStudentresult($id){
			
		global $conn;
		$sql = "SELECT * FROM user WHERE userID = '$id'";
		$studentresult = mysqli_query($conn,$sql);
		return $studentresult;
	}
		
	public function getuTopicid($chapter_id){
			
		global $conn;
		$sql = "SELECT * FROM knowledgekombat_skill WHERE chapterID = '$chapter_id'";
		$studentresult = mysqli_query($conn,$sql);
		return $studentresult;
	}
		
	function getlearningResultAverage($chapter_id,$schoolname,$classid,$subject_id,$state_id,$city_id,$subtopic,$userID){
		
		global $conn;
		$query = "SELECT  sum(nka.`learningCurrency`)/count(*) AS learningavg FROM `knowledgekombat_skill_attempt` nka INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID INNER JOIN `user` u ON u.userID = nka.userID INNER JOIN user_school_details usd ON u.userID = usd.userID ";
		if (!empty($city_id)) $query .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $query .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state_id)) $query .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $query .= " INNER JOIN knowledgekombat_skill ks on nk.chapterID = ks.chapterID";
		if (!empty($subject_id)) $query .= " INNER JOIN chapter c ON c.`chapterID`= nk.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		if (!empty($state_id)) $query .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $query .= " AND usd.city_id='$city_id'";
		if (!empty($schoolname)) $query .= " AND usd.school  = '$schoolname' ";
		if (!empty($classid)) $query .= " AND usd.grade = '$classid'";
		if (!empty($subtopic)) $query .= " AND nk.skillID='".$subtopic."'";
		if (!empty($chapter_id)) $query .= " AND nk.chapterID='$chapter_id'";
		if (!empty($subject_id)) $query .= " AND c.subjectID='$subject_id'";
		$query .=" WHERE 1=1 and u.userID ='$userID' ";
		$query .=" AND nka.`learningCurrency`>0"; 
		$sql .= " AND ((Date(u.created_timestamp)>='2019-01-01') OR (Date(u.updated_timestamp)>='2019-01-01'))'";
		$query .=" GROUP BY nk.skillID ";
		$query .=" ORDER BY nka.updated_timestamp  DESC";
		//echo $query;
		//echo "<br>";
		$result = mysqli_query($conn,$query);
		return $result;
	}
		
	public function get_userwise_city($userid)	
	{		
		global $conn;
		$sql = "SELECT city.name,city.id FROM user_school_details as usd INNER JOIN tbl_cities  as city ON usd.city_id=city.id WHERE usd.userID = '$userid'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_object($result);
		return $row;
	}	
	
}
?>