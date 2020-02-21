<?php
class  Teacher
{
	
	public function getStateResult($countryid){
		global $conn;
		$sql = "SELECT * FROM tbl_states  where country_id = '".$countryid."' order by name asc";
		$result = mysqli_query($conn,$sql);
		return $result;
	}
	
	public function getclassResult(){
		global $conn;
		$query = "SELECT  * FROM grade ORDER BY id ASC";
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
	
	public function getschoolbycode($schoolcode){
		global $conn;
		$query = "SELECT  *  FROM school_master WHERE school_code='$schoolcode'";
		$result = mysqli_query($conn,$query);
		$row = mysqli_fetch_object($result);
		return $row;
	}
	public function getSubjectResult(){
		global $conn;
		$query = "SELECT  * FROM  `subject` WHERE `subjectID` IN('1','2','3','8') ORDER BY subjectID ASC";
		$result = mysqli_query($conn,$query);
		return $result;
		
	}
	
public function getuserResult($schoolid,$classid,$subchapterid,$state,$city,$board_id,$chapter_id,$subject_id){
		
	global $conn;

// $sql="SELECT DISTINCT kct.`userID`,u.userID, u.name AS student_name,usd.school,usd.grade_id,ks.chapterID,tbl_states.name,tbl_cities.name FROM `knowledgekombat_chapter_time` kct 
// INNER JOIN `user` u ON u.userID = kct .userID 
// INNER JOIN user_school_details usd ON kct.`userID`=usd.userID 
// INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id
// INNER JOIN tbl_states  ON tbl_states.id = tbl_cities.state_id
// INNER JOIN knowledgekombat_skill ks ON kct.chapterID = ks.chapterID WHERE 1=1 ";
// if($state !='')$sql .= " AND tbl_states.id='$state'";
// if($city !='')$sql .= " AND usd.city_id='$city'";
// if($board_id !='')$sql .= " AND usd.board_id='$board_id'";
// $sql .= " GROUP BY kct.`userID` ";
// $result = mysqli_query($conn, $sql);
 // echo $sql;
// return $result;	

		
	// else{
		$sql ="SELECT  kct.`userID`,u.userID, u.name AS student_name,usd.school,usd.grade, ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank  FROM `knowledgekombat_chapter_time` kct
		INNER JOIN `user` u ON u.userID = kct .userID
		INNER JOIN user_school_details usd ON kct.`userID`=usd.userID";
		if(!empty($board_id)&&$board_id=='4'){
				$sql.=" INNER Join school_master sm on sm.school_code= usd.school_id";
			}
		if (!empty($city)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		if (!empty($subchapterid)) $sql .= " INNER JOIN knowledgekombat_skill_attempt ksa ON ksa.userID = usd.userID";
		if (!empty($subject_id)) $sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$sql .=" WHERE 1=1 ";
		if (!empty($state)) $sql .= " AND tbl_states.id='$state'";
		if (!empty($city)) $sql .= " AND usd.city_id='$city'";
		if ($board_id=="4"){
			if (!empty($schoolid)) $sql .= " AND usd.school_id  = '$schoolid' AND sm.`school_code`!='' ";
		} else {
			if (!empty($schoolid)) $sql .= " AND usd.school  = '$schoolid' ";
			 if(!empty($board_id) && $board_id!='All')$sql .= " AND usd.board_id='$board_id'";
		}		
		if (!empty($classid)) $sql .= " AND usd.grade = '$classid'";
		if (!empty($subchapterid) && $subchapterid!='undefined') $sql .= " AND ksa.skillID='$subchapterid'";
		if (!empty($chapter_id)) $sql .= " AND kct.chapterID='$chapter_id'";
		if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		//if ($board_id=='All') $sql .= " AND usd.board_id IS NULL";
		
	
		$sql .= " GROUP BY kct.`userID` HAVING rank !=0";
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		return $result;
		//}
	}
	
	public function getuserResultIDs($schoolid,$classid,$subchapterid,$state,$city,$board_id,$chapter_id,$subject_id){
		
		global $conn;

		$sql ="SELECT  kct.userID AS userIDs FROM `knowledgekombat_chapter_time` kct
		INNER JOIN `user` u ON u.userID = kct .userID
		INNER JOIN user_school_details usd ON kct.`userID`=usd.userID";
		if (!empty($city)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		if (!empty($subchapterid)) $sql .= " INNER JOIN knowledgekombat_skill_attempt ksa ON ksa.userID = usd.userID";
		if (!empty($subject_id)) $sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$sql .=" WHERE 1=1 ";
		if (!empty($state)) $sql .= " AND tbl_states.id='$state'";
		if (!empty($city)) $sql .= " AND usd.city_id='$city'";
		
		if (!empty($schoolid)) $sql .= " AND usd.school  = '$schoolid' ";
		if (!empty($classid)) $sql .= " AND usd.grade = '$classid'";
		if (!empty($subchapterid)) $sql .= " AND ksa.skillID='$subchapterid'";
		if (!empty($chapter_id)) $sql .= " AND kct.chapterID='$chapter_id'";
		if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		if ($board_id=='All') $sql .= " AND usd.board_id IS NULL";
		elseif(!empty($board_id))$sql .= " AND usd.board_id='$board_id'";
	
		$sql .= " GROUP BY kct.`userID` ";
		//echo $sql;
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
	
	public function getrankuserResult($classid,$schoolname,$city_id,$board_id)
	{
		global $conn;
		/*$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct 
		INNER JOIN `user_school_details` usd ON kct.`userID`=usd.`userID` WHERE kct.time>0 AND usd.school = '$schoolid'GROUP BY kct.userID ORDER BY rank DESC";*/
		//echo $classid;
		//if ($classid!='' && $classid!='0') {
			$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct INNER JOIN `user_school_details` usd ON kct.`userID`=usd.`userID`";
			if(!empty($board_id)&&$board_id=='4'){
				$query.=" INNER Join school_master sm on sm.school_code= usd.school_id";
			}
			$query.=" WHERE kct.time>0 ";
			if (!empty($classid)){	
				$query.= "AND usd.grade = '$classid' ";
			}	
			
			if (!empty($schoolname)){	
				if ($board_id==4){
					$query.= "AND usd.school_id = '$schoolname' AND sm.`school_code`!=''";
				} else {
					$query.= "AND usd.school = '$schoolname' ";			
				}
			}
			if (!empty($city_id)){	
				$query.= " AND usd.city_id = '$city_id' ";
			}			
			$query.= "GROUP BY kct.userID HAVING rank !=0  ORDER BY rank DESC";
			//echo $query;
			$result = mysqli_query($conn,$query);
			return $result;
		// } else {
		// 	return 0;
		// }
		//mysqli_query($conn,$query1);	
		
		
	}
	
	public function getrankTimestamp($userid)
	{
		global $conn;
		$query = "SELECT TIME_FORMAT(SEC_TO_TIME(ROUND(SUM(`time`))),'%HH : %iM : %SS') AS `time`,userID as userid FROM knowledgekombat_chapter_time where userID='".$userid."'  group by userID";
		//echo $query;		
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	public function getglobalrankResult($classid,$schoolname,$city_id,$board_id)
	{
		global $conn;
		$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct 
		INNER JOIN `user_school_details` usd ON kct.`userID`= usd.`userID` ";
		if(!empty($board_id)&&$board_id=='4'){
			$query.=" INNER Join school_master sm on sm.school_code= usd.school_id";
		}
		$query.=" WHERE kct.time>0 AND kct.learningCurrency!=0 ";
		if (!empty($classid)){	
			$query.=" AND usd.grade='$classid' "; 
		}
		//$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct INNER JOIN `user_school_details` usd ON kct.`userID`= usd.`userID` WHERE kct.time>0  ";
	  	
		// if (!empty($schoolname)){
		// 	$query.= "AND usd.school = '$schoolname' ";
		// }	
		if (!empty($city_id)){	
			$query.= " AND usd.city_id = '$city_id' ";
		}	
		$query.= "GROUP BY userid,kct.chapterID HAVING rank!=0  ORDER BY rank DESC";
		//echo $query;
		//mysqli_query($conn,$query1);	
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
		$query = "SELECT * FROM board";
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
	
	public function addRegister($objregi)
	
	{

		global $conn;
		$query =" INSERT INTO  teacher_password set ";
		foreach ($objregi as $key=>$value){
		$query .= "$key='$value'";
		$query .= ",";
		}    
		$query= substr($query, 0, -1); 
		//echo $query;
		mysqli_query($conn, $query);
		//mysqli_set_charset('utf8');
		return  mysqli_insert_id($conn);

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