
<?php 
session_start();
/*error_reporting(E_ALL);
ini_set("display_errors",1);*/
error_reporting(0);
include('../db_config.php');

global $conn;

function __autoload($classname){
	include("Classes/$classname.class.php");
}

$dashboard = new Dashboard();
$html='';
//action=getresult&schoolid="+schoolid+"&classid="+classid+"&chapterid="+chapterid,
if (isset($_POST['action']) && ($_POST['action']="getchapter")){
	
	$schoolid=isset($_POST['schoolid'])?$_POST['schoolid']:'';
	$classid=isset($_POST['classid'])?$_POST['classid']:'';
	$chapterid=isset($_POST['chapterid'])?$_POST['chapterid']:'';
	
	$resultskill = $dashboard->getskillResult($chapterid);
	$chaptercount =mysqli_num_rows($resultskill);
	
	$html='<div id="studentresult">
	<div id="table-scroll" class="table-scroll">
	 <div class="table-wrap">
	<table class="table sub_table1 main-table">
			<thead>
			<tr>
				<th class="fixed-side" scope="col">Name</th>';
				for ($i=1;$i<=$chaptercount;$i++){ 
				$html.='<th>3-'.$i.'</th>';
				}  
				$html.='<th>Overall</th>
			</tr>
			</thead>
			<tbody>';
			$resultskill = $dashboard->getskillResult($chapterid);
			$learningaverage =0;
			$overallavgcount =0;
			$overalllavgearning =0;
			$coloravg='';
			$html.='<tr>
					<td class="fixed-side"> Class Average</td>';
						while($rowskill = mysqli_fetch_object($resultskill)){
							$resultlearningaverage = $dashboard->getlearningResultAverage($rowskill->skillID,strtolower($schoolid),$classid);
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
										$coloravg ="greenr";
									} else if (($learningaverage>=80) && ($learningaverage<90)){
										$showlearningavg ="A2";
										$coloravg ="greenr";
									} else if (($learningaverage>=70) && ($learningaverage<80)){
										$showlearningavg ="B1";
										$coloravg ="oranger";
									} else if (($learningaverage>=60) && ($learningaverage<70)){
										$showlearningavg ="B2";
										$coloravg ="oranger";
									} else if (($learningaverage>=50) && ($learningaverage<60)){
										$showlearningavg ="C1";
										$coloravg ="greyr";
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
							$html.='<td><span class='.$coloravg.'>'.$showlearningavg.'</span></td>';
						}  
						
						
						
						
						
						if ($overallavgcount>0){
							$overallavgshowlearning =round($overalllavgearning/$overallavgcount);
						} else {
							$overallavgshowlearning =round($overalllavgearning);
						}	
						if ($overallavgshowlearning>=90){
							$showoveravgall ="A1";
							$coloravg ="greenr";
						} else if (($overallavgshowlearning>=80) && ($overallavgshowlearning<90)){
							$showoveravgall ="A2";
							$coloravg ="greenr";
						} else if (($overallavgshowlearning>=70) && ($overallavgshowlearning<80)){
							$showoveravgall ="B1";
							$coloravg ="oranger";
						} else if (($overallavgshowlearning>=60) && ($overallavgshowlearning<70)){
							$showoveravgall ="B2";
							$coloravg ="oranger";
						} else if (($overallavgshowlearning>=50) && ($overallavgshowlearning<60)){
							$showoveravgall ="C1";
							$coloravg ="greyr";
						} else if (($overallavgshowlearning>=40) && ($overallavgshowlearning<50)){
							$showoveravgall ="C2";
							$coloravg ="grey";
						} else if (($overallavgshowlearning>=33) && ($overallavgshowlearning<40)){
							$showoveravgall ="D";
							$coloravg ="dark_greyr";
						} else if (($overallavgshowlearning>=20) && ($overallavgshowlearning<30)){
							$showoveravgall ="E1";
							$coloravg ="bluer";
						} else if (($overallavgshowlearning>=0) && ($overallavgshowlearning<20)){
							$showoveravgall ="E1";
							$coloravg ="bluer";
						}
						
						$html.='<td><span class='.$coloravg.'>'.$showoveravgall.'</span></td>
						</tr>';
					
			$user = $dashboard->getuserResult(strtolower($schoolid),$classid);
			if (mysqli_num_rows($user)>0) {
				while($userlist = mysqli_fetch_object($user)){
				$html.='<tr>
					<td class="fixed-side circle_before"> <i class="fa fa-circle-thin" aria-hidden="true""></i> &nbsp'.$userlist->name.'</td>';
					
					$resultskill = $dashboard->getskillResult($chapterid);
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
								$color ="bluer";
							} else {
								$learning =$rowlearning->learning;
								if ($learning>=90){
									$showlearning ="A1";
									$color ="greenr";
								} else if (($learning>=80) && ($learning<90)){
									$showlearning ="A2";
									$color ="greenr";
								} else if (($learning>=70) && ($learning<80)){
									$showlearning ="B1";
									$color ="oranger";
								} else if (($learning>=60) && ($learning<70)){
									$showlearning ="B2";
									$color ="oranger";
								} else if (($learning>=50) && ($learning<60)){
									$showlearning ="C1";
									$color ="greyr";
								} else if (($learning>=40) && ($learning<50)){
									$showlearning ="C2";
									$color ="greyr";
								} else if (($learning>=33) && ($learning<40)){
									$showlearning ="D";
									$color ="dark_greyr";
								} else if (($learning>=20) && ($learning<30)){
									$showlearning ="E1";
									$color ="bluer";
								} else if (($learning>=0) && ($learning<20)){
									$showlearning ="E2";
									$color ="bluer";
								}
								
								$overallcount++;
							}	
							$overalllearning =$overalllearning+$learning;
						} else {
							$learning =0;
							$showlearning ="E1";
							$color ="bluer";
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
						$color ="greenr";
					} else if (($overallshowlearning>=80) && ($overallshowlearning<90)){
						$showoverall ="A2";
						$color ="greenr";
					} else if (($overallshowlearning>=70) && ($overallshowlearning<80)){
						$showoverall ="B1";
						$color ="oranger";
					} else if (($overallshowlearning>=60) && ($overallshowlearning<70)){
						$showoverall ="B2";
						$color ="oranger";
					} else if (($overallshowlearning>=50) && ($overallshowlearning<60)){
						$showoverall ="C1";
						$color ="greyr";
					} else if (($overallshowlearning>=40) && ($overallshowlearning<50)){
						$showoverall ="C2";
						$color ="grey";
					} else if (($overallshowlearning>=33) && ($overallshowlearning<40)){
						$showoverall ="D";
						$color ="dark_greyr";
					} else if (($overallshowlearning>=20) && ($overallshowlearning<30)){
						$showoverall ="E1";
						$color ="bluer";
					} else if (($overallshowlearning>=0) && ($overallshowlearning<20)){
						$showoverall ="E1";
						$color ="bluer";
					}
					$html.='<td><span class='.$color.'>'.$showoverall.'</span></td>
				</tr>';
				
				}
			} else {
					$html.='<tr>';
					$html.='<td class="circle_before">No user </td>';
					$html.='<td colspan='.$chaptercount.'>No Record found</td>';
					$html.='</tr>';
			}		
		$html.='</tbody>
	</table>
	 </div>
	 </div>
</div>';
}
echo $html;
?>

<script>// requires jquery library
jQuery(document).ready(function() {
   jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');   
 });
</script>