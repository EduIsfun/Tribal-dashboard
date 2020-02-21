<?php 
session_start();
// error_reporting(E_ALL);
// ini_set("display_errors",1);

include('../db_config.php'); global $conn; function __autoload($classname){
	include("Classes/$classname.class.php");
}
$dashboard = new Dashboard();

$html='';

if (isset($_POST['action']) && ($_POST['action']="getchapter")){
	$schoolid=isset($_POST['schoolid'])?$_POST['schoolid']:'';
	$classid=isset($_POST['classid'])?$_POST['classid']:'';
	$chapterid=isset($_POST['chapterid'])?$_POST['chapterid']:''; 	
	$html='<div id="studentresult">
	<div id="table-scroll" class="table-scroll">
	 <div class="table-wrap">
	<table class="table sub_table1 main-table">
			<thead>
			<tr>
				<th class="fixed-side">Name</th>
				<th>Class Rank</th>
				<th>Global Rank</th>
				<th>Time Spent</th> 
				<th>Overall Grade</th>
			</tr>
		</thead>
		<tbody>';

		$user = $dashboard->getuserResult($schoolid,$classid);
		if(mysqli_num_rows($user)>0){
			while($userlist = mysqli_fetch_object($user)){
				$html.=' <tr>
					<td class="fixed-side">'.$userlist->name.'</td>';
					$i=1;
					$allrank = 0;
					$overallresult = $dashboard->getrankuserResult($schoolid);
					while($classrank = mysqli_fetch_object($overallresult)){
						// echo '<pre>';
						// print_r($classrank);
						// echo '</pre>';
						if($classrank->userid==$userlist->userID){
							$allrank = $i;
						}
						$i++;
					}
				$html.= '<td><span class="green dark_grey">'.$allrank.'</span></td>';				
				$i=1;				
				$rank = 0;				
				$globalresult = $dashboard->getglobalrankResult($userlist->userID,$classid);	
				while($globalrank = mysqli_fetch_object($globalresult)){
                        // echo '<pre>';
						// print_r($globalrank);
						// echo '</pre>';							
				if($globalrank->userid==$userlist->userID){						
				$rank = $i;					
				}					
				$i++;				
				}		
				$html.='<td><span class="green dark_grey">'.$rank.'</span></td>';	
				$timeresult = $dashboard->getrankTimestamp($userlist->userID);			    
				$Totaltime = mysqli_fetch_object($timeresult);				
				$html.='<td><span class="green blue">'.$Totaltime->time.'</h2></td>';
				
				$learnincurrancy =0;
			     $coloravg='';
				
				$overallgrade = $dashboard->getoverallGrade($userlist->userID);	
                $allgrade = mysqli_fetch_object($overallgrade);	
                $learnincurrancy =$allgrade->currency;
							if (mysqli_num_rows($overallgrade)>0) {
								if (($allgrade->currency=="") || ($allgrade->currency=="0")) {
									$learnincurrancy =0;
									$showlearningavg ="E1";
									$coloravg ="bluer";	

                                } else {
									if ($learnincurrancy>=90){
										$showlearningavg ="A1";
										$coloravg ="greenr";
									} else if (($learnincurrancy>=80) && ($learnincurrancy<90)){
										$showlearningavg ="A2";
										$coloravg ="greenr";
									} else if (($learnincurrancy>=70) && ($learnincurrancy<80)){
										$showlearningavg ="B1";
										$coloravg ="oranger";
									} else if (($learnincurrancy>=60) && ($learnincurrancy<70)){
										$showlearningavg ="B2";
										$coloravg ="oranger";
									} else if (($learnincurrancy>=50) && ($learnincurrancy<60)){
										$showlearningavg ="C1";
										$coloravg ="greyr";
									} else if (($learnincurrancy>=40) && ($learnincurrancy<50)){
										$showlearningavg ="C2";
										$coloravg ="greyr";
									} else if (($learnincurrancy>=33) && ($learnincurrancy<40)){
										$showlearningavg ="D";
										$coloravg ="dark_greyr";
									} else if (($learnincurrancy>=20) && ($learnincurrancy<30)){
										$showlearningavg ="E1";
										$coloravg ="bluer";
									} else if (($learnincurrancy>=0) && ($learnincurrancy<20)){
										$showlearningavg ="E2";
										$coloravg ="bluer";
									}
								}
							}
                              									
				$html.='<td><span class='.$coloravg.'>'.$showlearningavg.'</span></td>
				</tr>';
				}
			} else {
				$html.='<tr>';
				$html.='<td class="fixed-side">No user </td>';
				$html.='<td colspan=>No Record found</td>';
				$html.='</tr>';
			}
		$html.='</tbody>
	</table>
</div>
	 </div>
</div>';
}
echo $html;


if (isset($_POST['action']) && ($_POST['action']="getresult")){
	$schoolid=isset($_POST['schoolid'])?$_POST['schoolid']:'';
	$classid=isset($_POST['classid'])?$_POST['classid']:'';
	$chapterid=isset($_POST['chapterid'])?$_POST['chapterid']:'';
	echo $chapterid;
}
?>
<script>// requires jquery library
jQuery(document).ready(function() {
   jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');   
 });
</script>