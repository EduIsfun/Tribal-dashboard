
<?php 
session_start();
$_SESSION['uids'];
// error_reporting(E_ALL);
// ini_set("display_errors",1);
error_reporting(0);
include('db_config.php');

global $conn;
include('model/Dashboard.class.php');
include('model/Teacher.class.php');

$teacher = new Teacher();
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
 //$school_id =isset($_POST['school_id'])?$_POST['school_id']:'';
 $schoollist =isset($_POST['school_id'])?$_POST['school_id']:'';
 $schoolname = str_replace("|", "','",  $schoollist );
 $schoolselectedarray=explode("|",$_SESSION['schoolname']);
	
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
	<table id="example1" class="table sub_table1 main-table">
			<thead>
			<tr>
				<th>SR.NO.</th>
				<th>Name</th>';
				
			if 	(count($schoolselectedarray)>1) { 		
				$html.= '<th>School</th>';
			}
				for ($i=1;$i<=$chaptercount;$i++){ 
				$html.='<th>I-'.$i.'</th>';
				}  
				$html.='<th>Overall</th>
			
			</tr>';
			$resultskill = $dashboard->getskillResult($chapter_id);
			$learningaverage =0;
			$overallavgcount =0;
			$overalllavgearning =0;
			$coloravg='';
			$img_grade='';
			$html1.='<tr>
					<td class="fixed-side"> &nbsp &nbsp </td>
					<td class="fixed-side"> Class Average</td>';
					$countssss='';
						while($rowskill = mysqli_fetch_object($resultskill)){
							$skillid=$rowskill->skillID;
							
							$resultlearningaverage = $dashboard->getlearningResultAverage($chapter_id,$schoolname,$classid,$subject_id,$state_id,$city_id,$skillid);

							$rowlearningaveareg=mysqli_fetch_object($resultlearningaverage);
							$learningaverage =$rowlearningaveareg->learningavg;
							if (mysqli_num_rows($resultlearningaverage)>0) {
								if (($rowlearningaveareg->learningavg=="") || ($rowlearningaveareg->learningavg=="0") ) {
									$learningaverage =0;
									$showlearningavg ="E2";
									$coloravg ="red";

									

									if(($rowlearningaveareg->learningavg== null))
									{
										$learningaverage =0;	
										$showlearningavg ="N/A";
										$coloravg ="grey2";
									}
								} else {
									if ($learningaverage>=90){
										$showlearningavg ="A1";
										$coloravg ="greenr12";
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
									} else if (($learningaverage>0) && ($learningaverage<20)){
										$showlearningavg ="E2";
										$coloravg ="red";
									} else if (($learningaverage<=0)){
										$showlearningavg ="E2";
										$coloravg ="red";
									}
									$overallavgcount++;
								}
								
							} else {
								/*$learningaverage =0;	
								$showlearningavg ="E2";
								$coloravg ="red";*/
								$learningaverage =0;	
								$showlearningavg ="N/A";
								$coloravg ="grey2";
							}	
							$html1.='<td><span class='.$coloravg.'>'.$showlearningavg.'</span></td>';
						}  
						
						if ($overallavgcount>0){
							$overallavgshowlearning =round($overalllavgearning/$overallavgcount);
						} else {
							$overallavgshowlearning =round($overalllavgearning);
						}	
						if ($overallavgshowlearning>=90){
							$showoveravgall ="A1";
							$coloravg ="greenr12";
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
							
						}
						else if (($overallavgshowlearning>=0) && ($overallavgshowlearning<20)){
							$showoveravgall ="E2";
							$coloravg ="red";
							$img_grade = 'images/red.png';
						}

						
						
						$html1.='<td><span class='.$coloravg.'>'.$showoveravgall.'</span></td>
						</tr>';
						$subtopic='';
						$ids = $dashboard->getuserResultIDs($chapter_id,$schoolname,$classid,$subject_id,$state_id,$city_id);
						$user = $dashboard->getuserResult($chapter_id,$schoolname,$classid,$subject_id,$state_id,$city_id);
						//$user = $teacher->getuserResult($classid,$subject_id,$school_id,$state_id,$city_id,$chapter_id,$subtopic);
			
			// print_r($user);
					// die();
					
			if($user){
				$count = 1;
			}
			
					$html.='<div class="box-tools pull-right" style="display:none">
							<a href="download-chapter.php?state_id='.$_POST['state_id'].'&city_id='.$city_id.'&school_id='.$schoolname.'&classid='.$classid.'&subject_id='.$subject_id.'&chapter_id='.$chapter_id.'" class="btn btn-success button-loading pull-right" id="btns" onclick="getExport();">Export Record</a>
						</div>';
						
						$html.='
							<input type="hidden" id="studentids" value="'.$ids.'">
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
								$showlearning ="E2";
								$color ="red";
								$img_grade = 'images/red.png';
							} else {
								$learning =$rowlearning->learning;
								if ($learning>=90){
									$showlearning ="A1";
									$color ="greenr12";
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
							$showlearning ="E2";
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
						$color =" greenr12";
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
				
				 </td>';
				 if 	(count($schoolselectedarray)>1) { 		
						$html.= ' <td>'.$userlist->school.'</td>';;
				}
				
					
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
								$showlearning ="E2";
								$color ="red1";
							} else {
								$learning =$rowlearning->learning;
								if ($learning>=90){
									$showlearning ="A1";
									$color ="greenr12";
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
							}	
						
						} else {
							// $learning =0;
							// $showlearning ="E2w";
							// $color ="red1";

							$learning =0;	
							$showlearning ="N/A";
							$color ="grey2";
							$overallcount--;
						}
						$overalllearning =$overalllearning+$learning;
						$overallcount++;
					
					
					//$html.='<td><span class='.$color.'>'.$showlearning.'</span>';
					$html.='<td><div class="popupHoverElement"><span class="'.$color.'">'.$showlearning.'</span>';
					$html.='<div id="two" class="popupBox1">';
					$html.='<h2> '.$rowskill->skill.' </h2>';
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
						$color =" greenr12";
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
</div>
<style>
.grey2 {
    background: #e2e3e5;
    color: #ffff;
    padding: 5px 20px;
    border-radius: 20px;
}
</style>
';
// $html.="<script src='js/jquery.dataTables.min.js'></script>
// 		<script src='js/dataTables.bootstrap.min.js'></script>
// 		<script>
// 			$(function () {
// 				$('#example1').DataTable()
// 				$('#example2').DataTable({
// 					'paging'      : true,
// 					'lengthChange': false,
// 					'searching'   : false,
// 					'ordering'    : true,
// 					'info'        : true,
// 					'autoWidth'   : false
// 				})
// 			})
// 		</script>";
echo $html;
die();
}

?>
