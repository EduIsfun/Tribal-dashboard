<?php 
		error_reporting(0);
		include('db_config.php');
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename = download-chapter-report.csv');
		$data = '';
		outputCSV($data);
		include('model/Dashboard.class.php');
		$dashboard = new Dashboard();
		function outputCSV($data) {
			$outstream = fopen("php://output", "a");
			$headers= 'Name, Grade, Overall Grade, Mobile, Email';
			$headers.= "\n";
			fwrite($outstream,$headers);
			function __outputCSV(&$vals, $key, $filehandler) {
			fputcsv($filehandler, $vals); // add parameters if you want
		}
			array_walk($data, "__outputCSV", $outstream);
			fclose($outstream);
 }

					$state = isset($_GET['state_id'])?$_GET['state_id']:'';
					$city_id = isset($_GET['city_id'])?$_GET['city_id']:'';
					$schoolid =isset($_GET['schoolid'])?$_GET['schoolid']:'';
					$classid =isset($_GET['classid'])?$_GET['classid']:'';
					$board_id = isset($_GET['board_id'])?$_GET['board_id']:'';
					$chapter_id = isset($_GET['chapter_id'])?$_GET['chapter_id']:'';
					$subject_id = isset($_GET['subject_id'])?$_GET['subject_id']:'';
				
				    
					$resultskill = $dashboard->getskillResult($chapter_id);
						$learningaverage =0;
						$overallavgcount =0;
						$overalllavgearning =0;
						$coloravg='';
						$img_grade='';
						//$show='';
						while($rowskill = mysqli_fetch_object($resultskill)){
							$skillid=$rowskill->skillID;
							
							$resultlearningaverage = $dashboard->getlearningResultAverage($state,$city_id,$board_id,$schoolid,$classid,$skillid,$subject_id,$chapter_id);
							
							$rowlearningaveareg=mysqli_fetch_object($resultlearningaverage);
							$learningaverage =isset($rowlearningaveareg->learningavg)?$rowlearningaveareg->learningavg:'';
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
										$coloravg ="greyr1";
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
								$learningaverage =0;	
								$showlearningavg ="E1";
								$coloravg ="bluer";
							}
							
							
								//$show[] = $showlearningavg;
								
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
							$coloravg ="greenr1";
							 $img_grade = 'images/green.png';
						} else if (($overallavgshowlearning>=70) && ($overallavgshowlearning<80)){
							$showoveravgall ="B1";
							$coloravg ="oranger1";
							$img_grade = 'images/yellow.png';
						} else if (($overallavgshowlearning>=60) && ($overallavgshowlearning<70)){
							$showoveravgall ="B2";
							$coloravg ="oranger1";
							$img_grade = 'images/yellow.png';
						} else if (($overallavgshowlearning>=50) && ($overallavgshowlearning<60)){
							$showoveravgall ="C1";
							$coloravg ="greyr1";
								$img_grade = 'images/blue.png';
						} else if (($overallavgshowlearning>=40) && ($overallavgshowlearning<50)){
							$showoveravgall ="C2";
							$coloravg ="grey1";
								$img_grade = 'images/blue.png';
						} else if (($overallavgshowlearning>=30) && ($overallavgshowlearning<40)){
							$showoveravgall ="D";
							$coloravg ="dark_greyr1";
							$img_grade = 'images/blue.png';
						} else if (($overallavgshowlearning>=20) && ($overallavgshowlearning<30)){
							$showoveravgall ="E1";
							$coloravg ="bluer";
							$img_grade = 'images/blue.png';
							
						} else if (($overallavgshowlearning>=0) && ($overallavgshowlearning<20)){
							$showoveravgall ="E1";
							$coloravg ="bluer";
							$img_grade = 'images/red.png';
						}
						
							//	print_r($showoveravgall);
								//$show[] = $showoveravgall;

			$user = $dashboard->getuserResult(strtolower($schoolid),$classid);
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
								$color ="bluer";
								$img_grade = 'images/red.png';
							} else {
								$learning =$rowlearning->learning;
								if ($learning>=90){
									$showlearning ="A1";
									$color ="greenr1";
									$img_grade = 'images/green.png';
								} else if (($learning>=80) && ($learning<90)){
									$showlearning ="A2";
									$color ="greenr1";
									$img_grade = 'images/green.png';
								} else if (($learning>=70) && ($learning<80)){
									$showlearning ="B1";
									$color ="oranger1";
									$img_grade = 'images/yellow.png';
								} else if (($learning>=60) && ($learning<70)){
									$showlearning ="B2";
									$color ="oranger1";
									$img_grade = 'images/yellow.png';
								} else if (($learning>=50) && ($learning<60)){
									$showlearning ="C1";
									$color ="greyr1";
									$img_grade = 'images/blue.png';
								} else if (($learning>=40) && ($learning<50)){
									$showlearning ="C2";
									$color ="greyr1";
									$img_grade = 'images/blue.png';
								} else if (($learning>=33) && ($learning<40)){
									$showlearning ="D";
									$color ="dark_greyr1";
									$img_grade = 'images/blue.png';
								} else if (($learning>=20) && ($learning<30)){
									$showlearning ="E1";
									$color ="bluer";
									$img_grade = 'images/red.png';
								} else if (($learning>=0) && ($learning<20)){
									$showlearning ="E2";
									$color ="bluer";
									$img_grade = 'images/red.png';
								}
								
								$overallcount++;
							}	
							$overalllearning =$overalllearning+$learning;
						} else {
							$learning =0;
							$showlearning ="E1";
							$color ="bluer";
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
						$color ="greenr1";
						 $img_grade = 'images/green.png';
					} else if (($overallshowlearning>=70) && ($overallshowlearning<80)){
						$showoverall ="B1";
						$color ="oranger1";
						$img_grade = 'images/yellow.png';
					
					} else if (($overallshowlearning>=60) && ($overallshowlearning<70)){
						$showoverall ="B2";
						$color ="oranger1";
						$img_grade = 'images/yellow.png';
						
					} else if (($overallshowlearning>=50) && ($overallshowlearning<60)){
						$showoverall ="C1";
						$color ="greyr1";
					$img_grade = 'images/blue.png';
					} else if (($overallshowlearning>=40) && ($overallshowlearning<50)){
						$showoverall ="C2";
						$color ="grey1";
						$img_grade = 'images/blue.png';
					} else if (($overallshowlearning>=30) && ($overallshowlearning<40)){
						$showoverall ="D";
						$color ="dark_greyr1";
					$img_grade = 'images/blue.png';
					} else if (($overallshowlearning>=20) && ($overallshowlearning<30)){
						$showoverall ="E1";
						$color ="bluer";
						$img_grade = 'images/red.png';
						
					} else if (($overallshowlearning>=0) && ($overallshowlearning<20)){
						$showoverall ="E1";
						$color ="bluer";
						$img_grade = 'images/red.png';
					
					}
					
						$studentName = $userlist->name;
						$studentMobile = $userlist->mobile;
						$studentEmail = $userlist->email;
					
					$resultskill = $dashboard->getskillResult($chapter_id);
					$overalllearning =0;
					$overallcount =0;
					$color='';
					$show=[];
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
									$color ="greenr1";
								} else if (($learning>=80) && ($learning<90)){
									$showlearning ="A2";
									$color ="greenr1";
								} else if (($learning>=70) && ($learning<80)){
									$showlearning ="B1";
									$color ="oranger1";
								} else if (($learning>=60) && ($learning<70)){
									$showlearning ="B2";
									$color ="oranger1";
								} else if (($learning>=50) && ($learning<60)){
									$showlearning ="C1";
									$color ="greyr1";
								} else if (($learning>=40) && ($learning<50)){
									$showlearning ="C2";
									$color ="greyr1";
								} else if (($learning>=33) && ($learning<40)){
									$showlearning ="D";
									$color ="dark_greyr1";
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
						$show[] = $showlearning;
					
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
						$color ="greenr1";
						 $img_grade = 'images/green.png';
					} else if (($overallshowlearning>=70) && ($overallshowlearning<80)){
						$showoverall ="B1";
						$color ="oranger1";
						$img_grade = 'images/yellow.png';
					
					} else if (($overallshowlearning>=60) && ($overallshowlearning<70)){
						$showoverall ="B2";
						$color ="oranger1";
						$img_grade = 'images/yellow.png';
						
					} else if (($overallshowlearning>=50) && ($overallshowlearning<60)){
						$showoverall ="C1";
						$color ="greyr1";
					$img_grade = 'images/blue.png';
					} else if (($overallshowlearning>=40) && ($overallshowlearning<50)){
						$showoverall ="C2";
						$color ="grey1";
						$img_grade = 'images/blue.png';
					} else if (($overallshowlearning>=30) && ($overallshowlearning<40)){
						$showoverall ="D";
						$color ="dark_greyr1";
					$img_grade = 'images/blue.png';
					} else if (($overallshowlearning>=20) && ($overallshowlearning<30)){
						$showoverall ="E1";
						$color ="bluer";
						$img_grade = 'images/red.png';
						
					} else if (($overallshowlearning>=0) && ($overallshowlearning<20)){
						$showoverall ="E1";
						$color ="bluer";
						$img_grade = 'images/red.png';
					
					}
						//	$showoverall
				
				      $showavg='';
				      for($i=0;$i<=count($show);$i++){
						$showavg.= $show[$i];
						//$showavg .= ", ";
						// $showinging.= implode(",",$showing);
						  
					  } 

				$field_name = $studentName .','. $showavg.','. $showoverall .',' . $studentMobile .',' . $studentEmail .','; 
					
						 if (preg_match('/\\r|\\n|,|"/', $field_name))
						{
							$field_name = '' . str_replace('','', $field_name) . '';
						}
							echo $separate . $field_name;
							//$separate = ',';
							echo "\n"; 
							
		
				}
?>