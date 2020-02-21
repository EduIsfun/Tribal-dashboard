<?php
session_start();
include('db_config.php');
include('model/Teacher.class.php');
global $conn;
$teacher = new Teacher();
$html = " ";

/* echo $query = "SELECT * FROM user";
$results = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($results) ){ 
	echo 'Name => '. $row['name'];
} */

if(isset($_POST['school'])){
	$schoolid = $_POST['school'];
	$classid = $_POST['classid'];
	//$chapterid = $_POST['chapter'];
	$subchapterid = $_POST['subchapterid'];



$html .= '<table class="table" id="userresult">
				<thead>
					<tr>
						<th> Name &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Class Rank &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Global Rank &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
						<th>Time Spent &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th> 
						<th>Overall Grade &nbsp <i class="fa fa-caret-down" aria-hidden="true"></i></th>
					</tr>
				</thead>';
				
					$user = $teacher->getuserResult($schoolid,$classid,$subchapterid);
					if(mysqli_num_rows($user)>0){
						while( $userlist = mysqli_fetch_object($user) ){
							
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
                              					
							$html.= '<tr>
									<td><span class="span_inline"><img src="'.$img_grade.'" alt="icon" /> &nbsp &nbsp <a href="edufun.php?id='.$userlist->userID.'">'.$userlist->name.' </a></span></td>';
									$i=1;
									$rank = 0;
									$overallresult = $teacher->getrankuserResult($schoolid);
									while($classrank = mysqli_fetch_object($overallresult)){
									// echo '<pre>';
									// print_r($classrank);
									// echo '</pre>';
										if($classrank->userid==$userlist->userID){
											$rank = $i;
										}
											$i++;
									}
							$html.= '<td class="orange"><span>'.$rank.'</span></td>';
									$i=1;				
									$globalranks = 0;				
									$globalresult = $teacher->getglobalrankResult($classid);	
									while($globalrank = mysqli_fetch_object($globalresult)){
									// echo '<pre>';
									// print_r($globalrank);
									// echo '</pre>';							
										if($globalrank->userid==$userlist->userID){						
											$globalranks = $i;					
									}					
											$i++;				
										}		
							$html.='<td class="orange"><span>'.$globalranks.'</span></td>';
									
									$timeresult = $teacher->getrankTimestamp($userlist->userID);			    
									$Totaltime = mysqli_fetch_object($timeresult);
									
							$html.= '<td class="orange"><span><ul class="time-inline">'.$Totaltime->time.'</ul></span></td>';
							
							
							
							$html.= '<td class='.$coloravg.'><span>'.$showlearningavg.'</span></td>
						</tr>';
						}
					}else{
						$html.='<tr>';
						$html.='<td>No user </td>';
						$html.='<td colspan="4">No Record found</td>';
						$html.='</tr>';
					}
					
					    $html.='</table>';
						echo $html;
						die();
						
	}     
						


?>
