<?php 
session_start();
error_reporting(0);
 include('../db_config.php');
	include('../model/Teacher.class.php');
	include('../model/Dashboard.class.php');
	$teacher = new Teacher();
	$dashboard = new Dashboard();
	
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
			// echo Romannumeraltonumber('I');
			//echo ConverToRoman(23); 
	
		$res='';
		if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getCity")) {
			$cityid = isset($_POST['city_id'])?$_POST['city_id']:'';
			$sql = "SELECT * FROM tbl_cities WHERE state_id = $cityid";
			$result = mysqli_query($conn,$sql);

			$res .='<option value="">Choose City</option>';
			while($row = mysqli_fetch_object($result))
			{
				$res .= "<option value='".$row->id."'>".$row->name."</option>";  
			}
			echo $res;
			mysqli_close($conn); 
			die();
		}

		$outputs = "";
		if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getBoard")){
			$city = $_POST['city'];
			$board_id = $_POST['board_id'];
			$outputs .='<option value="">School</option>';
			
			$sql ="SELECT ";
			if($board_id=='4'){
				$sql.=" sm.school as school,sm.school_code as school_code";
			} else {
				$sql.=" usd.school as school  ";
			}
			$sql.=" FROM  `knowledgekombat_chapter_time` kct 
			INNER JOIN `user` u ON u.userID = kct .userID 
			INNER JOIN user_school_details usd ON kct.`userID`=usd.userID ";
			if($board_id=='4'){
				$sql.=" INNER Join school_master sm on sm.school_code= usd.school_id";
			}
			
			$sql.=" where 1=1";

			if ($board_id == 'All' ){
				$sql.=" And usd.city_id = '$city' GROUP BY school ";				
			}else if(!empty($city) && $board_id==''){
				$sql.=" And usd.city_id ='$city' OR usd.board_id='' GROUP BY school";			}else if($board_id=='4'){
				$sql.=" And usd.city_id ='$city' AND sm.`school_code`!='' GROUP BY school";
			}else{
				$sql.=" And usd.city_id ='$city' AND usd.board_id='$board_id' GROUP BY school ";
			}
			//echo $sql;
			//die();
			
			$result = mysqli_query($conn,$sql);
			while($obj = mysqli_fetch_object($result))
			{
				if($board_id=='4'){
					$outputs .='<option value="'.addslashes($obj->school_code).'">'.ucfirst($obj->school).'</option>';
				} else {
					$outputs .='<option value="'.addslashes($obj->school).'">'.ucfirst($obj->school).'</option>';
				}	
			}
			echo $outputs;
			mysqli_close($conn); 
			die();
		}

		$output = "";
		if (isset($_REQUEST['action']) && ($_REQUEST['action']=="get_boardwise_subjects")){
			$board_id = $_POST['board_id'];

			if ($board_id=="All") {
				$sql = "SELECT  * FROM  `subject` WHERE `subjectID` IN('1','2','3','8') ORDER BY subjectID ASC";
			}
			else
			{
				$sql = "SELECT s.subject,s.subjectID FROM chapter as c
				INNER JOIN subject as s ON c.subjectID=s.subjectID
				INNER JOIN board as b ON b.board=c.exam
				WHERE b.id = '".$board_id."' GROUP BY s.subject ORDER BY subjectID ASC"; 
			}
			//$clasID = Romannumeraltonumber($_POST['classid']);			
			$result = mysqli_query($conn,$sql);
			$output.="<option value=>Choose Subject</option>";
			while($obj = mysqli_fetch_object($result))
			{
				$output .= "<option value='".$obj->subjectID."'>".ucfirst($obj->subject)."</option>";
			}
			echo $output;
			mysqli_close($conn); 
			die();
		}

		$output = "";
		if (isset($_REQUEST['action']) && ($_REQUEST['action']=="get_classwise_subjects")){
			$board_id = $_POST['board_id'];
			$clasID = Romannumeraltonumber($_POST['classid']);
			if ($board_id=="All") {
				$sql = "SELECT  * FROM  `subject` WHERE `subjectID` IN('1','2','3','8') ORDER BY subjectID ASC";
			} else if ($board_id>0 && $clasID>0) {			 
				$sql = "SELECT s.subject,s.subjectID FROM chapter as c
				INNER JOIN subject as s ON c.subjectID=s.subjectID
				INNER JOIN board as b ON b.board=c.exam
				WHERE b.id = '".$board_id."' AND c.grade='".$clasID."' GROUP BY s.subject ORDER BY subjectID ASC"; 			
				
			} else if ($board_id>0) {
				if ($board_id=="All") {
					$sql = "SELECT  * FROM  `subject` WHERE `subjectID` IN('1','2','3','8') ORDER BY subjectID ASC";
				}
				else
				{
					$sql = "SELECT s.subject,s.subjectID FROM chapter as c
					INNER JOIN subject as s ON c.subjectID=s.subjectID
					INNER JOIN board as b ON b.board=c.exam
					WHERE b.id = '".$board_id."' GROUP BY s.subject ORDER BY subjectID ASC"; 
				}
			}	
			$result = mysqli_query($conn,$sql);
			$output.="<option value=>Choose Subject</option>";
			while($obj = mysqli_fetch_object($result))
			{
				$output .= "<option value='".$obj->subjectID."'>".ucfirst($obj->subject)."</option>";
			}
			echo $output;
			mysqli_close($conn); 
			die();	
		}
		
		$output = "";
		if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getSubject")){
			$board_id = $_POST['board_id'];
			$subject_id = $_POST['subject'];
			$clasID = Romannumeraltonumber($_POST['classid']);
			$clasIDs   = $clasID;

			$sql = "SELECT c.* FROM chapter as c
				INNER JOIN board as b ON b.board=c.exam
				WHERE c.subjectID=$subject_id";
			if ($clasIDs != 0){
				$sql.=" AND c.grade = '".$clasIDs."'";				
			}
			if ($board_id != 'All' ){
				$sql.=" AND b.id = '".$board_id."'";				
			}
			$sql.=" GROUP BY c.chapter"; 
			
			$result = mysqli_query($conn,$sql);
			$output.="<option value=>Choose Chapter</option>";
			while($obj = mysqli_fetch_object($result))
			{
				$output .= "<option value='".$obj->chapterID."'>".ucfirst($obj->chapter)."</option>";
			}
			echo $output;
			mysqli_close($conn); 
			die();
		}
		
		$subout = "";
		
		if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getsubSubject")){
			
			$subchapter = $_POST['subchapter'];
			$sql = "SELECT * FROM knowledgekombat_skill WHERE chapterID = '$subchapter'";
			$subresult = mysqli_query($conn,$sql);
			$subout.="<option value=>Choose Topic</option>";
			while($subobj = mysqli_fetch_object($subresult))
			{
				$subout .="<option value='".$subobj->skillID."'>".ucfirst($subobj->skill)."</option>";
			}
				echo $subout;
				mysqli_close($conn); 
				die();
			
		}
		

if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getList")){
	$state = isset($_POST['state_id'])?$_POST['state_id']:'';
	$schoolid =isset($_POST['schoolid'])?$_POST['schoolid']:'';
	$classid =isset($_POST['classid'])?$_POST['classid']:'';
	$subchapterid = isset($_POST['subtopicid'])?$_POST['subtopicid']:'';
	$city_id = isset($_POST['city_id'])?$_POST['city_id']:'';
	$board_id = isset($_POST['board_id'])?$_POST['board_id']:'';
	$chapter_id = isset($_POST['chapter_id'])?$_POST['chapter_id']:'';
	$subject_id = isset($_POST['subject_id'])?$_POST['subject_id']:'';
	
	$html='';
	$html .= '<!DOCTYPE html>
	<html>
	<head>
	<style>
		@media only screen and (max-width: 992px) {
			#example1_wrapper {
			overflow-x: scroll;
			}
		}
	</style>
	</head>
	<body>	    
	<div class="" style="padding:10px;">    
    <div class="">';
	$html.= '<table id="example1" class="table table-bordered table-striped" data-page-length="25">
				<thead>
					<tr>
					   	<th>Sr.No. &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Name &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Class<i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Class Rank &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Global Rank &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Time Spent &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th> 
						<th>Overall Grade &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
					</tr>';
						// <div class="box-tools pull-right">
						// 	<a href="download-report.php?state_id='.$_POST['state_id'].'&city_id='.$city_id.'&board_id='.$board_id.'&schoolid='.$schoolid.'&classid='.$classid.'&subject_id='.$subject_id.'" class="btn btn-success button-loading pull-right" >Export Record</a>
						// </div>
						
						// <div class="box-tools pull-right" style="margin-right: 10px;">
						// 	<a href="download-knowledge.php?state_id='.$_POST['state_id'].'&city_id='.$city_id.'&board_id='.$board_id.'&schoolid='.$schoolid.'&classid='.$classid.'&subject_id='.$subject_id.'" class="btn btn-success button-loading pull-right" >Export Know</a>
						// </div>
						
						
				
					/*$ids = $teacher->getuserResultIDs($schoolid,$classid,$subchapterid,$state,$city_id,$board_id, $chapter_id,$subject_id);*/
					$user = $teacher->getuserResult($schoolid,$classid,$subchapterid,$state,$city_id,$board_id,$chapter_id,$subject_id);
					if($user){
						$count = 1;
					}
					$counget=1;
					$user_ids = '';
					//<input type="button" onclick="getPdf();" id="btn" class="btn btn-success name="PDF" value="PDF" style="margin-left: 723px;">
					$html.='<span id="userresults"></span>
							</thead>';
						
					if(mysqli_num_rows($user)>0){
						$ids = '';
						$i=1;
						$overallresult = $teacher->getrankuserResult($classid,$schoolid,$city_id,$board_id);
						$classrankar =  array();	
						while($classrank = mysqli_fetch_object($overallresult)){							
							$classrankar[$i] =  $classrank->userid;
							$i++;							
						}

						
						//echo "getrankuserResult-".count($classrankar)."/".$i;
						$globalresult = $teacher->getglobalrankResult($classid,$schoolid,$city_id,$board_id);	
						$globalrankar =  array();
						$j=1;
						while($globalrank = mysqli_fetch_object($globalresult)){
							$globalrankar[$j] =  $globalrank->userid;
							$j++;			
						}	
						
						while( $userlist = mysqli_fetch_object($user) ){
							if ( $ids == '' ) { $ids .= $userlist->userID; }
							else { $ids .= ','.$userlist->userID; }
							$learnincurrancy =0;
							$coloravg='';
							$img_grade = '';

							$overallgrade = $teacher->getoverallGrade($userlist->userID);	
							$allgrade = mysqli_fetch_object($overallgrade);	
							$learnincurrancy =$allgrade->currency;
							if (mysqli_num_rows($overallgrade)>0) {
								if (($allgrade->currency=="") || ($allgrade->currency=="0")) {
									$learnincurrancy =0;
									$showlearningavg ="E1";
									$coloravg ="redr";
                                    $img_grade = 'images/red.png';									

                                } else {
									if ($learnincurrancy>=90){
										$showlearningavg ="A1";
										$coloravg ="greenr";
										 $img_grade = 'images/green.png';	
									} else if (($learnincurrancy>=80) && ($learnincurrancy<90)){
										$showlearningavg ="A2";
										$coloravg ="greenr";
										 $img_grade = 'images/green.png';	
									} else if (($learnincurrancy>=70) && ($learnincurrancy<80)){
										$showlearningavg ="B1";
										$coloravg ="yellowr";
										$img_grade = 'images/yellow.png';
										
									} else if (($learnincurrancy>=60) && ($learnincurrancy<70)){
										$showlearningavg ="B2";
										$coloravg ="yellowr";
										$img_grade = 'images/yellow.png';
									} else if (($learnincurrancy>=50) && ($learnincurrancy<60)){
										$showlearningavg ="C1";
										$coloravg ="blue";
										$img_grade = 'images/blue.png';
									} else if (($learnincurrancy>=40) && ($learnincurrancy<50)){
										$showlearningavg ="C2";
										$coloravg ="blue";
										$img_grade = 'images/blue.png';
									} else if (($learnincurrancy>=30) && ($learnincurrancy<40)){
										$showlearningavg ="D";
										$coloravg ="blue";
										$img_grade = 'images/blue.png';
									} else if (($learnincurrancy>=20) && ($learnincurrancy<30)){
										$showlearningavg ="E1";
										$coloravg ="redr";
										$img_grade = 'images/red.png';
									} else if (($learnincurrancy>=0) && ($learnincurrancy<20)){
										$showlearningavg ="E1";
										$coloravg ="redr";
										$img_grade = 'images/red.png';
									}
								}
							}

							$classrankVAL  = array_search($userlist->userID, $classrankar);							
							$globalranks  = array_search($userlist->userID, $globalrankar);			
							//if 	(($rank !=0) && ($globalranks !=0)) {
							$html.='<tr>
						        	<td><span class="span_inline">'.$counget.'</span></td>
									<td>
										<span class="span_inline">
										<img src="'.$img_grade.'" alt="icon" /> &nbsp &nbsp <a href="edufun.php?id='.$userlist->userID.'" target="_blank">'.$userlist->student_name.' </a>
										</span>
									</td>';
									$html.='<td class="span_inline"><span>'.$userlist->grade.'</span></td>';
									if ($classrankVAL>0) {
										$html.='<td class="orange"><span>'.$classrankVAL.'</span></td>';
									} else{
										$html.='<td class="orange"><span>N/A</span></td>';
									}
									if ($globalranks>0) {
										$html.='<td class="orange"><span>'.$globalranks.'</span></td>';
									} else{
										$html.='<td class="orange"><span>N/A</span></td>';
									}	
											
									$timeresult = $teacher->getrankTimestamp($userlist->userID);			    
									$Totaltime = mysqli_fetch_object($timeresult);
											
									$html.='<td class="orange"><span><ul class="time-inline">'.$Totaltime->time.'</ul></span></td>';	
									$html.= '<td class='.$coloravg.'><span>'.$showlearningavg.'</span></td>
							</tr>';
							$counget++;
						//	}
						}
						$html.= '<input type="hidden" id="studentids" value="'.$ids.'">';
					}else{
						$html.='<tr>';
						$html.='<td>No user </td>';
						$html.='<td colspan="8">No Record found</td>';
						$html.='</tr>';
					}
					
					    $html.='</table>';						
						$html.="</div>
								</div>	
								</body>
								<script src='js/jquery.dataTables.min.js'></script>
								<script src='js/dataTables.bootstrap.min.js'></script>
								<script>
								  $(function () {
								    $('#example1').DataTable()
								    $('#example2').DataTable({
								      'paging'      : true,
								      'lengthChange': false,
								      'searching'   : false,
								      'ordering'    : true,
								      'info'        : true,
								      'autoWidth'   : false
									  
								    })
								  })		
								</script>
								</html>";
						echo $html;
						die();

	
}


if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getListsubtopic")){
	$state = isset($_POST['state_id'])?$_POST['state_id']:'';
	$schoolid =isset($_POST['schoolid'])?$_POST['schoolid']:'';
	$classid =isset($_POST['classid'])?$_POST['classid']:'';
	$subchapterid = isset($_POST['subtopicid'])?$_POST['subtopicid']:'';
	$city_id = isset($_POST['city_id'])?$_POST['city_id']:'';
	$board_id = isset($_POST['board_id'])?$_POST['board_id']:'';
	$chapter_id = isset($_POST['chapter_id'])?$_POST['chapter_id']:'';
	$subject_id = isset($_POST['subject_id'])?$_POST['subject_id']:'';
	// $resultskill = $dashboard->getskillResult($chapter_id);
	// $chaptercount =mysqli_num_rows($resultskill);
	$html='';
	$html .= '<!DOCTYPE html>
<html>
<head>
	
	<style>
@media only screen and (max-width: 992px) {
	#example1_wrapper {
    overflow-x: scroll;
	}
}
	</style>


</head>
<body>
    
<div class="" style="padding:10px;">    
    <div class="">';
	$html .= '<table id="example1" class="table table-bordered table-striped" data-page-length="25">
			
				<thead>
					<tr>
						<th> SR.NO. &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th> Name &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Class Rank &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Global Rank &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Time Spent &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Subtopic Grade &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>						
						<th>Overall Grade &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
					</tr>';
					
						
			$ids ='';
					//$ids = $teacher->getuserResultIDs($schoolid,$classid,$subchapterid,$state,$city_id,$board_id,$chapter_id,$subject_id);
					$user = $teacher->getuserResult($schoolid,$classid,$subchapterid,$state,$city_id,$board_id,$chapter_id,$subject_id);
					if($user){
						$count = 1;
					}
						// $html.='<div class="box-tools pull-right">
						// 	<a href="download-report.php?state_id='.$_POST['state_id'].'&city_id='.$city_id.'&board_id='.$board_id.'&schoolid='.$schoolid.'&classid='.$classid.'&subject_id='.$subject_id.'&chapter_id='.$chapter_id.'&subchapterid='.$subchapterid.'" class="btn btn-success button-loading pull-right" >Export Record</a>
						// </div>';
						
						// $html.='
						// 	<input type="hidden" id="studentids" value="'.$ids.'">
						// 	<input type="button" onclick="getPdf();"  class="btn btn-success name="PDF" value="PDF" style="margin-left: 844px;">
							$html.='<span id="userresults"></span>
						</thead>';
						
					if(mysqli_num_rows($user)>0){
						$htmls='';
						$resultskill = $dashboard->getskillResult($chapter_id);
						$learningaverage =0;
						$overallavgcount =0;
						$overalllavgearning =0;
						$coloravg='';
						
						$overallresult = $teacher->getrankuserResult($classid,$schoolid,$city_id,$board_id);
						$classrankar =  array();
						$i=1;
						while($classrank = mysqli_fetch_object($overallresult)){
							$classrankar[$i] =  $classrank->userid;
							$i++;
						}
						
						$globalresult = $teacher->getglobalrankResult($schoolid,$city_id,$board_id);	
						$globalrankar =  array();
						$j=1;
						while($globalrank = mysqli_fetch_object($globalresult)){
							$globalrankar[$j] =  $globalrank->userid;
							$j++;			
						}	
						
						while( $userlist = mysqli_fetch_object($user) ){
							
							//subtopic grade start here
						$rowskill = mysqli_fetch_object($resultskill);
							$skillid=$rowskill->skillID;
							
							$resultlearningaverage = $dashboard->getlearningResultAverage($state,$city_id,$board_id,$schoolid,$classid,$skillid,$subject_id,$chapter_id);
							
							$rowlearningaveareg=mysqli_fetch_object($resultlearningaverage);
							$learningaverage =$rowlearningaveareg->learningavg;
							if (mysqli_num_rows($resultlearningaverage)>0) {
								if (($rowlearningaveareg->learningavg=="") || ($rowlearningaveareg->learningavg=="0") ) {
									$learningaverage =0;
									$showlearningavg ="E1";
									$coloravg ="bluer";
								} else {
									if ($learningaverage>=90){
										$showlearningavg ="A1";
										$coloravg ="greenr1";
									} else if (($learningaverage>=80) && ($learningaverage<90)){
										$showlearningavg ="A2";
										$coloravg ="greenr1";
									} else if (($learningaverage>=70) && ($learningaverage<80)){
										$showlearningavg ="B1";
										$coloravg ="oranger1";
									} else if (($learningaverage>=60) && ($learningaverage<70)){
										$showlearningavg ="B2";
										$coloravg ="oranger1";
									} else if (($learningaverage>=50) && ($learningaverage<60)){
										$showlearningavg ="C1";
										$coloravg ="greyr1";
									} else if (($learningaverage>=40) && ($learningaverage<50)){
										$showlearningavg ="C2";
										$coloravg ="greyr";
									} else if (($learningaverage>=33) && ($learningaverage<40)){
										$showlearningavg ="D";
										$coloravg ="dark_greyr";
									} else if (($learningaverage>=20) && ($learningaverage<30)){
										$showlearningavg ="E1";
										$coloravg ="bluer";
									} else if (($learningaverage>=0) && ($learningaverage<20)){
										$showlearningavg ="E2";
										$coloravg ="bluer";
									}
									$overallavgcount++;
								}
								$overalllavgearning =$overalllavgearning+$learningaverage;
							} else {
							
								$showlearningavg ="E1";
								$coloravg ="bluer";
							}	
							$htmls='<td><span class='.$coloravg.'>'.$showlearningavg.'</span></td>';
						
							
							//subtopic grade end here
							
							$learnincurrancy =0;
							$coloravg='';
							$img_grade = '';

							$overallgrade = $teacher->getoverallGrade($userlist->userID);	
							$allgrade = mysqli_fetch_object($overallgrade);	
							$learnincurrancy =$allgrade->currency;
							if (mysqli_num_rows($overallgrade)>0) {
								if (($allgrade->currency=="") || ($allgrade->currency=="0")) {
									$learnincurrancy =0;
									$showlearningavg ="E1";
									$coloravg ="redr";
                                    $img_grade = 'images/red.png';									

                                } else {
									if ($learnincurrancy>=90){
										$showlearningavg ="A1";
										$coloravg ="greenr";
										 $img_grade = 'images/green.png';	
									} else if (($learnincurrancy>=80) && ($learnincurrancy<90)){
										$showlearningavg ="A2";
										$coloravg ="greenr";
										 $img_grade = 'images/green.png';	
									} else if (($learnincurrancy>=70) && ($learnincurrancy<80)){
										$showlearningavg ="B1";
										$coloravg ="yellowr";
										$img_grade = 'images/yellow.png';
										
									} else if (($learnincurrancy>=60) && ($learnincurrancy<70)){
										$showlearningavg ="B2";
										$coloravg ="yellowr";
										$img_grade = 'images/yellow.png';
									} else if (($learnincurrancy>=50) && ($learnincurrancy<60)){
										$showlearningavg ="C1";
										$coloravg ="blue";
										$img_grade = 'images/blue.png';
									} else if (($learnincurrancy>=40) && ($learnincurrancy<50)){
										$showlearningavg ="C2";
										$coloravg ="blue";
										$img_grade = 'images/blue.png';
									} else if (($learnincurrancy>=30) && ($learnincurrancy<40)){
										$showlearningavg ="D";
										$coloravg ="blue";
										$img_grade = 'images/blue.png';
									} else if (($learnincurrancy>=20) && ($learnincurrancy<30)){
										$showlearningavg ="E1";
										$coloravg ="redr";
										$img_grade = 'images/red.png';
									} else if (($learnincurrancy>=0) && ($learnincurrancy<20)){
										$showlearningavg ="E1";
										$coloravg ="redr";
										$img_grade = 'images/red.png';
									}
								}
							}

							$rank  = array_search($userlist->userID, $classrankar); 
							$globalranks  = array_search($userlist->userID, $globalrankar);			
										
							//if 	(($rank !=0) && ($globalranks !=0)) {				
							$html.= '<tr>
							        <td><span class="span_inline">&nbsp &nbsp '.$count++.' </span></td>
									<td><span class="span_inline"><img src="'.$img_grade.'" alt="icon" /> &nbsp &nbsp <a href="edufun.php?id='.$userlist->userID.'" target="_blank">'.$userlist->student_name.' </a></span></td>';
									
							$html.= '<td class="orange"><span>'.$rank.'</span></td>';
										
							$html.='<td class="orange"><span>'.$globalranks.'</span></td>';
									
									$timeresult = $teacher->getrankTimestamp($userlist->userID);			    
									$Totaltime = mysqli_fetch_object($timeresult);
									
							$html.= '<td class="orange"><span><ul class="time-inline">'.$Totaltime->time.'</ul></span></td>';
							
							$html.=''.$htmls.'';
							
							$html.= '<td class='.$coloravg.'><span>'.$showlearningavg.'</span></td>
						</tr>';
						//	}
						}
					}else{
						$html.='<tr>';
						$html.='<td>No user </td>';
						$html.='<td colspan="8">No Record found</td>';
						$html.='</tr>';
					}
						$html.="</div>
</div>	
</body>

<script src='js/jquery.dataTables.min.js'></script>
<script src='js/dataTables.bootstrap.min.js'></script>

<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
	  'deferRender': true,
	  'pageLength': 10
    })
  })
 
	
	
</script>
</html>";
					    $html.='</table>';
						echo $html;
						die();

	
}
?>