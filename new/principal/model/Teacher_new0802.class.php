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
		//$sql .= " AND (usd.grade = '$classid' OR FIND_IN_SET(".Romannumeraltonumber($classid).",usd.class_enabled))"; }
		$sql .= " AND (usd.grade = '$classid' )"; }
		if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		if (!empty($schoolname)) $sql .= " AND usd.school IN ( '$schoolname') ";
		if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
		if (!empty($chapter_id)) $sql .= " AND kct.chapterID='$chapter_id'";
		if (!empty($subtopic)) $sql .= " AND ks.skillID='$subtopic'";
			//$sql .= " AND ((Date(u.created_timestamp)>='2019-01-01') OR (Date(u.updated_timestamp)>='2019-01-01'))";
		$sql .= " GROUP BY kct.`userID` ORDER BY u.name DESC";
		//echo $sql;
		//die();
		$result = mysqli_query($conn, $sql);
		return $result;
	}
	
	public function getuserclassenabled($classid,$subject_id,$schoolname,$state_id,$city_id,$chapter_id,$subtopic){
			
		global $conn;
		$sql ="SELECT GROUP_CONCAT(DISTINCT(usd.`class_enabled`)) as grpclsenabled  FROM knowledgekombat_chapter_time 
		kct INNER JOIN user u ON u.userID = kct .userID INNER JOIN user_school_details usd use INDEX (userID_2) ON kct.userID=usd.userID";
		if (!empty($city_id)) $sql .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state_id)) $sql .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $sql .= " INNER JOIN knowledgekombat_skill ks on kct.chapterID = ks.chapterID";
		if (!empty($subject_id)) $sql .= " INNER JOIN chapter c ON c.`chapterID`=kct.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		if (!empty($subtopic))$sql .= " INNER JOIN knowledgekombat_skill ks  use INDEX (userID) on kct.chapterID = ks.chapterID";
		if (!empty($subtopic)) $sql .= " INNER JOIN knowledgekombat_skill_attempt ksa use INDEX (userID) ON ksa.userID = usd.userID";
		$sql .=" WHERE 1=1 ";
		// if ($classid=='all' || empty($classid)) {
			// 	$sql .= " AND usd.grade IN ('I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII')";
		// }
		if (!empty($classid) && $classid!='all') { 
		//$sql .= " AND (usd.grade = '$classid' OR FIND_IN_SET(".Romannumeraltonumber($classid).",usd.class_enabled))"; }
		$sql .= " AND (usd.grade = '$classid')"; }
		
		if (!empty($subject_id)) $sql .= " AND c.subjectID='$subject_id'";
		if (!empty($schoolname)) $sql .= " AND usd.school IN ( '$schoolname') ";
		if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
		if (!empty($chapter_id)) $sql .= " AND kct.chapterID='$chapter_id'";
		if (!empty($subtopic)) $sql .= " AND ks.skillID='$subtopic'";
			//$sql .= " AND ((Date(u.created_timestamp)>='2019-01-01') OR (Date(u.updated_timestamp)>='2019-01-01'))";
		//$sql .= " GROUP BY kct.`userID` ORDER BY u.name DESC";
		// echo $sql;
		// exit;
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
		if (!empty($schoolname)) $sql .= " AND usd.school IN ( '$schoolname')  ";
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
		//$sql .= " AND usd.grade = 'I'";
		//$sql .= " AND c.subjectID='1'";
		$sql .= " AND ((Date(u.created_timestamp)>='2019-01-01') OR (Date(u.updated_timestamp)>='2019-01-01'))";
		if (!empty($schoolname)) $sql .= " AND usd.school IN ( '$schoolname') ";
		if (!empty($state_id)) $sql .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $sql .= " AND usd.city_id='$city_id'";
		//echo $sql;
		//die();
		$result = mysqli_query($conn, $sql);
		return $result;
	}
	
	public function getrankuserResult($classid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id, $userIDs = array())
	{
		global $conn;
		$query="SELECT userid, totalcount, rank FROM (
				SELECT userid, totalcount, 
				@r := IF(@c = totalcount, @r, @r + 1) rank, @c := totalcount  FROM (
				SELECT  ksa.userID, ((IFNULL(((AVG(ksa.learningCurrency)*20)/100),0))+ (IFNULL(((AVG(kta.knowledgeCurrency)*80)/1000),0))) AS totalcount FROM knowledgekombat_skill_attempt ksa use INDEX (ksa_userID_2)
				INNER JOIN `user_school_details` usd use INDEX (userID_2) ON ksa.`userID`= usd.`userID` 
				Left JOIN `knowledgekombat_treasure_attempt` kta use INDEX (userID_2) ON kta.`userID`= usd.`userID` ";
				if ((!empty($classid)) || (!empty($subject_id))){	
				$query.=" INNER JOIN knowledgekombat_skill ks use INDEX (PRIMARY) ON ksa.skillID=ks.skillID INNER JOIN chapter c use INDEX (PRIMARY) ON
				c.chapterID=ks.chapterID ";  
				}
				$query.=" WHERE ksa.`userID`!=0  "; 
				/*if (!empty($classid)){	
					$query.=" AND c.grade='$classid' "; 
				}*/
				if(!empty($userIDs) && sizeof($userIDs) > 0)
				{
					$query .= " AND ksa.userID IN ('".$userIDs."') ";
				}

				if (!empty($classid)) { 
					$query .= " AND (usd.grade = '".ConverToRoman($classid)."')";
					//$query .= " AND (usd.grade = '".ConverToRoman($classid)."' OR FIND_IN_SET(".$classid.",usd.class_enabled))";
				}
	
				if (!empty($subject_id)){	
					$query.=" AND c.subjectID='$subject_id' "; 
				}
				if (!empty($schoolname)){	
						$query.= " AND usd.school IN ( '$schoolname') ";			
					}
					
				if (!empty($city_id)){	
					$query.= " AND usd.city_id = '$city_id' ";
				}	
				if (!empty($subtopic)){	
					$query.= " AND ksa.skillID = '$subtopic' ";
				}
				$query.= " GROUP BY userid ORDER BY totalcount DESC  ) t CROSS JOIN  (  SELECT @r := 0, @c := NULL ) i ) q";

			//echo "<br>";
			$result = mysqli_query($conn,$query);
			return $result;
	}	
	
	public function getrankTimestamp($userid){
		
		global $conn;
		$query = "SELECT TIME_FORMAT(SEC_TO_TIME(ROUND(SUM(`time`))),'%HH : %iM') AS `time`,userID as userid FROM knowledgekombat_chapter_time use INDEX (userID_2) where userID='".$userid."'  group by userID";
		// echo $query;	
		// exit;	
		$result = mysqli_query($conn,$query);
		return $result;
	}
		
	public function getglobalrankResult($classid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id)
	{
		global $conn;
		$query="SELECT userid, totalcount, rank FROM (
				SELECT userid, totalcount, 
				@r := IF(@c = totalcount, @r, @r + 1) rank, @c := totalcount  FROM (";
		$query.=" SELECT kta.userID, (((ksa.learningCurrency)*20/100)+((kta.`knowledgeCurrency`)*80/1000)) 
		AS totalcount FROM knowledgekombat_treasure_attempt kta LEFT JOIN `user_school_details` usd ON 
		kta.`userID`= usd.`userID` INNER JOIN  knowledgekombat_skill_attempt ksa ON ksa.`userID`=usd.`userID`";
		
		if (!empty($subject_id)){	
			$query.=" INNER JOIN knowledgekombat_skill ks ON ksa.skillID=ks.skillID INNER JOIN chapter c ON
			c.chapterID=ks.chapterID ";  
		}			
		$query.=" WHERE 1=1 AND ksa.`userID`!=0  "; 
		if (!empty($classid)){	
			$query.=" AND usd.grade='$classid' "; 
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
		$query.= " GROUP BY userid ORDER BY totalcount DESC  ) t CROSS JOIN  (  SELECT @r := 0, @c := NULL ) i ) q ";
		
		// echo $query;
		// exit;
		//echo "<br>";
		//mysqli_query($conn,$query1);	
		$result = mysqli_query($conn,$query);
		return $result;
		
	}

	public function getglobalrankResultNew($classid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id)
	{
		global $conn;

		$subQuery = " SELECT  ksa.userID, ((ksa.learningCurrency)*20/100) AS totalcount FROM knowledgekombat_skill_attempt ksa  use index (userID_6) ";
		$where = " WHERE  ksa.`userID`!=0 ";
		$groupby = " GROUP BY userID ";
		$orderby = " ORDER BY totalcount DESC ";

		if (!empty($city_id)){	
			$where.= " AND ksa.city_id = '$city_id' ";
		}

		if (!empty($schoolname)){	
			$where.= " AND ksa.school = '$schoolname' ";			
		}

		if (!empty($classid)){	
			$where .= " AND (ksa.grade = '".ConverToRoman($classid)."')";
		}

		if (!empty($subtopic)){	
			$where.= " AND ksa.skillID = '$subtopic' ";
		}

		// if (!empty($subject_id)){	
		// 	$query.=" AND ksa.subjectID='$subject_id' "; 
		// }
		
		$query= " select t.userID , t.totalcount + ((kta.`knowledgeCurrency`)*80/1000) AS totalcoun from ( ";
		$query .= $subQuery . $where . $groupby . $orderby ;
		$query .= " ) t inner JOIN `knowledgekombat_treasure_attempt` kta use INDEX (userID_2) ON kta.`userID`= t.userID GROUP BY userID ORDER BY totalcoun DESC ";
		
		$query = "SELECT userid, totalcount, rank FROM ( SELECT userid, totalcoun as totalcount,  @r := IF(@c = totalcoun, @r, @r + 1) rank, @c := totalcoun  FROM ( ".$query. " ) t CROSS JOIN (  SELECT @r := 0, @c := NULL ) i ) q ";
		// echo $query;
		// exit;
		//mysqli_query($conn,$query1);	
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	public function getaliasrankResult($classid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id, $userIDs = array())
	{
		global $conn;
		$query="SELECT userid, totalcount, rank FROM (
				SELECT userid, totalcount, 
				@r := IF(@c = totalcount, @r, @r + 1) rank, @c := totalcount  FROM (
				SELECT  ksa.userID, ((IFNULL(((AVG(ksa.learningCurrency)*20)/1000),0))+ (IFNULL(((AVG(kta.knowledgeCurrency)*80)/1000),0))) AS totalcount FROM knowledgekombat_skill_attempt ksa use INDEX (ksa_userID_2)
				INNER JOIN `user_school_details` usd use INDEX (userID_2) ON ksa.`userID`= usd.`userID` 
				left JOIN `knowledgekombat_treasure_attempt` kta use INDEX (userID_2) ON kta.`userID`= usd.`userID` ";
		if (!empty($subject_id)){	
			$query.=" INNER JOIN knowledgekombat_skill ks ON ksa.skillID=ks.skillID INNER JOIN chapter c ON
			c.chapterID=ks.chapterID ";  
		}			
		$query.=" WHERE 1=1 "; 

		// if(!empty($userIDs) && sizeof($userIDs) > 0)
		// {
		// 	$query .= " AND ksa.userID IN ('".$userIDs."') ";
		// }

		if (!empty($classid)){	
			$query .= " AND (usd.grade = '".ConverToRoman($classid)."')";
			//$query.=" AND usd.grade='$classid' "; 
		}
		if (!empty($subject_id)){	
			$query.=" AND c.subjectID='$subject_id' "; 
		}
		if (!empty($city_id)){	
			$query.= " AND usd.city_id = '$city_id' ";
		}	
		if (!empty($schoolname)){	
			$query.= " AND usd.school IN ( '$schoolname') ";			
		}
					
		if (!empty($subtopic)){	
			$query.= " AND ksa.skillID = '$subtopic' ";
		}
		$query.= " GROUP BY userid ORDER BY totalcount DESC  ) t CROSS JOIN  (  SELECT @r := 0, @c := NULL ) i ) q ";
		
		// echo $query;
		// exit;
		//mysqli_query($conn,$query1);	
		$result = mysqli_query($conn,$query);
		return $result;
		
	}

	public function getaliasrankResultNew($classid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id, $userIDs = array())
	{
		global $conn;

		$subQuery = " SELECT  ksa.userID, ((IFNULL(((AVG(ksa.learningCurrency)*20)/1000),0))) AS totalcount FROM knowledgekombat_skill_attempt ksa use index (userID_6) ";
		$where = " WHERE  ksa.`userID`!=0 ";
		$groupby = " GROUP BY userID ";
		$orderby = " ORDER BY totalcount DESC ";

		if (!empty($city_id)){	
			$where.= " AND ksa.city_id = '$city_id' ";
		}

		if (!empty($schoolname)){	
			$where.= " AND ksa.school IN ( '$schoolname') ";			
		}

		if (!empty($classid)){	
			$where .= " AND (ksa.grade = '".ConverToRoman($classid)."')";
		}

		if (!empty($subtopic)){	
			$where.= " AND ksa.skillID = '$subtopic' ";
		}

		// if (!empty($subject_id)){	
		// 	$query.=" AND ksa.subjectID='$subject_id' "; 
		// }
		
		$query= " select t.userID , t.totalcount + (IFNULL(((AVG(kta.knowledgeCurrency)*80)/1000),0)) AS totalcoun from ( ";
		$query .= $subQuery . $where . $groupby . $orderby ;
		$query .= " ) t left JOIN `knowledgekombat_treasure_attempt` kta use INDEX (userID_2) ON kta.`userID`= t.userID GROUP BY userID ORDER BY totalcoun DESC ";
		
		$query = "SELECT userid, totalcount, rank FROM ( SELECT userid, totalcoun as totalcount,  @r := IF(@c = totalcoun, @r, @r + 1) rank, @c := totalcoun  FROM ( ".$query. " ) t CROSS JOIN (  SELECT @r := 0, @c := NULL ) i ) q ";
		// echo $query;
		// exit;
		//mysqli_query($conn,$query1);	
		$result = mysqli_query($conn,$query);
		return $result;
	}

	public function getUserFromSchools($schoolname) {
		global $conn;
		$query = "SELECT userID from user_school_details usd where usd.school IN ( '$schoolname')  GROUP BY userID ";		
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	public function getglobalrankResults($schoolname){
		
		global $conn;
		$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct INNER JOIN `user_school_details` usd ON kct.`userID`= usd.`userID` WHERE kct.time>0 AND usd.grade = 'I' AND usd.school IN ( '$schoolname')  GROUP BY kct.userID ORDER BY rank DESC";
		//echo $query;		
		$result = mysqli_query($conn,$query);
		return $result;
	}
		
	public function getoverallGrade($userid,$subtopicid,$subject_id){
		global $conn;
		/*$query = "SELECT ksa.`userID` AS userid ,((IFNULL(((AVGAearningCurrency*20)/100),0))+ (IFNULL(((AVGknowledgeCurrency*80)/1000),0))) 
		AS currency FROM skillaverage ksa LEFT JOIN treasureavg kta ON kta.`userID`= ksa.`userID` WHERE ksa.userID='".$userid."' ";*/
		
		/*$query = " SELECT ROUND((IFNULL(((ksa.learningCurrency*20)/100),0))+(IFNULL(((kta.`knowledgeCurrency`*80)/1000),0)))  AS currency, 
		ksa.userID AS userid FROM knowledgekombat_skill_attempt ksa LEFT JOIN knowledgekombat_treasure_attempt kta ON (kta.userID= ksa.userID)
		WHERE ksa.userID='".$userid."' ";*/
		
		$query = " SELECT ((IFNULL(((AVG(ksa.learningCurrency)*20)/100),0))+(IFNULL(((AVG(kta.`knowledgeCurrency`)*80)/1000),0)))  AS currency, 
		ksa.userID AS userid FROM knowledgekombat_skill_attempt ksa 
		use INDEX (userID_3)
		LEFT JOIN knowledgekombat_treasure_attempt kta use INDEX (userID_2) ON kta.userID= ksa.userID";
		if (!empty($subject_id)){	
			$query.= " INNER JOIN knowledgekombat_skill ks ON ksa.skillID=ks.skillID INNER JOIN chapter c ON c.chapterID=ks.chapterID";
		}	
		$query.= "  WHERE ksa.userID='".$userid."' ";
		
		if (!empty($subtopicid)){	
			$query.= " AND ksa.skillID = '$subtopicid' ";
		}
			
		if (!empty($subject_id)){	
			$query.= " AND  c.subjectID = '$subject_id' ";
		}	
		$query.= " ORDER BY ksa.`timestamp` DESC LIMIT 0,1";
		// echo $query."<br><br>";
		// exit;
		//echo $userid.",".$subtopicid.",".$subject_id;
		//die();
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
		
		$query = "SELECT  nka.`learningCurrency` AS learningavg FROM `knowledgekombat_skill_attempt` nka INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID INNER JOIN `user` u ON u.userID = nka.userID INNER JOIN user_school_details usd ON u.userID = usd.userID ";
		if (!empty($city_id)) $query .= " INNER JOIN tbl_cities  ON tbl_cities.id = usd.city_id";
		if (!empty($state_id)) $query .= " INNER JOIN tbl_cities tc ON usd.`city_id`= tc.`id`";
		if (!empty($state_id)) $query .= " INNER JOIN tbl_states ON tbl_states.id = tc.state_id ";
		if (!empty($chapter_id)) $query .= " INNER JOIN knowledgekombat_skill ks on nk.chapterID = ks.chapterID";
		if (!empty($subject_id)) $query .= " INNER JOIN chapter c ON c.`chapterID`= nk.chapterID INNER JOIN `subject` s ON s.`subjectID` = c.subjectID";
		$query .=" WHERE 1=1 and u.userID ='$userID' ";
		if (!empty($state_id)) $query .= " AND tbl_states.id='$state_id'";
		if (!empty($city_id)) $query .= " AND usd.city_id='$city_id'";
		if (!empty($schoolname)) $query .= " AND usd.school  = '$schoolname' ";
		if (!empty($classid)) $query .= " AND usd.grade = '$classid'";
		if (!empty($subtopic)) $query .= " AND nk.skillID='".$subtopic."'";
		if (!empty($chapter_id)) $query .= " AND nk.chapterID='$chapter_id'";
		if (!empty($subject_id)) $query .= " AND c.subjectID='$subject_id'";
		//$query .=" AND nka.`learningCurrency`>0"; 
		$query .= " AND ((Date(u.created_timestamp)>='2019-01-01') OR (Date(u.updated_timestamp)>='2019-01-01'))";
		$query .= " ORDER BY nka.timestamp DESC LIMIT 0,1";
		//$query .=" GROUP BY nk.skillID ";
		//$query .=" ORDER BY nka.updated_timestamp  DESC";
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