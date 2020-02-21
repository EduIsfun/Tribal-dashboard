<?php

		session_start();
		error_reporting(0);
		//error_reporting(E_All);
		//ini_set('error_reporting', E_ALL);
		include('db_config.php');
		include('model/Teacher.class.php');
		global $conn;
		$teacher = new Teacher();
		
		
		if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getuserDetails")){
			$studentids = isset($_REQUEST['studentids'])?$_REQUEST['studentids']:'';
			$username = explode(",",$studentids);
			$countss=count($username);

			$htmls='';
			
			include("pdf/mpdf.php");
		
$htmls.='<!DOCTYPE html>
<html lang="en">
<head>
  <title> Dashboard PDF</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   
</head>
<body>
	<div style="width:100%;max-width:992pt;display:block;margin:0 auto;font-family: "Lato", sans-serif;">
		<table style="width:100%;" cellpadding="0" cellspacing="5">
			<tbody>
				<tr> 
					<td style="text-align: left;">
					
					</td> 
				</tr>
			</tbody>
		</table>';
		
		for($i=0;$i<$countss;$i++){
				$id = $username[$i];
				//$id ='g05167345904006340068';
				$result = $teacher->getStudentresult($id);
				$studentobj = mysqli_fetch_object($result);
				$userID = $currentUser = $studentobj->userID;
				
				// echo '<pre>';
				// print_r($userID);
				// echo '</pre>';
				$classinfo=$teacher->getClass($userID);
		
		$htmls.='<div style="background: linear-gradient(#fddc8a, #fdc339);padding: 10.5pt;border-top-left-radius: 7.5pt;border-top-right-radius: 7.5pt;overflow: hidden;">
			<div style="width:182pt;float: left;padding-right: 11.25pt;padding-left: 11.25pt;">
				<table width="100%" style="background: #fef4da;padding: 7.5pt;border-radius: 7.5pt;">
					<tbody>
						<tr>
							<td style="width:50pt;padding-bottom: 11.25pt;">Name</td>
							<td style="width: 30pt;text-align: center;padding-bottom: 11.25pt;">:</td>
							<td style="padding-bottom: 11.25pt;"> '.$studentobj->name.' </td>
						</tr>
						<tr>
							<td style="width:50pt;padding-bottom: 11.25pt;">Class</td>
							<td style="width: 30pt;text-align: center;padding-bottom: 11.25pt;">:</td>
							<td style="padding-bottom: 11.25pt;">'.$classinfo->grade.'</td>
						</tr>
						<tr>
							<td style="width:50pt;padding-bottom: 11.25pt;">City</td>
							<td style="width: 30pt;text-align: center;padding-bottom: 11.25pt;">:</td>
							<td style="padding-bottom: 11.25pt;">'.$studentobj->city.'</td>
						</tr>
						
						<tr>
							<td style="width:50pt;padding-bottom: 11.25pt;">School</td>
							<td style="width: 30pt;text-align: center;padding-bottom: 11.25pt;">:</td>
							<td style="padding-bottom: 11.25pt;">'.$classinfo->school.'</td>
						</tr>
						
					</tbody>
				</table>
			</div>';
			
					$questiontime = "SELECT SUM(timePerQuestion)/COUNT(timePerQuestion) AS `timePerQuestion` FROM knowledgekombat_chapter_time  WHERE  timePerQuestion > 0 AND userID ='".$currentUser."'";		
					$questionresult = mysqli_query($conn,$questiontime);
					$questionrow = mysqli_fetch_array($questionresult);
		
			$htmls.='<div style="width:182pt;float: left;padding-right: 11.25pt;padding-left: 11.25pt;">
						<table width="100%" style=" border-radius: 7.5pt;">
					<tbody>
						<tr>
							<td style="text-align:center;padding-right: 11.25pt;padding-left: 11.25pt;">
								<table style="width: 100%;border: 15px solid #f2b82c;padding: 10px;border-radius: 10px;background: #FFFFFF;margin: 12px 0;">
									<tbody>
										<tr>
											<th style="text-align: center;font-size:22.5pt;">
											'.number_format($questionrow['timePerQuestion'] , 2, '.', '').'</th>
										</tr>
									</tbody>
								</table>
								<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">Time Per Question</p>
							</td>';
							
							   #++++++++++++++++++++++  For Total Question Ans++++++++++++++#
								$tqasql = "select SUM(total_questions_answered) AS Totals from knowledgekombat_chapter_time  where userID='".$currentUser."'";		
								$tqaresult = mysqli_query($conn,$tqasql);
								$tqarow = mysqli_fetch_array($tqaresult);
								#++++++++++++++++++ End ++++++++++++++++++++++#
								
								#++++++++++++++++++++++  For knowledgeCurrency ++++++++++++++#
								$overallquery  = "SELECT ROUND(SUM(knowledgeCurrency)) as knowledgeCurrency FROM `knowledgekombat_treasure_attempt` WHERE `userID` ='".$currentUser."'";					
								$overallresult = mysqli_query($conn,$overallquery);                 	
								$overallrow = mysqli_fetch_assoc($overallresult);	
								#++++++++++++++++++ End ++++++++++++++++++++++#
								
								#++++++++++++++++++++++  For Overall Process ++++++++++++++#
								$overallquery  = "SELECT ROUND(SUM(progress)/COUNT(progress)) as overall_progress FROM `knowledgekombat_chapter_time` WHERE `userID` ='".$currentUser."'";					
								$overallresult = mysqli_query($conn,$overallquery);                 	
								$overallprocess = mysqli_fetch_assoc($overallresult);
								#++++++++++++++++++ End ++++++++++++++++++++++#
								
								#++++++++++++++++++++++  For Total Time Spent ++++++++++++++#
								$totaltime = "SELECT TIME_FORMAT(SEC_TO_TIME(ROUND(SUM(`time`))),'%HH : %iM : %SS') AS `time` FROM knowledgekombat_chapter_time  WHERE  userID='".$currentUser."'";		
								$totalresult = mysqli_query($conn,$totaltime);			
								$timetotalrow = mysqli_fetch_array($totalresult);
								
									#++++++++++++++++++ End ++++++++++++++++++++++#
								
							$htmls.='<td style="text-align:center;padding-right: 11.25pt;padding-left: 11.25pt;">
								<table style="width: 100%;border: 15px solid #f2b82c;padding: 10px;border-radius: 10px;background: #FFFFFF;margin: 12px 0;">
									<tbody>
										<tr>
											<th style="text-align: center;font-size:20.5pt;">'.$tqarow['Totals'].'</th>
										</tr>
									</tbody>
								</table>
								<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">total Question Answer</p>
							</td>
							
							<td style="width:187.5pt;padding-right: 11.25pt;padding-left: 11.25pt;text-align:center;">
							
							<table style="width: 140px;height: 140px;border-color: #acc61b #f3b92f #f3b92f #acc61b;border-radius: 50%;border-width: 14.5pt;border-style: solid;margin: auto;background: #fff;">
								<tbody><tr>
									<th style="text-align: center;font-size:20.5pt;font-weight: 600;padding-top: 7.5pt;">';
									
								$htmls.= isset($overallrow['knowledgeCurrency'])?$overallrow['knowledgeCurrency']:0;
									
								$htmls.='</th>
									</tr>
								</tbody>
						 
							</table>
							<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">Knowledge Currency</p>
						
							 
						</td> 
							 
						</tr>
						 
					 
					</tbody>
				</table>
			</div>
		</div>
	
		<div style="background: #ffcc52;padding: 14.5pt 0pt;margin: 3.75pt 0;">
			<table style="width: 100%;"> 
				<tbody>
					<tr>
						<td style="width:200ptpt;padding-right: 11.25pt;padding-left: 11.25pt;">
							<div style="padding: 7.5pt 0pt;margin: 3.75pt 0;">
								<p style="color: #9e7000;font-size: 18.75pt;margin: 0;">LEARNING</p>
								<h2 style="color: #9e7000;font-weight: 700;margin: 1.5pt 0 0;">CLASS 
								'.$classinfo->grade.' </h2>
								<p style="color: #9e7000;font-size: 18.75pt;margin: 0;">MATHS + SCIENCE</p>
							</div>
						</td>
						
						<td style="width:187.5pt;padding-right: 11.25pt;padding-left: 11.25pt;text-align:center;">
							
							<table style="width: 140px;height: 140px;border-color: #acc61b #f3b92f #f3b92f #acc61b;border-radius: 50%;border-width: 14.5pt;border-style: solid;margin: auto;background: #fff;">
									<tbody><tr>
								<th style="text-align: center;font-size:20.5pt;font-weight: 600;padding-top: 7.5pt;">';
									$htmls.= isset($overallrow['overall_progress'])?$overallrow['overall_progress']:0;
								$htmls.='</th>
									</tr>
									<tr>
									<td style="text-align: center;">
									percent</td>
									</tr>
								</tbody>
							</table>
							<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">OVERALL PROGRESS</p>
						
							 
						</td>
						
							<td style="width:187.5pt;">
								<table style="width: 100%;border: 15px solid #f2b82c;padding: 10px;border-radius: 10px;background: #FFFFFF;margin: 12px 0;">
									<tbody>
										<tr>
											<th style="text-align: center;font-size:20.5pt;">'.$timetotalrow['time'].'</th>
										</tr>
									</tbody>
								</table>
								<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">Total time Spent</p>
							</td>';
							
						#++++++++++++++++++++++  For Overall Accuracy++++++++++++++#
						$overallaccuracy=0;  
						$query  = "SELECT `userID`,`skillID`,`accuracy`,`timestamp`,`created_timestamp`,`updated_timestamp`
						FROM `knowledgekombat_skill_attempt` WHERE  `userID` ='".$currentUser."'  and `accuracy`>0 AND `timestamp`  IN (
						SELECT MAX(TIMESTAMP)
						FROM knowledgekombat_skill_attempt
						WHERE `userID` ='".$currentUser."' GROUP BY skillID
						)";

						$result = mysqli_query($conn,$query);    
						$count = mysqli_num_rows($result);
						while($row2 = mysqli_fetch_assoc($result))
						{
							$overallaccuracy =$overallaccuracy+$row2['accuracy'];
						}
						if ($count>0){
								$alculatedoverall = round($overallaccuracy /$count);
						}else {
								$alculatedoverall=0;
						}	
						#++++++++++++++++++++++ End ++++++++++++++#
						#++++++++++++++++++++++  For Overall Rank ++++++++++++++#
						/* $is=1;			
						$overallrankquery  = "SELECT ROUND(SUM(ksa.learningCurrency)/COUNT(ksa.learningCurrency) + SUM(kta.knowledgeCurrency)/COUNT(kta.knowledgeCurrency) ) AS  rank,kta.userID as userid FROM `knowledgekombat_skill_attempt` ksa INNER JOIN  knowledgekombat_treasure_attempt kta ON  ksa.userID=kta.userID   GROUP BY kta.userID ORDER BY rank DESC";				
						$overallresult = mysqli_query($conn,$overallrankquery);
						while ($overallrow = mysqli_fetch_assoc($overallresult)){
						if ($overallrow['userid'] == $currentUser ){
						$overallrank = $is;	
						}	

						$is++;	
						} */
						#++++++++++++++++++++++ End ++++++++++++++#
						#++++++++++++++++++++++  For Learning Currancy ++++++++++++++#
						/* $query  = "SELECT  ROUND(SUM(learningCurrency)) as  learningCurrency FROM `knowledgekombat_chapter_time` WHERE `userID` ='".$currentUser."'";					
						$result = mysqli_query($conn,$query);					
						$row3 = mysqli_fetch_assoc($result); */
						
						$htmls.='<td style="width:187.5pt;padding-right: 11.25pt;padding-left: 11.25pt;text-align:center;">
							
							<table style="width: 140px;height: 140px;border-color: #acc61b #f3b92f #f3b92f #acc61b;border-radius: 50%;border-width: 14.5pt;border-style: solid;margin: auto;background: #fff;">
					 
							<tbody><tr>
									<th style="text-align: center;font-size:20.5pt;font-weight: 600;padding-top: 7.5pt;">';
										$htmls .=isset($alculatedoverall)?$alculatedoverall:0;
									$htmls.='</th>
									</tr>
									<tr>
									<td style="text-align: center;">
									percent</td>
									</tr>
							</tbody>
						 
							</table>
							<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">OVERALL ACCURACY</p> 
						</td>';
						
						$htmls.='<td style="width:100pt;">
							<div style="padding: 14.5pt 0;"> 
								<h2 style="font-size:20pt;font-weight: 700;color: #b07c00;text-align: center;padding: 4.5pt;margin: 0;">';
									$htmls.=isset($overallrank)?$overallrank:0;
							$htmls.='</h2> 
							</div>
							<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">Overall rank</p>
						</td>
						
						<td style="width:187.5pt;padding-right: 11.25pt;padding-left: 11.25pt;text-align:center;">
							
							<table style="width: 140px;height: 140px;border-color: #acc61b #f3b92f #f3b92f #acc61b;border-radius: 50%;border-width: 14.5pt;border-style: solid;margin: auto;background: #fff;">
							<tbody><tr>
							<th style="text-align: center;font-size:20.5pt;font-weight: 600;padding-top: 7.5pt;">';
								$htmls.= isset($row3['learningCurrency'])?$row3['learningCurrency']:0;
							$htmls.='</th>
									</tr>
									<tr>
									<td style="text-align: center;">
									percent</td>
									</tr>
							</tbody>
						 
							</table>
							<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">Knowledge Currency</p>
						
							 
						</td>
						
						
					</tr>
				</tbody>
			</table>
		</div> 
		
	<table style="width: 100%;max-width: 100%;margin-bottom: 14.5pt;border: 1px solid #ddd;" cellspacing="0" cellpadding="8">
		<thead>
			<tr>
			<th style="background: #ffda82;text-align: center;color: #9e7000;padding: 6pt;">CHAPTERS</th>
			<th style="background: #ffda82;text-align: center;color: #9e7000;">PROGRESS</th>
			<th style="background: #ffda82;text-align: center;color: #9e7000;">TIME</th>
			<th style="background: #ffda82;text-align: center;color: #9e7000;">SCORE</th>
			<th style="background: #ffda82;text-align: center;color: #9e7000;">RANK</th>
			</tr>
		</thead>
		<tbody>';
				#++++++++++++++++++++++  For View Subject ++++++++++++++#
				$sqlsubject = "SELECT * FROM `knowledgekombat_chapter_time` kct INNER JOIN `chapter` c ON kct.`chapterID` = c.`chapterID` INNER JOIN `subject` s ON c.`subjectID` = s.`subjectID`  WHERE `userID` ='".$currentUser."' GROUP BY s.`subjectID`  ORDER BY s.`sequence` ASC";
				$resultsubject = mysqli_query($conn,$sqlsubject);
				while($rowsubject = mysqli_fetch_object($resultsubject)){
				$subject=$rowsubject->subject;
					#++++++++++++++++++++++ End ++++++++++++++#
					
				#++++++++++++++++++++++  For View Chapter ++++++++++++++#	
				$htmls.='<tr style="background:#acc61b;"><td colspan="5"><span style="color:#ffff;">'.$subject.'</span></td>';
				$sql = "SELECT * FROM `knowledgekombat_chapter_time` kct INNER JOIN `chapter` c ON kct.`chapterID` = c.`chapterID` INNER JOIN `subject` s ON c.`subjectID` = s.`subjectID`  WHERE `userID` ='".$currentUser."' AND s.subject='".$subject."'   ORDER BY s.`sequence` ASC";
				$result = mysqli_query($conn,$sql);	
				while($row1 = mysqli_fetch_array($result)){
					
						#++++++++++++++++++++++ End ++++++++++++++#
				
			$htmls.='<tr>
				<td style="width: 500pt;text-align: left;vertical-align: middle;padding: 0 14.5pt;color: #767676;font-size: 12.75pt;border-bottom: 1px solid #ddd;border-right: 1px solid #ddd;">'.$row1['chapter'].'</td>';
				#++++++++++++++++++++++  For View progress ++++++++++++++#	
				$progresssql = "select ROUND(SUM(progress)/COUNT(progress)) as  progress  from knowledgekombat_chapter_time  where chapterID ='".$row1['chapterID']."' and userID='".$currentUser."'";		
				$progressresult = mysqli_query($conn,$progresssql);
				$progresserow = mysqli_fetch_array($progressresult);
				$progresserow['progress']; 
				
				
				#++++++++++++++++++++++  For View Time ++++++++++++++#	
				$timesql = "select TIME_FORMAT(SEC_TO_TIME(`time`),'%HH : %iM : %SS') AS time from knowledgekombat_chapter_time  where chapterID ='".$row1['chapterID']."' and userID='".$currentUser."'";		
				$timeresult = mysqli_query($conn,$timesql);
				$timerow = mysqli_fetch_array($timeresult);	
				#++++++++++++++++++++++ End ++++++++++++++#
				
				#++++++++++++++++++++++  For View score ++++++++++++++#	
				$scoresql = "select ROUND(SUM(learningCurrency)/COUNT(learningCurrency)) as  learningCurrency  from knowledgekombat_chapter_time  where chapterID ='".$row1['chapterID']."' and userID='".$currentUser."'";		
				$scoreresult = mysqli_query($conn,$scoresql);
				$scorerow = mysqli_fetch_array($scoreresult);	
					#++++++++++++++++++++++ End ++++++++++++++#
					
					#++++++++++++++++++++++  For View Rank ++++++++++++++#	
				$ranksql = "SELECT * , FIND_IN_SET( learningCurrency,(SELECT GROUP_CONCAT( learningCurrency ORDER BY learningCurrency DESC ) 
				FROM knowledgekombat_chapter_time where chapterID ='".$row1['chapterID']."') ) AS rank FROM knowledgekombat_chapter_time where chapterID ='".$row1['chapterID']."' and userID='".$currentUser."'";
				$rankresult = mysqli_query($conn,$ranksql);
				$rankrow = mysqli_fetch_array($rankresult);	
				#++++++++++++++++++++++  End ++++++++++++++#	
				
				$htmls.='<td style="width: 350px;border-bottom: 1px solid #ddd;border-right: 1px solid #ddd;">
					<div style="padding-right: 11.25pt;padding-left: 11.25pt;">
						<div style="height: 18.75pt;margin-bottom: 0;border-radius: 4px;-webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.1);box-shadow: inset 0 1px 2px rgba(0,0,0,.1);border: 2px solid #ffcc52;background-color: #ffcc52;width: 70%;float: left;border-top-right-radius: 0;border-bottom-right-radius: 0;">
							
						</div>
						<div style="width: 26px;float: left;border: 2px solid #ffcc52;height: 18.75pt;border-left: 0;border-top-right-radius: 4px;border-bottom-right-radius: 4px;">
							</div>
					</div>
					<div style="float: left;padding-left: 21pt;padding-right: 11.25pt;margin-top: 7px;">
						<span>'.$progresserow['progress'].'%</span>
					</div>
				</td>
				<td style="text-align: center;vertical-align: middle;color: #767676;border-bottom: 1px solid #ddd;border-right: 1px solid #ddd;">'.$timerow['time'].'</td>
				
				 <td style="width: 120pt;border-bottom: 1px solid #ddd;border-right: 1px solid #ddd;text-align: center;">
				<span style="border: 1px solid #ceda8a;background: #acc61b;color: #ffff;padding: 3.75pt 30pt;font-size: 12.75pt;border-radius: 7.5pt;text-align: center;">'.$scorerow['learningCurrency'].'</span>
				</td>
				<td style="width: 103.75pt;text-align: center;vertical-align: middle;color: #767676;border-bottom: 1px solid #ddd;">'.$rankrow['rank'].'</td>';
			} 
			
			$htmls.='</tr>';
	   	}
			 
		$htmls.='</tbody>
	</table>';
	
	$levelsql = "SELECT `level` AS  LEVEL  FROM snailrush_level  WHERE  userID ='".$currentUser."' ORDER BY `timestamp` DESC LIMIT 0,1";		
	$leveleresult = mysqli_query($conn,$levelsql);
	$levelrow = mysqli_fetch_array($leveleresult);	

	/* $levelrow['LEVEL']."|".$timetotalrows['times']."|".$rowaccuracy['accuracy']."|".
	$overallranks;*/
	$flag = 1;
	if(($levelrow['LEVEL'] != "") || ($levelrow['LEVEL'] != 0 )){
		$flag = 0;
	}

	$totaltimes = "SELECT TIME_FORMAT(SEC_TO_TIME(SUM(`time`)),'%HH : %iM : %SS') AS `times` FROM game_time_spent  WHERE  userID='".$currentUser."'";		
	$totalresults = mysqli_query($conn,$totaltimes);			
	$timetotalrows = mysqli_fetch_array($totalresults);	 

	if(($timetotalrows['times'] != "") || ($timetotalrows['times'] != 0 )){
	   // $flag = 0;
	}

	$overaccuracy = "SELECT userID, AVG(CASE WHEN selected_choice=correct_choice THEN 1 ELSE 0 END) * 100 AS accuracy FROM snailrush_question NATURAL JOIN user_attempted_snailrush_question WHERE userid ='".$currentUser."'";	
							
	$accurcyresult = mysqli_query($conn,$overaccuracy);				
	$rowaccuracy = mysqli_fetch_assoc($accurcyresult);			
				
	if(($rowaccuracy['accuracy'] != "") || ($rowaccuracy['accuracy'] != 0 )){
		$flag = 0;
	}

	/* $is=1;			
	$allrank  = "SELECT ROUND(SUM(coins))  AS  rank,userID AS userid FROM `snailrush_coins_gained` GROUP BY userID ORDER BY rank DESC";	
	
	$allresult = mysqli_query($conn,$allrank);
	while ($allrow = mysqli_fetch_assoc($allresult)){
		if ($allrow['userid'] == $currentUser){
			$overallranks = $is;	
		}	
		$is++;	
	}
			
	if(($overallranks != "") || ($overallranks != 0 )){
		$flag = 0;
	} */


	if($flag == 0)
	{
    
	$htmls.='<div style="background: #ffcc52;padding: 14.5pt 0pt;margin: 3.75pt 0;">
			<table style="width: 100%;"> 
				<tbody>
					<tr>
						<td style="width:200ptpt;padding-right: 11.25pt;padding-left: 11.25pt;">
							<div style="padding: 7.5pt 0pt;margin: 3.75pt 0;">
								<p style="color: #9e7000;font-size:18.75pt;margin: 0;">OLYMPIAD</p>
								<h2 style="color: #9e7000;font-weight: 700;margin: 1.5pt 0 0;">CLASS '.$classinfo->grade.'</h2>
								<p style="color: #9e7000;font-size: 18.75pt;margin: 0;">MATHS + SCIENCE</p>
							</div>
						</td>
						
						<td style="width:187.5pt;padding-right: 11.25pt;padding-left: 11.25pt;text-align:center;">
							
							<table style="width: 140px;height: 140px;border-color: #acc61b #f3b92f #f3b92f #acc61b;border-radius: 50%;border-width: 14.5pt;border-style: solid;margin: auto;background: #fff;">
					 
								<tbody><tr>
									<th style="text-align: center;font-size:20.5pt;font-weight: 600;padding-top: 7.5pt;">
									'.$levelrow['LEVEL'].'
									</th>
									</tr>
									<tr>
									<td style="text-align: center;">
									percent</td>
									</tr>
								</tbody>
						 
							</table>
							<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">OVERALL PROGRESS</p>
						</td>
						
							<td style="width:187.5pt;">
								<table style="width: 100%;border: 15px solid #f2b82c;padding: 10px;border-radius: 10px;background: #FFFFFF;margin: 12px 0;">
									<tbody>
										<tr>
											<th style="text-align: center;font-size:18.5pt;">'.$timetotalrows['times'].'</th>
										</tr>
									</tbody>
								</table>
								<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">total Question Answer</p>
							</td>
						 
						<td style="width:187.5pt;padding-right: 11.25pt;padding-left: 11.25pt;text-align:center;">
							
							<table style="width: 140px;height: 140px;border-color: #acc61b #f3b92f #f3b92f #acc61b;border-radius: 100pt;border-width: 14.5pt;border-style: solid;margin: auto;background: #fff;">
					 
							<tbody><tr>
								<th style="text-align: center;font-size:18.5pt;font-weight: 600;padding-top: 7.5pt;">
								 '.round($rowaccuracy['accuracy']).'
								</th>
								</tr>
								<tr>
								<td style="text-align: center;">
								percent</td>
								</tr>
							</tbody>
						 
							</table>
							<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">OVERALL ACCURACY</p> 
						</td>
						<td style="width:100pt;">
							<div style="padding: 14.5pt 0;"> 
								<h2 style="font-size: 20pt;font-weight: 700;color: #b07c00;text-align: center;padding: 4.5pt;margin: 0;">'.$overallranks.'</h2> 
							</div>
							<p style="color: #9e7000;font-weight: 700;text-align: center;margin-bottom: 0;">Rank</p> 
						</td>
						
					</tr>
				</tbody>
			</table>
		</div> 
	</div>';
	}
		}
		echo $html;
	}
	
$htmls.='</body>
</html>';
				
			// $mpdf=new mPDF('c','A4','','',10,10,10,10,8); 
			// $mpdf->SetDisplayMode('fullpage');
			// $mpdf->list_indent_first_level = 0; 
			// $stylesheet = file_get_contents('mpdfstyletables.css');
			// $mpdf->WriteHTML($stylesheet,1);
			// $mpdf->WriteHTML($htmls);
			// $mpdf->Output('mpdf.pdf', 'D');
			
			//$mpdf = new mPDF (['autoPageBreak' => true]);
			$mpdf = new mPDF ('c','A4','','',10,10,10,10,8); 
			$mpdf->AddPage('L');
			$mpdf->SetDisplayMode('fullpage');
			$mpdf->WriteHTML($htmls);
			//$mpdf->WriteHTML('<pagebreak type="NEXT-ODD" pagenumstyle="1" />');
			$file= rand().$m_code.".pdf";
			$path ="fee-receipt/".$file;
			$mpdf->Output($file,'D');
			
			// $mpdf1=new mPDF('en-GB-x','A4','','',10,10,10,10,6,3);
			// $mpdf1->SetDisplayMode('fullpage');
			// $mpdf1->WriteHTML($htmls);
			// $files= rand().$m_code.".pdf";

?>