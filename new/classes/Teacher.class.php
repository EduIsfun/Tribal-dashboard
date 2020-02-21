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
		$query = "SELECT  class FROM grade GROUP BY class";
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
	public function getSubjectResult(){
		global $conn;
		$query = "SELECT  * FROM subject";
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	public function getuserResult($schoolid,$classid,$chapterid){
		
		global $conn;
		$sql ="SELECT DISTINCT kct.`userID`,u.userID, u.name,usd.school,usd.grade_id FROM `knowledgekombat_chapter_time` kct
		INNER JOIN `user` u ON u.userID = kct .userID
		INNER JOIN user_school_details usd ON kct.`userID`=usd.userID
		WHERE 1=1 ";

		if($schoolid != '0') $sql .= " AND usd.school  = '$schoolid'";
		if($classid != '0') $sql .= " AND usd.grade_id = '$classid'";
		if($chapterid != '0') $sql .= " AND chapterID='$chapterid' ";
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		return $result;
	}
	
	public function getrankuserResult($schoolid)
	{
		global $conn;
		$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct 
		INNER JOIN `user_school_details` usd ON kct.`userID`=usd.`userID` WHERE kct.time>0 AND usd.school = '$schoolid'GROUP BY kct.userID ORDER BY rank DESC";
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
	
	public function getglobalrankResult($classid)
	{
		global $conn;
		$query = "SELECT ROUND(SUM(kct.learningCurrency)/COUNT(kct.learningCurrency)) AS rank,kct.userID AS userid,kct.chapterID FROM knowledgekombat_chapter_time kct 
		INNER JOIN `user_school_details` usd ON kct.`userID`= usd.`userID` WHERE kct.time>0 AND usd.grade_id = '$classid' AND kct.learningCurrency !=0  GROUP BY kct.userID ORDER BY rank DESC";
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

	
}


?>