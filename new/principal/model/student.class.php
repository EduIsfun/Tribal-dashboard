<?php
  class student {
	  function checkUserid($userId)
		{
			global $conn;
         $query = "SELECT * FROM user_password WHERE userID='$userId'";
			$result = mysqli_query($conn, $query);
			
			return $result;
		}
	function checkUsermobile($userId)
		{
			global $conn;
			 $query = "SELECT * FROM user WHERE mobile='$userId'";
			$result = mysqli_query($conn, $query);
			
			return $result;
		}
	function checkMobile($userId)
		{
			global $conn;
		 $query = "SELECT * FROM user WHERE userID='$userId'";
			$result = mysqli_query($conn, $query);
			
			return $result;
		}
	function checkLogin($mobiluserid,$password)
		{
			global $conn;
         $query = "SELECT * FROM user_password WHERE userID='$mobiluserid' AND password='$password'";
			$result = mysqli_query($conn,$query);
			
			return $result;
		}
	function getUserinfo($uid)
		{
			global $conn;
          $sql ="SELECT u.userid,u.name,u.city,u.email,u.mobile,u.country,u.dob,s.school,s.grade,TIME_FORMAT(SEC_TO_TIME(`time`),'%HH : %iM : %SS') AS times FROM `user` u
			LEFT JOIN `user_school_details` s ON s.userid=u.userid 
			LEFT JOIN `game_time_spent` t ON t.userid=u.userid 
			where u.userid='".$uid."' ";
			$result = mysqli_query($conn,$sql);
			
			return $result;
		}
	function addOtp($otp,$mobile)
		{
			global $conn;
			$query = "INSERT INTO user_otp(otp,mobile) VALUES ('$otp',$mobile)";
			$result = mysqli_query($conn,$query);
			
			return $result;
		}
	function getOtp($mobile)
		{
			global $conn;
			$query = "SELECT * FROM user_otp WHERE mobile='$mobile' AND status='0'";
			$result = mysqli_query($conn,$query);
			
			return $result;
		}
	function getOtpstatus($mobile,$otp)
		{
			global $conn;
			$query = "SELECT * FROM user_otp WHERE mobile='$mobile' AND otp='$otp'";
			$result = mysqli_query($conn,$query);
			return $result;
		}
	function updateOtp($otp,$mobile)
		{
			global $conn;
			$query = "UPDATE user_otp SET otp='$otp' WHERE mobile='$mobile' AND status='0'";
			$result = mysqli_query($conn,$query);
			
			return $result;
		}
	function updateMobile($mobile,$userid)
		{
			global $conn;
			$query = "UPDATE user SET mobile='$mobile' WHERE userID='$userid'";
			$result = mysqli_query($conn,$query);
			
			return $result;
		}
	function updateOtpstatus($otp,$mobile)
		{
			global $conn;
			$query = "UPDATE user_otp SET status='1' WHERE mobile='$mobile' AND otp='$otp'";
			$result = mysqli_query($conn,$query);
			
			return $result;
		}
	function updateProfile($objusers,$userid)
		{
			global $conn;
			 $query = "UPDATE user SET name='".$objusers->name."',gmailID='".$objusers->email."',city='".$objusers->city."',country='".$objusers->country."' WHERE userID='$userid' ";
			$result = mysqli_query($conn,$query);
			
			return $result;
		}
		function getviewUser()
		{
			global $conn;
            $query = "SELECT * From `user` ORDER BY timestamp DESC";
			$result = mysqli_query($conn, $query);
			return $result;
		}
		
		function getcallMe()
		
		{
			global $conn;
			$query = "SELECT c.UserID,c.name,c.mobile,c.time_stamp,u.email,u.dob,u.country,u.`created_timestamp`,u.`updated_timestamp`,u.city FROM call_me c
            INNER JOIN `user` u ON c.userID=u.userID ORDER BY `time_stamp` DESC";
			$result = mysqli_query($conn, $query);
			return $result;
			
		}
		
		function getinviteFriends()
		
		{
			global $conn;
			//$query = "SELECT * From `invite_friends` ORDER BY time_stamp DESC";
			$query = "SELECT i.userID,i.name,i.mobile,i.emailid,i.time_stamp,u.dob,u.country,u.`created_timestamp`,u.`updated_timestamp`,u.city FROM invite_friends i
            INNER JOIN `user` u ON i.userID=u.userID ORDER BY `time_stamp` DESC";
			$result = mysqli_query($conn, $query);
			return $result;
			
		}
		
		function getlead()
		
		{
			global $conn;
			$query = "SELECT * From `lead`";
			$result = mysqli_query($conn,$query);
			return $result;
			
		}
		
		// function Totalgame()
		
		// {
			// global $conn;
			// $query = "SELECT game,COUNT(*) AS total FROM `session` GROUP BY game";
			// $result = mysqli_query($conn,$query);
			// return $result;
			
		// }
		
		function paiduser()
		
		{
			global $conn;
			//$query="select * from serial_key ";
			$query="SELECT se.`userID`,se.`deviceID`,se.created_timestamp,se.game,se.timestamp,u.name,us.grade,s.`platform`,s.`serial_key`,us.`school` FROM `session` se 
            INNER JOIN `serial_key` s ON se.deviceID=s.deviceID
            INNER JOIN `user` u ON se.`userID`=u.userID
            LEFT JOIN `user_school_details` us ON se.`userID`=us.userID
            WHERE `serial_key` IS NOT NULL 
            GROUP BY deviceID ORDER BY se.timestamp DESC";
		    // $query = "SELECT DISTINCT se.deviceID,se.`modelnum`,sc.grade,sc.school,u.name,se.`ip`,se.`loggedout`,se.`timestamp`,se.`created_timestamp`,se.`updated_timestamp`,se.userID,s.serial_key,s.game,s.platform FROM `session` se 
                      // INNER JOIN `serial_key` s ON s.deviceID = se.deviceID 
                      // INNER JOIN `user` u ON se.userID=u.userID
                      // INNER JOIN `user_school_details`sc ON u.userId=sc.userID
                      // WHERE se.deviceID IS NOT NULL GROUP BY se.deviceID"; 
			$result = mysqli_query($conn,$query);
			return $result;

        }
		
		function Freeuser()
		
		{
			global $conn;
			// $query="SELECT `session`.* FROM `session` WHERE `session`.`deviceID` <> ANY (SELECT `deviceID` FROM `serial_key`) 
			// GROUP BY `session`.`userID` ORDER BY timestamp DESC";
			$query="SELECT * FROM `session` AS a WHERE NOT EXISTS(SELECT * FROM `serial_key` AS b WHERE b.`deviceID` = a.`deviceID`) 
            GROUP BY a.`userID`";
			$result = mysqli_query($conn,$query);
			return $result;

		}
		
		function getuserpassword()
		
		{
			global $conn;
			//$query = "SELECT userID,password From `user_password`";
		   $query = "SELECT p.userID,p.password,u.name,u.email,u.city,u.mobile,u.dob,u.timestamp FROM user_password p
           INNER JOIN `user` u ON u.userID=p.userID ORDER BY `timestamp` DESC";
			$result = mysqli_query($conn,$query);
			return $result;
			
		}
		
		
		function getstudentInfo()
		
		{
			global $conn;
		   $query = " SELECT DISTINCT s.userid,se.grade,se.section,s.game,u.name FROM `session` s
           INNER JOIN  `user_school_details` se ON se.userid=s.`userID`
           INNER JOIN `user` u ON s.userid=u.userid GROUP BY s.userid";
			$result = mysqli_query($conn,$query);
			return $result;
			
		}
		
		function getsound()
		
		{
			global $conn;
		   $query = " SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			INNER JOIN `user` u ON k.userid=u.userid
			INNER JOIN `user_school_details` sc ON sc.userid=k.userid
			WHERE c.chapterID='8P0003'";
			$result = mysqli_query($conn,$query);
			return $result;
			
		}
		
		function getFriction()
		
		{
			global $conn;
		    $query = "SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			INNER JOIN `user` u ON k.userid=u.userid
			LEFT JOIN `user_school_details` sc ON sc.userid=k.userid
			WHERE c.chapterID='8P0002'";
			$result = mysqli_query($conn,$query);
			return $result;
		}
		
		function getForcePressure()
		
		{
			global $conn;
		    $query = "SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			INNER JOIN `user` u ON k.userid=u.userid
			LEFT JOIN `user_school_details` sc ON sc.userid=k.userid
			WHERE c.chapterID='8P0001'";
			$result = mysqli_query($conn,$query);
			return $result;
		}
		
		function getNatural()
		
		{
			global $conn;
		    $query = "SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			INNER JOIN `user` u ON k.userid=u.userid
			LEFT JOIN `user_school_details` sc ON sc.userid=k.userid
			WHERE c.chapterID='8P0005'";
			$result = mysqli_query($conn,$query);
			return $result;
		}
		
		function getNaturalII()
		
		{
			global $conn;
		    $query = "SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			INNER JOIN `user` u ON k.userid=u.userid
			LEFT JOIN `user_school_details` sc ON sc.userid=k.userid
			WHERE c.chapterID='8P0006'";
			$result = mysqli_query($conn,$query);
			return $result;
		}
		function getsolarSystemI()
		
		{
			global $conn;
		    $query = "SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			INNER JOIN `user` u ON k.userid=u.userid
			LEFT JOIN `user_school_details` sc ON sc.userid=k.userid
			WHERE c.chapterID='8P0009'";
			$result = mysqli_query($conn,$query);
			return $result;
		}
		
		function getsolarSystemII()
		
		{
			global $conn;
		    $query = "SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			INNER JOIN `user` u ON k.userid=u.userid
			LEFT JOIN `user_school_details` sc ON sc.userid=k.userid
			WHERE c.chapterID='8P0010'";
			$result = mysqli_query($conn,$query);
			return $result;
		}
		
		function getsolarSystemIII()
		
		{
			global $conn;
		    $query = "SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			INNER JOIN `user` u ON k.userid=u.userid
			LEFT JOIN `user_school_details` sc ON sc.userid=k.userid
			WHERE c.chapterID='8P0011'";
			$result = mysqli_query($conn,$query);
			return $result;
		}
		
		function getLightI()
		
		{
			global $conn;
		    $query = "SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			INNER JOIN `user` u ON k.userid=u.userid
			LEFT JOIN `user_school_details` sc ON sc.userid=k.userid
			WHERE c.chapterID='8P0007'";
			$result = mysqli_query($conn,$query);
			return $result;
		}
		
		
		function getLightII()
		
		{
			global $conn;
		    $query = "SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			INNER JOIN `user` u ON k.userid=u.userid
			LEFT JOIN `user_school_details` sc ON sc.userid=k.userid
			WHERE c.chapterID='8P0008'";
			$result = mysqli_query($conn,$query);
			return $result;
		}
		
		function getChemical ()
		
		{
			global $conn;
		    $query = "SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			INNER JOIN `user` u ON k.userid=u.userid
			LEFT JOIN `user_school_details` sc ON sc.userid=k.userid
			WHERE c.chapterID='8P0004'";
			$result = mysqli_query($conn,$query);
			return $result;
		}
		// function getsolarSystemI()
		
		// {
			// global $conn;
		    // $query = "SELECT c.chapterID,k.userid,c.chapter,u.name,sc.grade FROM chapter c 
			// INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID 
			// INNER JOIN `user` u ON k.userid=u.userid
			// LEFT JOIN `user_school_details` sc ON sc.userid=k.userid
			// WHERE c.chapterID='8P00010'";
			// $result = mysqli_query($conn,$query);
			// return $result;
		// }
		
		function Addsubject($objsub)
		{
			global $conn;
			$query="INSERT INTO subject(subject) values('".$objsub->subject."')";
			$result = mysqli_query($conn,$query);
			return  mysqli_insert_id($conn);
	   }
	   
	   function Editsubject($subject,$id)
		{
			global $conn;
			$query="UPDATE subject SET  subject='$subject' WHERE subjectID='".$id."'";
			$result = mysqli_query($conn,$query);
			return  mysqli_insert_id($conn);
	   }
	   
	   function getsubjectinfo($id)
	   {
  	    global $conn;

		$query = "SELECT * FROM subject WHERE subjectID = ".$id."";
        $result = mysqli_query($conn, $query);
		$rows = mysqli_fetch_object($result);
		return $rows;
	   }
	   
	   function getsubject()
		
		{
			global $conn;
			$query = "SELECT * From `subject` order by subjectID desc";
			$result = mysqli_query($conn,$query);
			return $result;
			
		}
		
		function deletesubject($id)
	    {
			 global $conn;
			 $query  = "DELETE FROM subject Where subjectID= $id";
			 mysqli_query($conn,$query);
	   }
     
       function Addchapter($objchap)
		{
			global $conn;
			echo $query="INSERT INTO chapter(chapter,subjectID,grade,exam) values('".$objchap->chapter."','".$objchap->subjectID."','".$objchap->grade."','".$objchap->exam."')";
			$result = mysqli_query($conn,$query);
			return  mysqli_insert_id($conn);
	   }	 
		
		
		function grade($id)
		
		{
			global $conn;
			$query="SELECT * FROM user_school_details WHERE userID='".$id."'";
			$result=mysqli_query($conn,$query);
			return $result;
		}
		
		function getviewuserDetails($ID)
		{
			global $conn;

			$query = "SELECT * From `user` WHERE userID='".$ID."'";
			$result = mysqli_query($conn, $query);
			return $result;	  
		}
		
		function getsessionDetails($ID)
		{
			global $conn;
			//$query = "SELECT * From `session` WHERE userID='".$ID."'";
			$query="SELECT u.userID,u.name,s.`game`,s.`version`,s.`deviceID`,s.`modelnum`,s.`timestamp` FROM `user` u
            INNER JOIN `session` s ON s.userID=u.userID WHERE u.userID='".$ID."'";
			$result = mysqli_query($conn, $query);
			return $result;
		}
		
	  function TimeSpent($id)
	  {
			global $conn;
			$query= "SELECT
			userID,TIME_FORMAT(SEC_TO_TIME(`time`),'%HH : %iM : %SS') AS `times`
			FROM game_time_spent WHERE `userID`= '".$id."'";
			$result=mysqli_query($conn,$query);
			return $result;
			
                // $query= "SELECT
                // u.userID,TIME_FORMAT(SEC_TO_TIME(`time`),'%Hh : %im : %Ss') FROM game_time_spent t
                // INNER JOIN `user` u ON u.userID=t.userID WHERE u.userID='".$id."'";
			   

		}
		
		function TotalQuestion($id)
	    {
			global $conn;
			$query= "SELECT * FROM `user_attempted_snailrush_question` WHERE `userID` ='".$id."' GROUP BY `questionID`";
			$result=mysqli_query($conn,$query); 
			mysqli_num_rows($result);
			return $result;
            
		}
		
		function Learningclass($id)
		{
			global $conn;
			$query="SELECT * FROM user_school_details WHERE userID='".$id."'";
			$result=mysqli_query($conn,$query);
			return $result;
		}
		
		function chapterList($id)
		{
			global $conn;
			$query="SELECT c.chapter,k.userID,k.time,k.chapterID FROM chapter c
            INNER JOIN `knowledgekombat_chapter_time` k ON c.chapterID=k.chapterID WHERE userID='".$id."'";
			$query="SELECT u.userID,k.chapterID,c.chapter,s.rank,t.time, SUM(s.`score`) AS score FROM `knowledgekombat_chapter_unlock`k
			INNER JOIN `user` u ON u.userID=k.userID
			INNER JOIN chapter c ON c.chapterID=k.chapterID
			INNER JOIN knowledgekombat_chapter_time t ON c.chapterID=t.chapterID
			INNER JOIN snailrush_oneday_winner s ON u.userID=s.userID  WHERE u.userID='".$id."' GROUP BY u.userID ,c.chapterID";
			$result=mysqli_query($conn,$query);
			return $result;
		}
		
		// function winnerRank($id)
		// {
			// global $conn;
			// $query="SELECT rank,score FROM snailrush_oneday_winner WHERE userID='".$id."' ";
			// $result=mysqli_query($conn,$query);
			// return $result;
		// }
	   
	   function accuracy($id)
	   {
		    global $conn;
		    $query="SELECT accuracy FROM snailrush_oneday_winner WHERE userID='".$id."' GROUP BY userID ";
		    $result=mysqli_query($conn,$query);
			return $result;
		}
		
		function level($id)
	   {
			global $conn;
			$query="SELECT level FROM snailrush_level WHERE userID='".$id."' ";
			// $query="SELECT l.userID,LEVEL,t.time FROM snailrush_level l
			// INNER JOIN `game_time_spent` t ON t.userID=l.userID WHERE userID='".$id."'";
			 $result=mysqli_query($conn,$query);
			 return $result;
		}
	   
  }	
		
		
	?>