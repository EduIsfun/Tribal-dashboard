<?php 
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include('db_config.php');
include('model/Teacher.class.php');
global $conn;
$teacher = new Teacher();
$id = isset($_GET['id'])?$_GET['id']:'';

$result = $teacher->getStudentresult($id);
$studentobj = mysqli_fetch_object($result);
$userID=$currentUser = $studentobj->userID;
$classinfo=$teacher->getClass($userID);
if (count($classinfo->class_enabled)>0){
	$gradeforsel = $classinfo->class_enabled;
} else {
	$gradeforsel = $classinfo->grade;	
}	
$classesarray = explode(",",$gradeforsel);

if (isset($_POST) && (count($_POST)>0)) {
	$selectgrade = $_POST['gradeenabled'];
} else {
	$querygrd = "SELECT c.grade FROM `chapter` c INNER JOIN knowledgekombat_skill ks  ON ks.`chapterID`=c.`chapterID`
	INNER JOIN knowledgekombat_skill_attempt ksa  ON ks.`skillID`=ksa.`skillID` WHERE ksa.`userID` ='".$currentUser."'
	ORDER BY c.grade ASC LIMIT 1 ";
	$resultgrd = mysqli_query($conn,$querygrd);
	$rowgrd = mysqli_fetch_object($resultgrd);
	$selectgrade =$rowgrd->grade; 
}	
if (!empty($classinfo->school_id)){
	$schoolinfo= $teacher->getschoolbycode($classinfo->school_id);
	$schoolname =$schoolinfo->school;
} else {
	$schoolname =$classinfo->school;
}	

$cityinfo=$teacher->get_userwise_city($userID);

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
  <link href="css_student/style.css" rel="stylesheet">
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
    <div id="navbar" class="navbar-collapse collapse Sb-navbar_padd">
		<ul class="nav navbar-nav sb_navbar_top" id="navPrincipal">
			<li><a href="principaldashboard.php"><span><img src="images/home.png"></span>Dashboard</a></li>
        </ul>
    </div>
    </nav>
</div>
<div class="container-fluid">
	<div class="section1">
		<div class="row">
			<div class="col-md-6">
				<div class="form_content">
				<form class="form-horizontal" action="" method="post" id="userdetails">
					<div class="form-group">
						<label class="col-md-2 control-label" for="name"> Name  </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-9">
							<input id="name" name="name" type="text" placeholder="" value="<?php echo $studentobj->name; ?>" class="form-control inp-field" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="name"> Class </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-9">
						 <select id="gradeenabled" name="gradeenabled" class="form-control inp-field">
						 <?php foreach ($classesarray as $key=>$value) { 
								if ($selectgrade==$value) { $selected ="selected";} else { $selected='';}
								$grdq= "SELECT count(*) as countgrd FROM knowledgekombat_skill_attempt ksa INNER JOIN
								knowledgekombat_skill ks ON ksa.skillID=ks.skillID INNER JOIN chapter c ON
								c.chapterID=ks.chapterID  WHERE (c.grade='".$value."' or 
								c.grade='".Romannumeraltonumber($value)."') AND userID ='".$currentUser."'";
								$grdres = mysqli_query($conn,$grdq);
								$grdrow = mysqli_fetch_object($grdres);
								if ($grdrow->countgrd>0){
									echo '<option value='.$value.' '.$selected.'>'.$value.'</option>';
								}
							}		
						?>
						</select>
							<!--<input id="name" name="name" type="text" placeholder="" value="<?php //echo $classinfo->class_enabled; ?>"class="form-control inp-field" readonly>-->
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="name"> City </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-9">
							<input id="name" name="name" type="text" placeholder="" value="<?php echo $studentobj->city?>" class="form-control inp-field" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="name"> School </label>
						<label class="col-md-1 control-label">:</label>
						<div class="col-md-9">
							<input id="name" name="school" type="text" placeholder="" value="<?php echo $schoolname?>" class="form-control inp-field" readonly>
						</div>
					</div>
				</form>
				</div>
			</div>
		<?php
		$questiontime = "SELECT SUM(timePerQuestion)/COUNT(timePerQuestion) AS `timePerQuestion` FROM 
		knowledgekombat_skill_attempt ksa INNER JOIN knowledgekombat_skill ks ON ksa.skillID=ks.skillID INNER JOIN
		chapter c ON c.chapterID=ks.chapterID  WHERE timePerQuestion > 0 AND c.grade='".$selectgrade."'	AND 
		userID 	='".$currentUser."'";
		
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
							if ($questionrow['timePerQuestion']==""){	
								echo "0 Secs";
							} else { 
								echo number_format($questionrow['timePerQuestion'] , 2, '.', '')." Secs";	
							} ?> 
							</h2>
						</div>
					</div>
				 	<p class="time_cont_para">TIME PER QUESTIONS</p>
					</li>
					<?php
					$totaltime = "SELECT TIME_FORMAT(SEC_TO_TIME(ROUND(SUM(`time`))),'%HH : %iM : %SS') AS `time` FROM
					knowledgekombat_chapter_time  kct INNER JOIN chapter c ON c.chapterID=kct.chapterID WHERE 
					userID='".$currentUser."' AND c.grade='".$selectgrade."'";		
					
					$totalresult = mysqli_query($conn,$totaltime);			
					$timetotalrow = mysqli_fetch_array($totalresult);	 
					?>
					<li class="col-md-4 col-sm-12 col-xs-12">
					<div class="time_content">
						<div class="time">
							<h2 class="tdtime">
							<?php 
								if ($timetotalrow['time']==""){	
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
		 			<?php 
					$tqasql = "select SUM(total_questions_answered) AS Totals from knowledgekombat_chapter_time  
					kct INNER JOIN chapter c ON c.chapterID=kct.chapterID WHERE userID='".$currentUser."' AND 
					c.grade='".$selectgrade."'";		
					$tqaresult = mysqli_query($conn,$tqasql);
					$tqarow = mysqli_fetch_array($tqaresult);	  
					?>
					<li class="col-md-4 col-sm-12 col-xs-12">
					<div class="time_content">
						<div class="time"> 
							<h2>
							<?php 
							if ($tqarow['Totals']==""){	
								echo "0" ;
							} else { 
								echo $tqarow['Totals'];
							} ?> 
							</h2>
						</div>
					</div>
				 	<p class="time_cont_para">TOTAL QUESTIONS ANSWERED</p>
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
					<h2 class="learning_head">CLASS <?php echo numberToRomanRepresentation($selectgrade);//$classinfo->grade;?>
					</h2>
					<p class="learning_para">MATHS + SCIENCE</p>
					</li>
					<li class="col-md-2 col-sm-12 col-xs-12">
			 		<?php 					
					$overallquery  = "SELECT ROUND(SUM(progress)/COUNT(progress)) as overall_progress FROM 
					knowledgekombat_chapter_time kct INNER JOIN chapter c ON c.chapterID=kct.chapterID WHERE 
					userID='".$currentUser."' AND c.grade='".$selectgrade."'";		
					$overallresult = mysqli_query($conn,$overallquery);
					$overallrow = mysqli_fetch_assoc($overallresult);
					?>
					<div class="circle">
						<div class="time">					 
							<h2>
							<?php 
								if ($overallrow['overall_progress']=="") {	
									echo "0" ;
								} else { 
									echo $overallrow['overall_progress'];
								} 
							?> 
							<br/><span></span></h2>
							<p>percent</p>				 
						</div>				
					</div>
					<p class="cont_para1">TOTAL CHAPTER COMPLETION</p>
					</li>
					
					
					
					<li class="col-md-2 col-sm-12 col-xs-12"> 
						<div class="circle">
						<div class="time">
					<?php 	
					$classrankquery="SELECT userid, totalcount, rank FROM ( SELECT userid, totalcount, @r := IF(@c = totalcount, @r, @r + 1) rank, 
					@c := totalcount  FROM ( SELECT ksa.userID, ((IFNULL(((AVG(ksa.learningCurrency)*20)/100),0))+ (IFNULL(((AVG(kta.knowledgeCurrency)*80)/1000),0))) AS totalcount FROM
					knowledgekombat_skill_attempt  ksa INNER JOIN `user_school_details` usd ON ksa.`userID`=usd.`userID` 
					left JOIN `knowledgekombat_treasure_attempt` kta ON kta.`userID`= usd.`userID` 
					INNER JOIN knowledgekombat_skill ks ON ksa.skillID=ks.skillID 
					INNER JOIN chapter c ON c.chapterID=ks.chapterID ";    
					if (!empty($classinfo->school_id)){
						$classrankquery.=" INNER Join school_master sm on sm.school_code= usd.school_id";
					}
					$classrankquery.=" WHERE 1=1 AND usd.city_id ='".$cityinfo->id."' AND
					c.grade ='".$selectgrade."' "; 
					if (!empty($classinfo->school_id)){
						$classrankquery.="  AND sm.school = '".$schoolname."'";
					} else {
						$classrankquery.="  AND usd.school = '".$schoolname."'";
					}
					
					$classrankquery.="  GROUP BY userid ORDER BY totalcount DESC) t CROSS JOIN ( SELECT @r := 0, 
					@c := NULL  ) i ) q";
					//echo $classrankquery;
					$classrankresult = mysqli_query($conn,$classrankquery);
					$classrankar =  array();
					$totalcrank =0;
					while($classrankobj = mysqli_fetch_object($classrankresult)){
						if ( $classrankobj->userid==$currentUser){
							$classrank=$classrankobj->rank;
						}	
						$totalcrank++;
					}
						
					if($classrank=="") {	
						echo "0" ;
					} else { 
						echo "<h2 style='font-size: 20px; text-align: center; padding-top: 15px; }'>";
						echo $classrank;
						echo "<br>---------<br>";
						echo $totalcrank;
						echo "</h2>";
						//echo $classrank."/".$totalcrank;
					} 
					?> 
					</div></div>					 
					<p class="cont_para1">CLASS RANK</p>
					</li>
					
					<li class="col-md-2 col-sm-12 col-xs-12"> 
					<div class="circle">
						<div class="time">
					<?php 	
					$globalrankquery="SELECT userid, totalcount, rank FROM (SELECT userid, totalcount, @r := IF(@c = totalcount, @r, @r + 1) rank, 
					@c := totalcount  FROM (";
					$globalrankquery.=" SELECT ksa.`userID`,((IFNULL(((AVGAearningCurrency*20)/100),0))+ (IFNULL(((AVGknowledgeCurrency*80)/1000),0))) 
					AS totalcount FROM skillaverage ksa LEFT JOIN treasureavg kta ON kta.`userID`= ksa.`userID` ";
					if (!empty($classinfo->school_id)){
						$globalrankquery.=" INNER Join school_master sm on sm.school_code= usd.school_id";
					}				  
					$globalrankquery.="WHERE 1=1 AND ksa.`userID`!=0 ";
					$globalrankquery.=" GROUP BY userid ORDER BY totalcount DESC) t CROSS JOIN (SELECT
					@r:=0,@c:=NULL)i) q";
					$globalrankresult = mysqli_query($conn,$globalrankquery);
					$totalgrank =0;
					while($globalrankobj = mysqli_fetch_object($globalrankresult)){
						if ( $globalrankobj->userid==$currentUser){
							$globalrank=$globalrankobj->rank;
						}	
						$totalgrank++;
					}
					
					if($globalrank=="") {	
						echo "0" ;
					} else { 
						echo "<h2 style='font-size: 20px; text-align: center; padding-top: 15px; }'>";
						echo $globalrank;
						echo "<br>---------<br>";
						echo $totalgrank;
						echo "</h2>";
						//echo $globalrank."/".$globalrank;
					} 
					?> 
					</div></div>					 
					<p class="cont_para1">GLOBAL RANK</p>
					</li>
			
					<li class="col-md-2 col-sm-12 col-xs-12">
					<div class="circle">
						<div class="time">
						<?php					
						$query ="SELECT * FROM knowledgekombat_skill_attempt WHERE userID ='".$currentUser."' AND
						skillAttemptID IN (SELECT MAX(skillAttemptID) FROM knowledgekombat_skill_attempt ksa 
						INNER JOIN knowledgekombat_skill ks ON ksa.skillID=ks.skillID INNER JOIN chapter c ON 
						c.chapterID=ks.chapterID WHERE userID ='".$currentUser."' AND c.`grade` ='".$selectgrade."'
						GROUP BY ksa.skillID)";
						$result = mysqli_query($conn,$query);
						$totallearningcur = 0;
						$totcount = 0;
						while ($row3 = mysqli_fetch_assoc($result)){
							$totallearningcur=$totallearningcur+$row3['learningCurrency'];
							$totcount++;
						}
						if (($totallearningcur=="") || ($totallearningcur==0)) {	
							echo "<h2>";
							echo "0" ;
							echo "</h2>";
						} else { 
							echo "<h2 style='font-size: 20px; text-align: center; padding-top: 15px; }'>";
							echo $totallearningcur;
							echo "<br>---------<br>";
							echo $totcount*100;
							echo "</h2>";
						} 
						?>
						</div>	
					</div>			
					<p class="cont_para1">TOTAL LEARNING CURRENCY</p>				
					</li>
					<li class="col-md-2 col-sm-12 col-xs-12">
					<?php 					
					$overallquery="SELECT ROUND(SUM(knowledgeCurrency)) as knowledgeCurrency,count(knowledgeCurrency)
					as outof_knowledgeCurrency FROM `knowledgekombat_treasure_attempt` kta INNER JOIN chapter c ON
					c.chapterID=kta.chapterID WHERE userID='".$currentUser."' AND c.grade='".$selectgrade."'";		
					$overallresult = mysqli_query($conn,$overallquery);
					$overallrow = mysqli_fetch_assoc($overallresult);			
					?>			
					<div class="circle">				
						<div class="time">					
			 			<?php 
						if ($overallrow['knowledgeCurrency']=="") {	
							echo "<h2>";
							echo "0" ;
							echo "</h2>";
						} else { 
							echo "<h2 style='font-size: 20px; text-align: center; padding-top: 15px; }'>";
							echo $overallrow['knowledgeCurrency'];
							echo "<br>---------<br>";
							if ($overallrow['outof_knowledgeCurrency']>1) {	
								$outof_knowledgeCurrency=$overallrow['outof_knowledgeCurrency']*1000;		
								echo $outof_knowledgeCurrency;
							} else {
								echo "1000";
							}
							echo "</h2>";
						} 
						?>							
						</div>								
					</div>				
					<p class="cont_para1">TOTAL KNOWLEDGE CURRENCY</p>			
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
				<th>NUMBER OF ISLAND</th>
				<th>CHAPTER COMPLETION</th>
				<th>TIME</th>
				<th>KNOWLEDGE CURRENCY</th>
				<th>LEARNING CURRENCY</th>
			  </tr>
			</thead>
			<tbody>
			<?php	 
			$sqlsubject = "SELECT kct.*,c.*,s.* FROM `knowledgekombat_chapter_time` kct INNER JOIN `chapter` c ON
			kct.`chapterID` = c.`chapterID` INNER JOIN `subject` s ON c.`subjectID` = s.`subjectID` WHERE 
			c.grade='".$selectgrade."' and `userID` ='".$currentUser."' GROUP BY s.`subjectID` ORDER BY s.`sequence`
			ASC";
			$resultsubject = mysqli_query($conn,$sqlsubject);
			$acc=0;	
			$chcount =mysqli_num_rows($resultsubject);
			if ($chcount>0){
				while($rowsubject = mysqli_fetch_object($resultsubject)) {
					$subject=$rowsubject->subject;
				?>
					<tr style="background:#acc61b;">
						<td colspan="6"><span style="color:#2a4c7b;"><b>
						<?php echo strtoupper($subject); ?></b></span></td>
					</tr>	
					<?php 
					$sql="SELECT kct.*,c.*,s.* FROM knowledgekombat_chapter_time kct 
					INNER JOIN `chapter` c ON kct.`chapterID` = c.`chapterID` 
					INNER JOIN `subject` s ON c.`subjectID` = s.`subjectID`  
					WHERE c.grade='".$selectgrade."' and `userID` ='".$currentUser."' AND 
					s.subjectID='".$rowsubject->subjectID."' ORDER BY
					c.`chapterID`  ASC";
					$result = mysqli_query($conn,$sql);	 
					while($row1 = mysqli_fetch_array($result)){
					?>
					<tr data-toggle="collapse" data-target="#accordion_<?php echo $acc; ?>" class="clickable collapse-row collapsed">
						<td style="width: 400px"><a style="cursor: pointer;">
						<?=$row1['chapter']; ?></a></td>
						<td style="width: 100px">
						<?php 
						$islandsql = "SELECT DISTINCT(ksa.skillID) FROM
						knowledgekombat_skill_attempt AS ksa INNER JOIN
						knowledgekombat_skill AS ks ON ksa.skillID=ks.skillID WHERE
						ksa.userID='".$currentUser."' AND 
						ks.chapterID='".$row1['chapterID']."' AND 
						ksa.learningCurrency > 0 ";
						$islandresult = mysqli_query($conn,$islandsql);	
						$islandrow = mysqli_fetch_object($islandresult);
						echo mysqli_num_rows($islandresult);
						?>	
						</td>
						<?php 	
						$progresssql="select ROUND(SUM(progress)/COUNT(progress)) as 
						progress from knowledgekombat_chapter_time where 
						chapterID ='".$row1['chapterID']."' and 
						userID='".$currentUser."'";		
						$progressresult = mysqli_query($conn,$progresssql);
						$progresserow = mysqli_fetch_array($progressresult);	  
						$progresserow['progress'];
						?>	
						<td style="width: 270px">
						<div class="col-sm-10"><div class="progress">
						<div class="progress-bar progress-bar-info" role="progressbar" 
						aria-valuenow="<?php echo $progresserow['progress'];?>"
						aria-valuemin="0" aria-valuemax="100" 
						style="width:<?php echo $progresserow['progress'];?>%">
						</div>
						</div></div>
						<div class="col-sm-1">
							<span><?php echo $progresserow['progress'];?>%</span>
						</div>
						</td>		
						<?php 
						$timesql="SELECT TIME_FORMAT(SEC_TO_TIME(`time`),'%HH : %iM :
						%SS') AS time FROM knowledgekombat_chapter_time  where
						chapterID ='".$row1['chapterID']."' and 
						userID='".$currentUser."'";		
						$timeresult = mysqli_query($conn,$timesql);			
						$timerow = mysqli_fetch_array($timeresult);	   
						?>	   	   		
						<td style="width: 105px;"><?=$timerow['time']; ?></td>
						<td class="text-center" style="width: 105px;">
						<span class="score">
						<?php 	
						$scoresql="SELECT knowledgeCurrency FROM
						knowledgekombat_treasure_attempt WHERE 
						chapterID ='".$row1['chapterID']."' AND 
						userID ='".$currentUser."'";	
						$scoreresult = mysqli_query($conn,$scoresql);
						$scorerow = mysqli_fetch_array($scoreresult);
						if (count($scorerow['knowledgeCurrency'])>0) {
							echo $scorerow['knowledgeCurrency'];
						} else {						
							echo "N/A";
						}	
						?>
						</span>
						</td>
						<td class="text-center" style="width: 105px;">
						<span class="score">
						<?php 	
						$lCurrencysql="SELECT ksa.learningCurrency AS lc FROM
						knowledgekombat_skill_attempt AS ksa INNER JOIN
						knowledgekombat_skill AS ks ON ksa.skillID=ks.skillID WHERE
						ksa.userID='".$currentUser."' AND ksa.skillID IN (SELECT
						skillID FROM knowledgekombat_skill WHERE 
						chapterID='".$row1['chapterID']."')  AND ksa.skillAttemptID IN
						( SELECT MAX(`skillAttemptID`) FROM knowledgekombat_skill_attempt
						WHERE userID ='".$currentUser."' GROUP BY `skillID`)";
						$lCurrencyresult = mysqli_query($conn,$lCurrencysql);
						$sumoflc=0;
						while($lCurrencyresultrow=mysqli_fetch_array($lCurrencyresult)){
							$sumoflc+=$lCurrencyresultrow['lc'];
						}
						echo $sumoflc;		
						?>				
						</span>
						</td>
					</tr> 
					<tr>
					<?php
					$sql="SELECT ksa.skillAttemptID, ksa.userID, ksa.skillID,
					ksa.learningCurrency,ks.skill FROM knowledgekombat_skill_attempt as
					ksa	INNER JOIN knowledgekombat_skill as ks ON ksa.skillID=ks.skillID
					WHERE ksa.userID='".$currentUser."' AND ksa.skillID IN (SELECT
					skillID FROM knowledgekombat_skill WHERE 
					chapterID='".$row1['chapterID']."')  GROUP BY ksa.skillID ORDER BY
					ks.skillID ASC";
					$isdetailresult = mysqli_query($conn,$sql);
					if ($isdetailresult->num_rows>0) {	  
					?>
						<td colspan="6" style="padding: 0;">  	          	
						<div id="accordion_<?php echo $acc; ?>" class="collapse">
							<table class="table table-bordered" style="margin-bottom:
							0px !important; width: 100%;">
								<thead style="background: #ffda82;">
									<tr>
										<td style="width: 25%">TOPIC</td>
										<td style="width: 25%">GRADE</td>
										<td style="width: 25%">TIME PER QUESTION</td>
										<td  style="padding:0px !important"><table class="table-bordered" style="margin-bottom:0px !important; width: 100%;padding:0px !important"><tr>
											<td colspan="3">TOPIC WISE LEARNING CURRENCY</td>
											</tr>
											<tr>
												<td style="width: 35%">Current Score</td>
												<td style="width: 35%">Total Attempt</td>
												<td style="width: 30%">Average Score</td>
											</tr>
										</table></td>	
											
									</tr>
								</thead>
							<?php
							while($isdetailobj = mysqli_fetch_object($isdetailresult)){
								$lCurrencytopic="SELECT ksa.learningCurrency AS lc FROM	knowledgekombat_skill_attempt AS ksa  
								WHERE ksa.userID='".$currentUser."' AND
								ksa.skillID = '".$isdetailobj->skillID."' 
								ORDER BY ksa.`timestamp` DESC LIMIT 0,1";
								//echo $lCurrencytopic;
								/*	AND ksa.skillAttemptID IN (	SELECT MAX(`skillAttemptID`) FROM 
									knowledgekombat_skill_attempt WHERE	`userID` ='".$currentUser."' 
									GROUP	BY `skillID`)";*/
								$lCresult = mysqli_query($conn,$lCurrencytopic);
								$lCresultrow = mysqli_fetch_object($lCresult);
								$lCurrencysum="SELECT count(*) as totalcount,ROUND(AVG(`learningCurrency`)) AS avglc FROM 
								knowledgekombat_skill_attempt AS ksa WHERE ksa.userID='".$currentUser."' AND 
								ksa.skillID = '".$isdetailobj->skillID."' ";
								$lCtotres = mysqli_query($conn,$lCurrencysum);
								$lCtotcount = mysqli_fetch_object($lCtotres);
								
								
								
								$iswiselC=$lCresultrow->lc;
								echo "<tr><td>".$isdetailobj->skill."</td>";
								echo "<td>";
								if ($iswiselC>=90){
									$showlearningavg ="A1";	
								} else if (($iswiselC>=80) && ($iswiselC<90)){
									$showlearningavg ="A2";
								} else if (($iswiselC>=70) && ($iswiselC<80)){
									$showlearningavg ="B1";								
								} else if (($iswiselC>=60) && ($iswiselC<70)){
									$showlearningavg ="B2";
								} else if (($iswiselC>=50) && ($iswiselC<60)){
									$showlearningavg ="C1";
								} else if (($iswiselC>=40) && ($iswiselC<50)){
									$showlearningavg ="C2";
								} else if (($iswiselC>=30) && ($iswiselC<40)){
									$showlearningavg ="D";
								} else if (($iswiselC>=20) && ($iswiselC<30)){
									$showlearningavg ="E1";
								} else if (($iswiselC>=0) && ($iswiselC<20)){
									$showlearningavg ="E2";
								}
								echo $showlearningavg."</td>";
								$sql="SELECT `timeperquestion` as questiontime FROM 
								`knowledgekombat_skill_attempt` WHERE 
								skillID=".$isdetailobj->skillID ." and 
								userID='".$currentUser."' ORDER BY `timestamp` DESC
								LIMIT 1";	
								$qstimeresult = mysqli_query($conn,$sql);
								$qtobj = mysqli_fetch_object($qstimeresult);
								if (($qtobj->questiontime!='')&&($qtobj->questiontime!=0)) {
									echo "<td>".$qtobj->questiontime." s </td>";
								} else {
									echo "<td>N/A </td>";
								}
								
								
								echo '<td style="padding:0px !important"><table class="table" style="margin-bottom:0px !important; width: 100%;">
											<tr>
												<td style="width: 35%;border-right: 1px solid #ddd;">'.$lCresultrow->lc.'</td>
												<td style="width: 35%;border-right: 1px solid #ddd;">'.$lCtotcount->totalcount.'</td>
												<td style="width: 30%">'.$lCtotcount->avglc.'</td>
											</tr>
										</tbody></table></td>
										</tr>';
							}
							?>
							</table>
						</div>	              
						</td>
					<?php } else { ?>
						<td colspan="6">  	          	
						<div id="accordion_<?php echo $acc; ?>" class="collapse">
							No Data Found !
						</div>
						</td>
					<?php } ?>
					</tr>
					<?php 
						$acc++;
					} 
				}	
			} else { ?>
					<tr style="background:#acc61b;">
						<td colspan="6"><span style="color:#2a4c7b;"><b>No Record Found</b></span></td>
					</tr>	
			<?php }
			?>
			</tbody>
		</table>
	</div>
</div>
<?php
$levelsql = "SELECT `level` AS  LEVEL  FROM snailrush_level  WHERE  userID ='".$currentUser."' ORDER BY `timestamp` DESC LIMIT 0,1";		
$leveleresult = mysqli_query($conn,$levelsql);
$levelrow = mysqli_fetch_array($leveleresult);	
$flag = 1;
if(($levelrow['LEVEL'] != "") || ($levelrow['LEVEL'] != 0 )){
    $flag = 0;
}

$totaltimes = "SELECT TIME_FORMAT(SEC_TO_TIME(SUM(`time`)),'%HH : %iM : %SS') 
AS `times` FROM game_time_spent  WHERE  userID='".$currentUser."'";
$totalresults = mysqli_query($conn,$totaltimes);			
$timetotalrows = mysqli_fetch_array($totalresults);	 

if(($timetotalrows['times'] != "") || ($timetotalrows['times'] != 0 )){
   // $flag = 0;
}

$overaccuracy = "SELECT userID, AVG(CASE WHEN selected_choice=correct_choice THEN 1
ELSE 0 END) * 100 AS accuracy FROM snailrush_question NATURAL JOIN
user_attempted_snailrush_question WHERE userid ='".$currentUser."'";	
$accurcyresult = mysqli_query($conn,$overaccuracy);				
$rowaccuracy = mysqli_fetch_assoc($accurcyresult);			
			
if(($rowaccuracy['accuracy'] != "") || ($rowaccuracy['accuracy'] != 0 )){
    $flag = 0;
}

$i=1;			
$allrank  = "SELECT ROUND(SUM(coins))  AS  rank,userID AS userid FROM `snailrush_coins_gained` GROUP BY userID ORDER BY rank DESC";				
$allresult = mysqli_query($conn,$allrank);
while ($allrow = mysqli_fetch_assoc($allresult)){
	if ($allrow['userid'] == $currentUser){
		$overallranks = $i;	
	}	
$i++;	
}
			
if(($overallranks != "") || ($overallranks != 0 )){
    $flag = 0;
}
if($flag == 0) {
?>
<div class="container-fluid">
	<div class="section2">
		<div class="row">
			<ul>
				<li class="col-md-5th-1 col-sm-12 col-xs-12">
				<div class="first">
					<h2 class="learning_head">OLYMPIAD</h2>
					<p class="learning_para"><?php echo $classinfo->grade;?></h2>
				</div>
				</li>
				<li class="col-md-5th-1 col-sm-12 col-xs-12">
				<div class="circle">
					<div class="time">
					<h2><?php 
						if ($levelrow['LEVEL']=="") {	
							echo "0" ;
						} else { 
							echo $levelrow['LEVEL'];
						} ?>
					</h2>
					</div>
				</div>
				<p class="cont_para1">LEVEL</p>
				</li>
				<li class="col-md-5th-1 col-sm-12 col-xs-12">
				<div class="time_content">
					<div class="time">
						<h2 class="tdtime"><?php 
							if ($timetotalrows['times']=="") {
								echo "0" ;
							} else {
								echo $timetotalrows['times'];
							} ?>
						</h2>
					</div>
				</div>
				<p class="time_cont_para">TOTAL TIME SPENT</p>
				</li>
				<li class="col-md-5th-1 col-sm-12 col-xs-12">
				<div class="circle">
					<div class="time">
						<h2><?php echo round($rowaccuracy['accuracy']); ?></h2>
				 		<p>percent</p>  
					</div>
				</div>
				<p class="cont_para1">OVERALL ACCURACY</p>
				</li>
				<li class="col-md-5th-1 col-sm-12 col-xs-12">
				<h4 class="learning_head2"> 
					<?php 
						if($overallranks=="") {	
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
</div>
<?php } ?>
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

function numberToRomanRepresentation($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}
function Romannumeraltonumber($input_roman){
	$di=array(
		'I'=>1,
		'V'=>5,
		'X'=>10,
		'L'=>50,
		'C'=>100,
		'D'=>500,
		'M'=>1000
	);
	$result=0;
	if($input_roman=='') return $result;
		//LTR
	for($i=0;$i<strlen($input_roman);$i++){ 
		$result=(($i+1)<strlen($input_roman) and 
		$di[$input_roman[$i]]<$di[$input_roman[$i+1]])?($result-$di[$input_roman[$i]]) 
		:($result+$di[$input_roman[$i]]);
	}
	return $result;
}

//Example Usage
?>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}


$("#gradeenabled").change(function() {
 		document.getElementById("userdetails").submit(); 
});


</script>