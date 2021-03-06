<?php
class Dashboard
{
	function getschoolResult(){

		global $conn;
		$query = " SELECT DISTINCT school FROM user_school_details ORDER BY school ASC";
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
		echo $query = "SELECT DISTINCT  chapter,kct.`chapterID` AS chapterID FROM `knowledgekombat_chapter_time` kct INNER JOIN `chapter` c ON kct.`chapterID` = c.`chapterID`  WHERE $cond and kct.`learningCurrency` > 0 ORDER BY chapter ASC";
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	function getuserResult($schoolid,$classid){
		global $conn;
		$query = "SELECT name,u.userID FROM user u INNER JOIN user_school_details usd on u.userID = usd.userID where LOWER(usd.school)='$schoolid' and usd.grade='$classid'";
		$result = mysqli_query($conn,$query);
		return $result;
	}

	function getskillResult($chapterid){
		global $conn;
		$query = "SELECT chapterID ,skillID,skill FROM knowledgekombat_skill WHERE chapterID='$chapterid'";
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	function getlearningResult($skillid,$userid){
		global $conn;
		$query = "SELECT nk.skillID,nk.chapterID,nk.skill,u.userID,nka.updated_timestamp, nka.`learningCurrency` AS learning FROM `knowledgekombat_skill_attempt` nka INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID INNER JOIN `user` u ON u.userID = nka.userID WHERE nk.skillID='".$skillid."' and u.userID='".$userid."' ORDER BY nka.updated_timestamp DESC LIMIT 0,1";
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
	function getlearningResultAverage($skillid,$schoolid,$classid){
		global $conn;
		$query = "SELECT  sum(nka.`learningCurrency`)/count(*) AS learningavg,nka.userID, MAX(nka.updated_timestamp), nka.`learningCurrency`,u.`name` FROM `knowledgekombat_skill_attempt` nka INNER JOIN `knowledgekombat_skill` nk ON nk.skillID=nka.skillID INNER JOIN `user` u ON u.userID = nka.userID INNER JOIN user_school_details usd ON u.userID = usd.userID WHERE nk.skillID='".$skillid."' AND LOWER(usd.school)='".$schoolid."' and usd.grade='".$classid."' AND nka.`learningCurrency`>0 GROUP BY u.userID ORDER BY nka.updated_timestamp  DESC";
	
		/*echo "<br>";
		echo $query;
		echo "<br>";*/
		$result = mysqli_query($conn,$query);
		return $result;
	}
	
}
?>