<?php 
	session_start();
	include('include/db_config.php');

	// if(empty($_SESSION['uid'])){
		// header('Location:index.php');
	// }
	$id=$_GET['id'];
	$sql ="SELECT u.userid,u.name,u.city,u.email,u.mobile,u.country,u.dob,s.school,s.grade,TIME_FORMAT(SEC_TO_TIME(`time`),'%HH : %iM : %SS') AS times FROM `user` u
	LEFT JOIN `user_school_details` s ON s.userid=u.userid 
	LEFT JOIN `game_time_spent` t ON t.userid=u.userid 
	where u.userid='".$id."' ";
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($result);
	$_SESSION['currentusers'] = $row;
	$currentUser  = $_SESSION['currentusers'];
	
  // print_r($_SESSION['currentuser']);	
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>EduisFun</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="../css/style.css" rel="stylesheet">
</head>
<body>
	<div class="container-fluid">

        <nav class="navbar navbar-default nav_height">
            
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
					<img src="images/logo.png">
                </div>
                <div id="navbar" class="navbar-collapse collapse Sb-navbar_padd ">
                    <ul class="nav navbar-nav sb_navbar_top" id="navPrincipal">
                       <!-- <li><a href="dashboard.php"><span><img src="images/left_arrow.png"></span>go back</a></li>-->
                        <li><a href="view-user.php"><span><img src="images/left_arrow.png"></span>Go back</a></li>
                     
                    </ul>
                </div>
            
        </nav>
		</div>
	
	<div class="container-fluid">
		<div class="section1">
		
		<div class="row">
		<div class="col-md-6">
		<div class="form_content">
		 <form class="form-horizontal" action="" method="post">
		
            <div class="form-group">
              <label class="col-md-2 control-label" for="name"> Name  </label>
			  <label class="col-md-1 control-label">:</label>
              <div class="col-md-9">
                <input id="name" name="name" type="text" placeholder="" value="<?php echo $currentUser['name']; ?>" class="form-control inp-field" readonly>
		
              </div>
            </div>
			 <div class="form-group">
              <label class="col-md-2 control-label" for="name"> Class </label>
			  <label class="col-md-1 control-label">:</label>
              <div class="col-md-9">
                <input id="name" name="name" type="text" placeholder="" value="<?php echo $currentUser['grade']; ?>" class="form-control inp-field" readonly>
		
              </div>
            </div>
			 <div class="form-group">
              <label class="col-md-2 control-label" for="name"> City </label>
			  <label class="col-md-1 control-label">:</label>
              <div class="col-md-9">
                <input id="name" name="name" type="text" placeholder="" value="<?php echo $currentUser['city']; ?>" class="form-control inp-field" readonly>
		
              </div>
            </div>
			</form>
		</div>
		</div>
		<?php
		$questiontime = "SELECT TIME_FORMAT(SEC_TO_TIME(ROUND(SUM(timePerQuestion)/COUNT(timePerQuestion))),'%HH : %iM : %SS') AS `timePerQuestion` FROM knowledgekombat_chapter_time  WHERE  timePerQuestion > 0 AND userID ='".$_SESSION['currentusers']['userid']."'";		
		$questionresult = mysqli_query($conn,$questiontime);
		$questionrow = mysqli_fetch_array($questionresult);
			
			?>
		<div class="col-md-6">
			<ul>  
				<li class="col-md-4 col-sm-12 col-xs-12">
				<div class="time_content">
				<div class="time">
				 
						<h2 class="tdtime">
						<?php 
						if ($questionrow['timePerQuestion']=="") 
						{	
							echo "0" ;
						} else { 
							echo $questionrow['timePerQuestion'];
						} ?> 
						
						</h2>
					 
					
				</div>
				</div>
				 
				<p class="time_cont_para">TIME PER QUESTIONS</p>
			</li>
		 
			
			<?php 
			$tqasql = "select SUM(total_questions_answered) AS Totals from knowledgekombat_chapter_time  where userID='".$_SESSION['currentusers']['userid']."'";		
			$tqaresult = mysqli_query($conn,$tqasql);
			$tqarow = mysqli_fetch_array($tqaresult);	  
			
				?>
			
			<li class="col-md-4 col-sm-12 col-xs-12">
				<div class="time_content">
				<div class="time"> 
					<h2>
					<?php 
						if ($tqarow['Totals']=="") 
						{	
							echo "0" ;
						} else { 
							echo $tqarow['Totals'];
						} ?> 
					</h2>
				</div>
				
				</div>
				 
				<p class="time_cont_para">TOTAL QUESTIONS ANSWERED</p>
			</li>
			 										
			<li class="col-md-4 col-sm-12 col-xs-12">
			<?php 					
			$overallquery  = "SELECT ROUND(SUM(knowledgeCurrency)) as knowledgeCurrency FROM `knowledgekombat_treasure_attempt` WHERE `userID` ='".$_SESSION['currentusers']['userid']."'";					
			$overallresult = mysqli_query($conn,$overallquery);                 	
			$overallrow = mysqli_fetch_assoc($overallresult);			
			?>			
			<div class="circle">				
			<div class="time">					
			 					
			<h2>
			
				<?php 
						if ($overallrow['knowledgeCurrency']=="") 
						{	
							echo "0" ;
						} else { 
							echo $overallrow['knowledgeCurrency'];
						} 
						?> 
			
			</h2>					
			 								
			</div>								
			</div>				
			<p class="cont_para1">Knowledge Currency</p>			
			</li>	
</ul>			
		</div>
		</div>
		
		</div>
		
	</div>
<div class="container-fluid">
	<div class="section2">
		<div class="row">
		<div class="">
		<ul>
			<li class="col-md-2 col-sm-12 col-xs-12">
		 
			<p class="learning_para">LEARNING</p>
			<h2 class="learning_head">CLASS <?php 
			
			$romanValues=array('I' => 1,'II' => 2,'III' => 3,'IX' => 4,'V' => 5,'VI' => 6,'VII' => 7,'VIII' => 8,'IX' => 9,'X' => 10,'XI' => 11,'XII' => 12,);	
			foreach ($romanValues as $key=>$chapterid){	
			if ($currentUser['grade']==$key) {	
			
			
			echo ordinal($chapterid);
			
			}
			}
			?></h2>
			<p class="learning_para">MATHS + SCIENCE</p>
			 
			</li>
			<li class="col-md-2 col-sm-12 col-xs-12">
			 
				<?php 
				$overallquery  = "SELECT ROUND(SUM(progress)/COUNT(progress)) as overall_progress FROM `knowledgekombat_chapter_time` WHERE `userID` ='".$_SESSION['currentusers']['userid']."'";					
				$overallresult = mysqli_query($conn,$overallquery);                 	
				$overallrow = mysqli_fetch_assoc($overallresult);			
				?>
			<div class="circle">
				<div class="time">
					 
					<h2>

					<?php 
						if ($overallrow['overall_progress']=="") 
						{	
							echo "0" ;
						} else { 
							echo $overallrow['overall_progress'];
						} 
						?> 
					
					
					<br/><span></span></h2>
					<p>percent</p>
					
					 
				 
				</div>
				
				</div>
				<p class="cont_para1">OVERALL PROGRESS</p>
				 
			</li>
 
			<?php
				$totaltime = "SELECT TIME_FORMAT(SEC_TO_TIME(ROUND(SUM(`time`))),'%HH : %iM : %SS') AS `time` FROM knowledgekombat_chapter_time  WHERE  userID='".$_SESSION['currentusers']['userid']."'";		
				$totalresult = mysqli_query($conn,$totaltime);			
				$timetotalrow = mysqli_fetch_array($totalresult);	 
			
			?>
			<li class="col-md-2 col-sm-12 col-xs-12">
					<div class="time_content">
					
				<div class="time">
					 
						<h2 class="tdtime">
						<?php 
							if ($timetotalrow['time']=="") 
							{	
								echo "0" ;
							} else { 
								echo $timetotalrow['time'];
							} 
						?> 
						
						
						</h2>
				 
					
				</div>
				
				</div>
				<p class="time_cont_para">TOTAL TIME SPENT</p>
				
			</li>
			 
			
		<li class="col-md-2 col-sm-12 col-xs-12">
		
				<?php 
					$query  = "SELECT  ROUND(SUM(accuracy)/COUNT(accuracy)) as  overall_accuracy FROM `knowledgekombat_skill` kks INNER JOIN `knowledgekombat_skill_attempt` kksa ON (kks.`skillID`=kksa.`skillID`) WHERE kksa.`userID` ='".$_SESSION['currentusers']['userid']."'";				
					$result = mysqli_query($conn,$query);				
					$row2 = mysqli_fetch_assoc($result);			
				?>
			
		
			<div class="circle">													
				<div class="time">
					 
					<h2>
					
					<?php 
						if ($row2['overall_accuracy']=="") 
						{	
							echo "0" ;
						} else { 
							echo $row2['overall_accuracy'];
						} 
						?> 
					
					
					
					</h2>
					   
				</div>
				
			</div>
				<p class="cont_para1">OVERALL ACCURACY</p>
		
			</li>
			
			<?php 	
                   $i=1;			
				$overallrankquery  = "SELECT ROUND(SUM(ksa.learningCurrency)/COUNT(ksa.learningCurrency) + SUM(kta.knowledgeCurrency)/COUNT(kta.knowledgeCurrency) ) AS  rank,kta.userID as userid FROM `knowledgekombat_skill_attempt` ksa INNER JOIN  knowledgekombat_treasure_attempt kta ON  ksa.userID=kta.userID   GROUP BY kta.userID ORDER BY rank DESC";				
				$overallresult = mysqli_query($conn,$overallrankquery);
				while ($overallrow = mysqli_fetch_assoc($overallresult)){
					if ($overallrow['userid']==$_SESSION['currentusers']['userid']){
					$overallrank = $i;	
					}	
					
				$i++;	
				}
                
			?>
			<li class="col-md-2 col-sm-12 col-xs-12">
			 
		
			<h4 class="learning_head2"><?php
               
						if($overallrank=="") 
						{	
							echo "0" ;
						} else { 
							echo $overallrank;
						} ?> 

		              </h4>
			
			 
			<p class="cont_para1">OVERALL RANK</p>
		
			</li>			
		
			</li>						
			 							
			<?php					
			 $query  = "SELECT  ROUND(SUM(learningCurrency)) as  learningCurrency FROM `knowledgekombat_chapter_time` WHERE `userID` ='".$_SESSION['currentusers']['userid']."'";					
			 $result = mysqli_query($conn,$query);					
			 $row3 = mysqli_fetch_assoc($result);				
			 ?>			
		<li class="col-md-2 col-sm-12 col-xs-12">
			  					
			 <div class="circle">
			 <div class="time">	 					
			 <h2>
			 
				<?php 
						if ($row3['learningCurrency']=="") 
						{	
							echo "0" ;
						} else { 
							echo $row3['learningCurrency'];
						} 
				?> 
			 
			 </h2>					 					 					 										 			</div>	
			 </div>			
			 <p class="cont_para1">Learning Currency</p>				
			 </li>
			
			</ul>
		</div>
		</div>
	</div>
</div>
<div class="container-fluid">
<div class="table-responsive">    
		<table class="table table-bordered main_table">
    <thead>
      <tr>
        <th>CHAPTERS</th>
        <th>PROGRESS</th>
        <th>TIME</th>
        <th>SCORE</th>
        <th>RANK</th>
      </tr>
    </thead>
    <tbody>
	 <?php	 
	 //$romanValues=array(			'I' => 1,			'II' => 2,			'III' => 3,			'IX' => 4,			'V' => 5,			'VI' => 6,			'VII' => 7,			'VIII' => 8,			'IX' => 9,			'X' => 10,			'XI' => 11,		);		foreach ($romanValues as $key=>$chapterid){						if ($currentUser['grade']==$key) {				
	 
	 
		$sql = "SELECT * FROM `knowledgekombat_chapter_time` kct INNER JOIN `chapter` c ON kct.`chapterID` = c.`chapterID` 
		WHERE `userID` ='".$_SESSION['currentusers']['userid']."' ORDER BY c.`chapter` ASC ";		

	//	}								}	
	   $result = mysqli_query($conn,$sql);	
      // $row1 = mysqli_fetch_array($result);     	
	   while($row1 = mysqli_fetch_array($result)){
	 ?>
	<tr>
	<td style="width: 400px"><?=$row1['chapter']; ?></td>
	 
		<?php 	
			$progresssql = "select ROUND(SUM(progress)/COUNT(progress)) as  progress  from knowledgekombat_chapter_time  where chapterID ='".$row1['chapterID']."' and userID='".$_SESSION['currentusers']['userid']."'";		
			$progressresult = mysqli_query($conn,$progresssql);
			$progresserow = mysqli_fetch_array($progressresult);	  
			$progresserow['progress']; 
		
			?>
	
        <td style="width: 270px">
			<div class="col-sm-10">
				<div class="progress">
					<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $progresserow['progress'];?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $progresserow['progress'];?>%">
					</div>
				</div>
			</div>
			<div class="col-sm-1">
				<span><?php echo $progresserow['progress'];?>%</span>
			</div>
		</td>		<?php $timesql = "select TIME_FORMAT(SEC_TO_TIME(`time`),'%iM : %SS') AS time from knowledgekombat_chapter_time  where chapterID ='".$row1['chapterID']."' and userID='".$_SESSION['currentusers']['userid']."'";		$timeresult = mysqli_query($conn,$timesql);			$timerow = mysqli_fetch_array($timeresult);	   ?>	   	   		
        <td style="width: 105px;"><?=$timerow['time']; ?></td>
         <td class="text-center" style="width: 105px;">
		<span class="score"><?php 	
			$scoresql = "select ROUND(SUM(learningCurrency)/COUNT(learningCurrency)) as  learningCurrency  from knowledgekombat_chapter_time  where chapterID ='".$row1['chapterID']."' and userID='".$_SESSION['currentusers']['userid']."'";		
			$scoreresult = mysqli_query($conn,$scoresql);
			$scorerow = mysqli_fetch_array($scoreresult);	  
			echo $scorerow['learningCurrency']; 
		
			?></span>
		</td>
        <td style="width: 105px;"><?php 	
			$ranksql = "SELECT * , FIND_IN_SET( learningCurrency, (SELECT GROUP_CONCAT( learningCurrency ORDER BY learningCurrency DESC ) 
						FROM knowledgekombat_chapter_time ) ) AS rank FROM knowledgekombat_chapter_time where chapterID ='".$row1['chapterID']."' and userID='".$_SESSION['currentusers']['userid']."'";
			$rankresult = mysqli_query($conn,$ranksql);
			$rankrow = mysqli_fetch_array($rankresult);	 
			echo $rankrow['rank']; 
			?></td>
      </tr>
	<?php	}	?>
    </tbody>
  </table>

</div>
</div>

     
<div class="container-fluid">
	<div class="section2">
		<div class="row">
		<ul>
		
			<li class="col-md-5th-1 col-sm-12 col-xs-12">
			<div class="first">
			
			<h2 class="learning_head">OLYMPIAD</h2>
			<p class="learning_para"><?php 
			
			$romanValues=array('I' => 1,'II' => 2,'III' => 3,'IX' => 4,'V' => 5,'VI' => 6,'VII' => 7,'VIII' => 8,'IX' => 9,'X' => 10,'XI' => 11,'XII' => 12,);	
			foreach ($romanValues as $key=>$chapterid){	
			if ($currentUser['grade']==$key) {	
			
			
			echo ordinal($chapterid);
			
			}
			}
			?></h2>
			</div>
			</li>
			
			<?php
				$levelsql = "select ROUND(SUM(level)/COUNT(level)) as  level  from snailrush_level  where  userID ='".$_SESSION['currentusers']['userid']."'";		
				$leveleresult = mysqli_query($conn,$levelsql);
				$levelrow = mysqli_fetch_array($leveleresult);	
			?>
			
			<li class="col-md-5th-1 col-sm-12 col-xs-12">
	
			<div class="circle">
				<div class="time">
					 
					 
				 
				 
					 
					<h2><?php 
						if ($levelrow['level']=="") 
						{	
							echo "0" ;
						} else { 
							echo $levelrow['level'];
						} ?>  </h2>
					 
				</div>
				
				</div>
				<p class="cont_para1">Level</p>
			</li>
			
			
			<?php
			    $totaltimes = "SELECT TIME_FORMAT(SEC_TO_TIME(SUM(`time`)),'%HH : %iM : %SS') AS `times` FROM game_time_spent  WHERE  userID='".$_SESSION['currentusers']['userid']."'";		
				$totalresults = mysqli_query($conn,$totaltimes);			
				$timetotalrows = mysqli_fetch_array($totalresults);	 
			
			?>
			<li class="col-md-5th-1 col-sm-12 col-xs-12">
					<div class="time_content">
					
				<div class="time">
					 
						<h2 class="tdtime"><?php 
							if ($timetotalrows['times']=="") {
								echo "0" ;
							} else {
								echo $timetotalrows['times'];
							} ?></h2>
					 
					
				</div>
				
				
				</div>
				<p class="time_cont_para">TOTAL TIME SPENT</p>
		
			</li>
			
			<?php 
			$overaccuracy = "SELECT userID, AVG(CASE WHEN selected_choice=correct_choice THEN 1 ELSE 0 END) * 100 AS accuracy FROM 
			snailrush_question NATURAL JOIN user_attempted_snailrush_question WHERE userid ='".$_SESSION['currentusers']['userid']."'";	
			//$overaccuracy = "SELECT  ROUND(SUM(accuracy)/COUNT(accuracy)) as  overall_accuracy FROM `snailrush_oneday_winner`  WHERE `userID` ='".$_SESSION['currentuser']['userid']."'";				
			$accurcyresult = mysqli_query($conn,$overaccuracy);				
			$rowaccuracy = mysqli_fetch_assoc($accurcyresult);			
			?>

			 
		 
			<li class="col-md-5th-1 col-sm-12 col-xs-12">
			<div class="circle">
				<div class="time">
					 
					<h2><?php echo round($rowaccuracy['accuracy']); ?></h2>
				 
				 
					<p>percent</p>  
					 
				</div>
				
			</div>
				<p class="cont_para1">OVERALL ACCURACY</p>
		
			</li>
			<?php 	
            $i=1;			
			   $allrank  = "SELECT ROUND(SUM(coins))  AS  rank,userID AS userid FROM `snailrush_coins_gained` GROUP BY userID ORDER BY rank DESC";				
				$allresult = mysqli_query($conn,$allrank);
				while ($allrow = mysqli_fetch_assoc($allresult)){
					if ($allrow['userid']==$_SESSION['currentusers']['userid']){
					$overallranks = $i;	
					}	
					
				$i++;	
				}
                
			?>
			<li class="col-md-5th-1 col-sm-12 col-xs-12">
			 
		
			<h4 class="learning_head2"> 
			
			<?php 
						if($overallranks=="") 
						{	
							echo "0" ;
						} else { 
							echo $overallranks;
						} ?> 
			
			
			</h4>
			
			 
			<p class="cont_para1">OVERALL RANK</p>
		
			</li>
		 
			</ul>
 
	</div>
</div>

</html>
<?php
function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13)) {
        return $number. '<sup>th</sup>';
	}  else  {
        return $number. '<sup>'.$ends[$number % 10].'</sup>';
	}	
}
//Example Usage
?>

