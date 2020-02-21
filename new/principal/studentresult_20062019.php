
<?php 
session_start();
$_SESSION['uids'];
// error_reporting(E_ALL);
// ini_set("display_errors",1);
error_reporting(0);
include('db_config.php');

global $conn;
//include('model/Teacher.class.php');
include('model/Dashboard.class.php');

$dashboard = new Dashboard();
$html='';

		$sql = "SELECT * FROM teacher_login WHERE userID = '".$_SESSION['uids']."'";
		$result = mysqli_query($conn,$sql);
		$obj = mysqli_fetch_object($result);

		$school_id = $obj->school;
		$state_id = $obj->state_id;
		$city_id = $obj->city_id;

	
	
if (isset($_POST['action']) && ($_POST['action']="getchapter")){
	
	
 $chapter_id = isset($_POST['chapter_id'])?$_POST['chapter_id']:'';
 $school_id =isset($_POST['school_id'])?$_POST['school_id']:'';
 $classid = isset($_POST['class_id'])?$_POST['class_id']:'';
 if ($classid=="all"){
		$classid ='';
	}	
 $subject_id = isset($_POST['subject_id'])?$_POST['subject_id']:'';
 $state_id =isset($_POST['state_id'])?$_POST['state_id']:'';
 $city_id =isset($_POST['city_id'])?$_POST['city_id']:'';

	// $resultchapterid = $dashboard->getChapterid($chapter_name);
	// $chapterlist=mysqli_fetch_object($resultchapterid);
    // $chapter_id=$chapterlist->chapterID;
		$resultskill = $dashboard->getskillResult($chapter_id);
		$chaptercount =mysqli_num_rows($resultskill);
	
	$html='<div id="userresult">
	<div id="table-scroll" class="table-scroll">
	 <div class="table-wrap table_overflow">
	<table class="table sub_table1 main-table">
			<thead>
			<tr><th>SR.NO.</th><th>Name</th>';
				for ($i=1;$i<=$chaptercount;$i++){ 
				$html.='<th>3-'.$i.'</th>';
				}  
				$html.='<th>Overall</th>
			
			</tr>';
			$resultskill = $dashboard->getskillResult($chapter_id);
			$learningaverage =0;
			$overallavgcount =0;
			$overalllavgearning =0;
			$coloravg='';
			$img_grade='';
			$html.='<tr>
					<td class="fixed-side"> &nbsp &nbsp </td>
					<td class="fixed-side"> Class Average</td>';
					$countssss='';
						while($rowskill = mysqli_fetch_object($resultskill)){
							$skillid=$rowskill->skillID;
							
							$resultlearningaverage = $dashboard->getlearningResultAverage($chapter_id,$school_id,$classid,$subject_id,$state_id,$city_id);
							
							$rowlearningaveareg=mysqli_fetch_object($resultlearningaverage);
							$learningaverage =$rowlearningaveareg->learningavg;
							if (mysqli_num_rows($resultlearningaverage)>0) {
								if (($rowlearningaveareg->learningavg=="") || ($rowlearningaveareg->learningavg=="0") ) {
									$learningaverage =0;
									$showlearningavg ="E1";
									$coloravg ="red";
								} else {
									if ($learningaverage>=90){
										$showlearningavg ="A1";
										$coloravg ="greenr1";
									} else if (($learningaverage>=80) && ($learningaverage<90)){
										$showlearningavg ="A2";
										$coloravg ="greenr2";
									} else if (($learningaverage>=70) && ($learningaverage<80)){
										$showlearningavg ="B1";
										$coloravg ="yello1";
									} else if (($learningaverage>=60) && ($learningaverage<70)){
										$showlearningavg ="B2";
										$coloravg ="yello2";
									} else if (($learningaverage>=50) && ($learningaverage<60)){
										$showlearningavg ="C1";
										$coloravg ="oran1";
									} else if (($learningaverage>=40) && ($learningaverage<50)){
										$showlearningavg ="C2";
										$coloravg ="oran2";
									} else if (($learningaverage>=33) && ($learningaverage<40)){
										$showlearningavg ="D";
										$coloravg ="blue2";
									} else if (($learningaverage>=20) && ($learningaverage<30)){
										$showlearningavg ="E1";
										$coloravg ="red";
									} else if (($learningaverage>=0) && ($learningaverage<20)){
										$showlearningavg ="E2";
										$coloravg ="red1";
									}
									$overallavgcount++;
								}
								$overalllavgearning =$overalllavgearning+$learningaverage;
							} else {
								$learningaverage =0;	
								$showlearningavg ="E1";
								$coloravg ="red";
							}	
							$html.='<td><span class='.$coloravg.'>'.$showlearningavg.'</span></td>';
						}  
						
						
						
						
						
						if ($overallavgcount>0){
							$overallavgshowlearning =round($overalllavgearning/$overallavgcount);
						} else {
							$overallavgshowlearning =round($overalllavgearning);
						}	
						if ($overallavgshowlearning>=90){
							$showoveravgall ="A1";
							$coloravg ="greenr1";
							 $img_grade = 'images/green.png';
						} else if (($overallavgshowlearning>=80) && ($overallavgshowlearning<90)){
							$showoveravgall ="A2";
							$coloravg ="greenr2";
							 $img_grade = 'images/green.png';
						} else if (($overallavgshowlearning>=70) && ($overallavgshowlearning<80)){
							$showoveravgall ="B1";
							$coloravg ="yello1";
							$img_grade = 'images/yellow.png';
						} else if (($overallavgshowlearning>=60) && ($overallavgshowlearning<70)){
							$showoveravgall ="B2";
							$coloravg ="yello2";
							$img_grade = 'images/yellow.png';
						} else if (($overallavgshowlearning>=50) && ($overallavgshowlearning<60)){
							$showoveravgall ="C1";
							$coloravg ="oran1";
								$img_grade = 'images/orng.png';
						} else if (($overallavgshowlearning>=40) && ($overallavgshowlearning<50)){
							$showoveravgall ="C2";
							$coloravg ="oran2";
								$img_grade = 'images/orng.png';
						} else if (($overallavgshowlearning>=30) && ($overallavgshowlearning<40)){
							$showoveravgall ="D";
							$coloravg ="blue2";
							$img_grade = 'images/blue.png';
						} else if (($overallavgshowlearning>=20) && ($overallavgshowlearning<30)){
							$showoveravgall ="E1";
							$coloravg ="red";
							$img_grade = 'images/red.png';
							
						} else if (($overallavgshowlearning>=0) && ($overallavgshowlearning<20)){
							$showoveravgall ="E2";
							$coloravg ="red1";
							$img_grade = 'images/red.png';
						}
						
						$html.='<td><span class='.$coloravg.'>'.$showoveravgall.'</span></td>
						</tr>';
						
						$ids = $dashboard->getuserResultIDs($chapter_id,$school_id,$classid,$subject_id,$state_id,$city_id);
						$user = $dashboard->getuserResult($chapter_id,$school_id,$classid,$subject_id,$state_id,$city_id);
			
			// print_r($user);
					// die();
					
			if($user){
				$count = 1;
			}
			
					$html.='<div class="box-tools pull-right">
							<a href="download-chapter.php?state_id='.$_POST['state_id'].'&city_id='.$city_id.'&school_id='.$school_id.'&classid='.$classid.'&subject_id='.$subject_id.'&chapter_id='.$chapter_id.'" class="btn btn-success button-loading pull-right" id="btns" onclick="getExport();">Export Record</a>
						</div>';
						
						$html.='
							<input type="hidden" id="studentids" value="'.$ids.'">
							<input type="button" onclick="getPdf();"  class="btn btn-success name="PDF" value="PDF" style="margin-left:475px;">
							<span id="userresults"></span>
						</thead>
					<tbody>';
			
			if (mysqli_num_rows($user)>0) {
				while($userlist = mysqli_fetch_object($user)){
					
					//color of image start here
					$resultskill = $dashboard->getskillResult($chapter_id);
					$overalllearning =0;
					$overallcount =0;
					$color='';
					while($rowskill = mysqli_fetch_object($resultskill)){
					   
					   $resultlearning = $dashboard->getlearningResult($rowskill->skillID,$userlist->userID);
						$rowlearning=mysqli_fetch_object($resultlearning);
						if (mysqli_num_rows($resultlearning)>0) {
							if (($rowlearning->learning=="") || ($rowlearning->learning=="0") ) {
								$learning =0;
								$showlearning ="E1";
								$color ="red";
								$img_grade = 'images/red.png';
							} else {
								$learning =$rowlearning->learning;
								if ($learning>=90){
									$showlearning ="A1";
									$color ="greenr1";
									$img_grade = 'images/green.png';
								} else if (($learning>=80) && ($learning<90)){
									$showlearning ="A2";
									$color ="greenr2";
									$img_grade = 'images/green.png';
								} else if (($learning>=70) && ($learning<80)){
									$showlearning ="B1";
									$color ="yello1";
									$img_grade = 'images/yellow.png';
								} else if (($learning>=60) && ($learning<70)){
									$showlearning ="B2";
									$color ="yello2";
									$img_grade = 'images/yellow.png';
								} else if (($learning>=50) && ($learning<60)){
									$showlearning ="C1";
									$color ="oran1";
									$img_grade = 'images/orng.png';
								} else if (($learning>=40) && ($learning<50)){
									$showlearning ="C2";
									$color ="oran2";
									$img_grade = 'images/orng.png';
								} else if (($learning>=33) && ($learning<40)){
									$showlearning ="D";
									$color ="blue2";
									$img_grade = 'images/blue.png';
								} else if (($learning>=20) && ($learning<30)){
									$showlearning ="E1";
									$color ="red";
									$img_grade = 'images/red.png';
								} else if (($learning>=0) && ($learning<20)){
									$showlearning ="E2";
									$color ="red1";
									$img_grade = 'images/red.png';
								}
								
								$overallcount++;
							}	
							$overalllearning =$overalllearning+$learning;
						} else {
							$learning =0;
							$showlearning ="E1";
							$color ="red";
						}
					
					} 
					
					
					if ($overallcount>0){
						$overallshowlearning =round($overalllearning/$overallcount);
					} else {
						$overallshowlearning =round($overalllearning);
					}	
					if ($overallshowlearning>=90){
						$showoverall ="A1";
						$color ="greenr1";
					 $img_grade = 'images/green.png';
					} else if (($overallshowlearning>=80) && ($overallshowlearning<90)){
						$showoverall ="A2";
						$color ="greenr2";
						 $img_grade = 'images/green.png';
					} else if (($overallshowlearning>=70) && ($overallshowlearning<80)){
						$showoverall ="B1";
						$color ="yello1";
						$img_grade = 'images/yellow.png';
					
					} else if (($overallshowlearning>=60) && ($overallshowlearning<70)){
						$showoverall ="B2";
						$color ="yello2";
						$img_grade = 'images/yellow.png';
						
					} else if (($overallshowlearning>=50) && ($overallshowlearning<60)){
						$showoverall ="C1";
						$color ="oran1";
					$img_grade = 'images/orng.png';
					} else if (($overallshowlearning>=40) && ($overallshowlearning<50)){
						$showoverall ="C2";
						$color ="oran1";
						$img_grade = 'images/orng.png';
					} else if (($overallshowlearning>=30) && ($overallshowlearning<40)){
						$showoverall ="D";
						$color ="blue2";
					$img_grade = 'images/blue.png';
					} else if (($overallshowlearning>=20) && ($overallshowlearning<30)){
						$showoverall ="E1";
						$color ="red";
						$img_grade = 'images/red.png';
						
					} else if (($overallshowlearning>=0) && ($overallshowlearning<20)){
						$showoverall ="E2";
						$color ="red1";
						$img_grade = 'images/red.png';
					
					}
					
					//color of image end here
				$html.='<tr><td class="fixed-side"> <span class="circle_before">  &nbsp &nbsp '.$count++.' </span></td><td class="fixed-side"> 
					
							<span class="circle_before"><img src="'.$img_grade.'" alt="icon" /><span>
							 &nbsp <a href="edufun.php?id='.$userlist->userID.'" target="_blank">'.$userlist->name.'</a>
				
				 </td>
					';
					
					$resultskill = $dashboard->getskillResult($chapter_id);
					$overalllearning =0;
					$overallcount =0;
					$color='';
					while($rowskill = mysqli_fetch_object($resultskill)){
					   
					   $resultlearning = $dashboard->getlearningResult($rowskill->skillID,$userlist->userID);
						$rowlearning=mysqli_fetch_object($resultlearning);
						if (mysqli_num_rows($resultlearning)>0) {
							if (($rowlearning->learning=="") || ($rowlearning->learning=="0") ) {
								$learning =0;
								$showlearning ="E1";
								$color ="red";
							} else {
								$learning =$rowlearning->learning;
								if ($learning>=90){
									$showlearning ="A1";
									$color ="greenr1";
								} else if (($learning>=80) && ($learning<90)){
									$showlearning ="A2";
									$color ="greenr2";
								} else if (($learning>=70) && ($learning<80)){
									$showlearning ="B1";
									$color ="yello1";
								} else if (($learning>=60) && ($learning<70)){
									$showlearning ="B2";
									$color ="yello2";
								} else if (($learning>=50) && ($learning<60)){
									$showlearning ="C1";
									$color ="oran1";
								} else if (($learning>=40) && ($learning<50)){
									$showlearning ="C2";
									$color ="oran2";
								} else if (($learning>=33) && ($learning<40)){
									$showlearning ="D";
									$color ="blue2";
								} else if (($learning>=20) && ($learning<30)){
									$showlearning ="E1";
									$color ="red";
								} else if (($learning>=0) && ($learning<20)){
									$showlearning ="E2";
									$color ="red1";
								}
								
								$overallcount++;
							}	
							$overalllearning =$overalllearning+$learning;
						} else {
							$learning =0;
							$showlearning ="E1";
							$color ="red";
						}
						
					
					
					//$html.='<td><span class='.$color.'>'.$showlearning.'</span>';
					$html.='<td><div class="popupHoverElement"><span class="'.$color.'">'.$showlearning.'</span>';
					$html.='<div id="two" class="popupBox1">';
					$html.='<h2> '.$rowskill->skill.' </h2>';
					$html.='<table style="width:100%;">';
					$html.='<tr>';
					$html.='<td>80%</td>';
					$html.='<td>70%</td>';
					$html.='<td>60%</td>';
					$html.='<td>50%</td>';
					$html.='<td>20%</td>';
					$html.= '</tr>';
					$html.= '</table>';		
					$html.= '</div>';		
					$html.= '</div>';		
					$html.='</td>';
					
					} 
					if ($overallcount>0){
						$overallshowlearning =round($overalllearning/$overallcount);
					} else {
						$overallshowlearning =round($overalllearning);
					}	
					if ($overallshowlearning>=90){
						$showoverall ="A1";
						$color ="greenr1";
					 $img_grade = 'images/green.png';
					} else if (($overallshowlearning>=80) && ($overallshowlearning<90)){
						$showoverall ="A2";
						$color ="greenr2";
						 $img_grade = 'images/green.png';
					} else if (($overallshowlearning>=70) && ($overallshowlearning<80)){
						$showoverall ="B1";
						$color ="yello1";
						$img_grade = 'images/yellow.png';
					
					} else if (($overallshowlearning>=60) && ($overallshowlearning<70)){
						$showoverall ="B2";
						$color ="yello2";
						$img_grade = 'images/yellow.png';
						
					} else if (($overallshowlearning>=50) && ($overallshowlearning<60)){
						$showoverall ="C1";
						$color ="oran1";
					$img_grade = 'images/orng.png';
					} else if (($overallshowlearning>=40) && ($overallshowlearning<50)){
						$showoverall ="C2";
						$color ="oran2";
						$img_grade = 'images/orng.png';
					} else if (($overallshowlearning>=30) && ($overallshowlearning<40)){
						$showoverall ="D";
						$color ="blue2";
					$img_grade = 'images/blue.png';
					} else if (($overallshowlearning>=20) && ($overallshowlearning<30)){
						$showoverall ="E1";
						$color ="red";
						$img_grade = 'images/red.png';
						
					} else if (($overallshowlearning>=0) && ($overallshowlearning<20)){
						$showoverall ="E2";
						$color ="red1";
						$img_grade = 'images/red.png';
					
					}
					$html.='<td><span class='.$color.'>'.$showoverall.'</span></td>';
				
					$html.='
				</tr>';
				
				}
			} else {
					$html.='<tr>';
					$html.='<td class="circle_before">No user </td>';
					$html.='<td colspan="14">No Record found</td>';
					$html.='</tr>';
			}		
		$html.='</tbody>
	</table>
	 </div>
	 </div>
</div>';
echo $html;
die();
}

?>
