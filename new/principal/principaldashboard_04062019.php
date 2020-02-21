<?php
session_start();
if(empty($_SESSION['uids']))
{
	header("location:index.php");
}
	error_reporting(0);
	include('db_config.php');
	include('model/Teacher.class.php');
	include('model/Download.class.php');
	global $conn;
	$teacher = new Teacher();
	$download = new Download();

	$sql = "SELECT * FROM teacher_login WHERE userID = '".$_SESSION['uids']."'";
	$result = mysqli_query($conn,$sql);
	$obj = mysqli_fetch_object($result);

	$schoolname = $obj->school;
	$state_id = $obj->state_id;
	$city_id = $obj->city_id;
	$board_id = $obj->board_id;
	
	function ConverToRoman($num){ 
		$n = intval($num); 
		$res = ''; 

		//array of roman numbers
		$romanNumber_Array = array( 
			'M'  => 1000, 
			'CM' => 900, 
			'D'  => 500, 
			'CD' => 400, 
			'C'  => 100, 
			'XC' => 90, 
			'L'  => 50, 
			'XL' => 40, 
			'X'  => 10, 
			'IX' => 9, 
			'V'  => 5, 
			'IV' => 4, 
			'I'  => 1
		); 

		foreach ($romanNumber_Array as $roman => $number){ 
			//divide to get  matches
			$matches = intval($n / $number); 

			//assign the roman char * $matches
			$res .= str_repeat($roman, $matches); 

			//substract from the number
			$n = $n % $number; 
		} 

		// return the result
		return $res; 
	} 

	//echo ConverToRoman(23); 
 	$classid =isset($_POST['classid'])?$_POST['classid']:'I';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title> Dashboard</title>
	<meta charset="UTF-8">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Favicon -->   
	<link href="img/favicon.ico" rel="shortcut icon"/> 
	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/font-awesome.css" /> 
	<link rel="stylesheet" href="css/style.css"/>   
</head>
<body onload="myFunction()">
<!-- header -->
<div class="header-top">
	<div class="container-fluid"> 
			 <div class="row">
				<div class="col-md-4" style="padding: 10px;">
					<a href="#"><img src="images/logo.png"></a> 
				</div> 
				<div class="col-md-4 logo">
					<?php $school = $teacher->getschool();
				    $row=mysqli_fetch_object($school)
					?>
					<a href="#"><img src="images/logo2.png"></a> 
					<input type="hidden" name="school_id" id="school_id" value="<?php echo $schoolname; ?>">
					<input type="hidden" name="state_id" id="state_id" value="<?php echo $state_id; ?>">
					<input type="hidden" name="city_id" id="city_id" value="<?php echo $city_id; ?>">
				</div> 
				<div class="col-md-4">
					<div class="right-text">
						<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
						<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
						<button class="btn btn-primary" id="menu1" type="button" ><?php echo $schoolname;?><!--  dropdown-toggle data-toggle="dropdown"-->
							<!-- <span class="caret"></span> -->
						</button>
						<ul style="list-style-type: none; text-align: center; color: #428bca;"> 
							<!-- class="dropdown-menu" role="menu" aria-labelledby="menu1" --> 
							<li><a href="profile.php" class='cmenu'>Settings</a> <span style="color:#18294f;">|</span> <a href="logout.php" class='cmenu'>Sign out</a></li>
			                <!-- <li></li>   -->
						<!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="#">HTML</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#">CSS</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#">JavaScript</a></li>
						<li role="presentation" class="divider"></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#">About Us</a></li>     -->
						</ul>
						<!-- <h2 class="dropdown">
			              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $schoolname;?></a>


			              <ul class="dropdown-menu">
			                <li><a href="profile.php">Settings</a></li>
			                <li><a href="logout.php">Sign out</a></li>  
			              </ul>
			            </h2> -->
					</div>
				</div>
			 </div>
	</div>
</div>
<!-- header -->

<!-- Dashboard -->
	  
<div class="container-fluid"> 
	<div class="row">
		<div class="col-md-2 no-padd">
			<div id="body-row">
				<div id="sidebar-container" class="sidebar-expanded d-none d-md-block">
					<ul class="list-group">
							<li class="active" id="all">
								<a href="#submenu1" data-toggle="collapse" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
									<div class="d-flex w-100 justify-content-start align-items-center">
										<img src="images/key.png" style="opacity: .6;" alt="" /> &nbsp 
										<span name="classid" id="classid" onclick="changeClass('all');" class="menu-collapsed">Class </span>
										<span class="submenu-icon"></span>	
									</div>
								</a>
								<input type="hidden" name="class_id" id="class_id">
								<input type="hidden" name="subject_id" id="subject_id">						
							</li>
						  	<?php 
							$countclass='1';
							$classresult = $teacher->getclassResult();
							while($objclass = mysqli_fetch_object($classresult)){
								$clsID = ConverToRoman($objclass->id);											
								?> 
								<li class="active" id ="<?php echo $clsID; ?>">
								<a href="#submenu<?php echo $countclass; ?>" data-toggle="collapse" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
									<div class="d-flex w-100 justify-content-start align-items-center">
										<img src="images/key.png" style="opacity: .6;" alt="" /> &nbsp 
										<span name="classid" id="classid" onclick="changeClass('<?php echo $clsID; ?>');" class="menu-collapsed">Class <?php echo $objclass->class?></span>
										<span class="submenu-icon"></span>
									</div>
								</a>
								
								</li>
								<div id='submenu<?php echo $countclass; ?>' class="collapse sidebar-submenu">
									<!-- <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
										<span class="menu-collapsed">Division A</span>
									</a>
									<a href="#" class="list-group-item list-group-item-action bg-dark text-white">
										<span class="menu-collapsed">Division B</span>
									</a>
									<a href="#" class="list-group-item list-group-item-action bg-dark text-white">
										<span class="menu-collapsed">Division C</span>
									</a> -->
								</div>
							<?php  $countclass++;
							} 
							?>	
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-7 no-padd">
			<div class="tab-section" onload="mySubject()">
			<!-- 	<ul class="list-group list-scroll">
					<li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
						<img src="images/key.png" style="opacity: .6;" alt=""> &nbsp; CHAPTER
					</li>
				</ul> -->

				<ul class="nav nav-tabs" id="tab_subjects">
				   <!-- <span id="resultsubject"></span>  -->
					<?php 
					// $subjectresult = $teacher->getSubjectResult($board_id,$class_id);
					// while($objsubject = mysqli_fetch_object($subjectresult))
					// {
					// 	$li_id = ( $li_count == 0 ) ? 'id="firstli"' : '';
					// 	?>
					 	<!-- <li <?php echo $li_id; ?> >
					// 		<a name="subject_id" id="subject_id" onclick="changeSubject('<?php echo $objsubject->subjectID?>');" data-toggle="tab"><?php echo $objsubject->subject?></a>
					// 	</li> -->
					 	<?php 
					// 	$li_count++;
					// } 
					?>
				</ul>

				  <div class="tab-content">
						<div id="chartContainer" style="height: 370px; width: 100%;">
							<!-- <img src="images/dashboardnew3.png" class="img-responsive" alt="" /> -->
						</div>
						<!-- <div id="menu1" class="tab-pane fade">
						  <h3>Menu 1</h3>
						  <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
						</div>
						<div id="menu2" class="tab-pane fade">
						  <h3>Menu 2</h3>
						  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
						</div>
						<div id="menu3" class="tab-pane fade">
						  <h3>Menu 3</h3>
						  <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
						</div>
						<div id="menu4" class="tab-pane fade">
						  <h3>Menu 3</h3>
						  <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
						</div> -->
				  </div>  
			</div>
			<!-- table -->

<div class="table-responsive dash-table" id="userresult">	
	<?php	
	$classid =isset($_POST['classid'])?$_POST['classid']:'';

	if (isset($schoolname)){
	

$html=''; 
$html .= '<div class="" style="padding:10px;">    
    <div class="">';
	
	$html.= '<table id="example1" class="table table-bordered table-striped">';
	$html.= '<thead>
				<tr>
					<th>No.</th>
					<th>Name</th>
					<th>Class Rank</th>
					<th>Global Rank </th>
					<th>Time Spent</th> 
					<th>Overall Grade</th>
				</tr>
				
				<div class="box-tools">
							<a href="download-report.php?state_id='.$_POST['state_id'].'&city_id='.$city_id.'&schoolname='.$schoolname.'&classid=I&subject_id=1" class="btn btn-success button-loading pull-right" 
							id="btns" onclick="getExport()";>Export Record</a>
						</div>';
				
			
					$is = 1;
					$ids = $download->getuserResultIDss($schoolname,$state_id,$city_id);
					$user = $teacher->getusermainpageResult($schoolname,$state_id,$city_id);
					
					$html.='
							<input type="hidden" id="studentids" value="'.$ids.'">
							<input type="button" onclick="getPdf();"  id="btn" class="btn btn-success name="PDF" value="PDF" style="margin-left:452px;">
							<span id="userresults"></span>
						</thead>';
					
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
						$rank = 0;
						$overallresult = $teacher->getrankuserResult($userlist->grade,$schoolname);
						while($classrank = mysqli_fetch_object($overallresult)){
							if($classrank->userid==$userlist->userID){
								$rank = $i;
							}
							$i++;
						}
						if ($rank!=0){
							$html.= '
							
							<tr> 
								<td style="color:white;">'.$is.'</td>
								<td><span class="span_inline" style="color:white;"><img src="'.$img_grade.'" alt="icon" /> &nbsp &nbsp <a href="edufun.php?id='.$userlist->userID.'" target="_blank">'.ucfirst($userlist->name).'  </a></span></td>';
									$i=1;
									
								
									
									$html.= '<td class="dark"><span>'.$rank.'</span></td>';
									 '<td class="orange"><span>'.$rank.'</span></td>';
									 
									$i=1;				
									$globalranks = 0;				
									$globalresult = $teacher->getglobalrankResult($classid,$schoolname);	
									while($globalrank = mysqli_fetch_object($globalresult)){
									// echo '<pre>';
									// print_r($globalrank);
									// echo '</pre>';							
										if($globalrank->userid==$userlist->userID){						
											$globalranks = $i;					
										}					
											$i++;				
									}	
										
										$html.= '<td class="dark"><span>'.$globalranks.'</span></td>';
										
											$timeresult = $teacher->getrankTimestamp($userlist->userID);			    
											$Totaltime = mysqli_fetch_object($timeresult);
											
										$html.= '<td class="dark">
										<span>
											<ul class="time-inline">
												'.$Totaltime->time.'
											</ul>
										</span></td>';
										
									$html.= '<td class='.$coloravg.'><span>'.$showlearningavg.'</span></td>';

							$html.= '</tr>';
						}	
								$is++; 
							
					}
						}else{
							$html.= '<tr>';
							$html.= '<td>No user </td>';
							$html.= '<td colspan="8">No Record found</td>';
							$html.= '</tr>';
						}
						
				$html.= '
				</table>';
			$html.="</div>
</div>";
echo $html;
}	
?>
</div>
<!--end --> 
<div class="middle-bg"></div>
		</div>
		<div class="col-md-3 no-padd">
			<div id="body-row">
				<div id="sidebar-container" class="sidebar-expanded d-none d-md-block">
					<div id="chapter"></div>
				</div>
			</div>
		</div>
</div>
</body>
</html>

<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>	
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
<script>
/* ---+-----+---- Get user result list with class---+---+--*/ 
function changeClass(classid) {
	//alert(classid);
	$('#chapter').empty('');
    var subject_id = ( $('#subject_id').val() ) ? $('#subject_id').val() : '';
    var school_id = $('#school_id').val();
    var state_id = $('#state_id').val();
    var city_id = $('#city_id').val();
	$('li.active').removeClass('active');
	$('#'+classid).addClass("active");
 	var board_id = '<?php echo $board_id; ?>';

	$.ajax({
		type:"POST",
		cache:false,
		url:"action.php",
		data: "action=getClass&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id,
		success:function(response){
			$("#chapter").html(response);
			//$("chartContainer").html(response);
			console.log('response'+response);
		}
	});
	
	$.ajax({
		type:"POST",
		cache:false,
		url:"action.php",
		data: "action=getList&classid="+classid+"&subject_id="+subject_id+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id,
		beforeSend: function() {
			$('#userresult').html("<img src='images/uc.gif' height='80'>").show('fast');	
		},
		success:function(response){				
			$("#userresult").html(response);
			$("#class_id").val(classid);		
		}
	});
	
	//alert(classid+'===');		// return json_encode
	$.ajax({
		type:"POST",
		cache:false,
		url:"chart_details.php",
		data:"action=getChartDetails&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id,
		// contentType: "application/json",
		// dataType:"josn",
		success:function(chartData){
			
			console.log('chartData'+chartData);
			 
			var your_object = JSON.parse(chartData);
			// var json_text = JSON.stringify(your_object, null, 2);
			
			var A1 = ( your_object.A1 ) ? your_object.A1 : 0;
			var A2 = ( your_object.A2 ) ? your_object.A2 : 0;
			var B1 = ( your_object.B1 ) ? your_object.B1 : 0;
			var B2 = ( your_object.B2 ) ? your_object.B2 : 0;
			var C1 = ( your_object.C1 ) ? your_object.C1 : 0;
			var C2 = ( your_object.C2 ) ? your_object.C2 : 0;
			var D =	( your_object.D ) ? your_object.D : 0;
			var E1 = ( your_object.E1 ) ? your_object.E1 : 0;
			var E2 = ( your_object.E2 ) ? your_object.E2 : 0;
			
			var dynamicData = [];
			if ( A1 > 0 ) dynamicData.push({ label: "A1", "y": A1, color: "green" },);
			if ( A2 > 0 ) dynamicData.push({ label: "A2", "y": A2, color: "#46d246" },);
			if ( B1 > 0 ) dynamicData.push({ label: "B1", "y": B1, color: "#e6e600" },);
			if ( B2 > 0 ) dynamicData.push({ label: "B2", "y": B2, color: "yellow" },);
			if ( C1 > 0 ) dynamicData.push({ label: "C1", "y": C1, color: "#ff9900" },);
			if ( C2 > 0 ) dynamicData.push({ label: "C2", "y": C2, color: "#ffb84d" },);
			if ( D > 0 ) dynamicData.push({ label: "D", "y": D , color: "blue"},);
			if ( E1 > 0 ) dynamicData.push({ label: "E1", "y": E1, color: "#B22222" },);
			if ( E2 > 0 ) dynamicData.push({ label: "E2", "y": E2, color: "red" },);
			
			var dynamicData = [
				{ label: "A1 (90-100)", "y": A1, color: "green" },
				{ label: "A2 (80-90)", "y": A2, color: "#46d246" },
				{ label: "B1 (70-80)", "y": B1, color: "#e6e600" },
				{ label: "B2 (60-70)", "y": B2, color: "yellow" },
				{ label: "C1 (50-60)", "y": C1, color: "#ff9900" },
				{ label: "C2 (40-50)", "y": C2, color: "#ffb84d" },
				{ label: "D (30-40)", "y": D, color: "blue"},
				{ label: "E1 (20-30)", "y": E1, color: "#B22222" },
				{ label: "E2 (0-20)", "y": E2, color: "red" },
			]; 
			
			/* pie charts used  */
			var chart = new CanvasJS.Chart("chartContainer", {
				theme: "dark2", // "light1", "light2", "dark1", "dark2"
				exportEnabled: true,
				animationEnabled: true,				
				title: {
					text: "Performance Chart"					
				},				
				subtitles: [{
					text: ""
				}],
				data: [{
					type: "pie",
					//yValueFormatString: "#,##0.00\"%00.4\"",
					showInLegend: "true",
					legendText: "{label}",
					yValueFormatString: "#,##0\"-Total-count\"",
					indexLabel: "{label} ({y})",
					dataPoints: dynamicData
				}]			
			});				
			/* End pie charts used  */
			chart.render();
		}
	});

	// for class and board wise subject
	$.ajax({
		type:"POST",
		cache:false,
		url:"action.php",
		data: "action=get_board_class_wise_subjects&board_id="+board_id+"&class_id="+classid,
		success:function(response){
			$("#tab_subjects").html(response);
 			$('#'+subject_id).attr("class", "active");
		}
	});	
}

function changeSubject(subject_id){
		if (subject_id==undefined) {
			subject_id='';
		}
     	$('#chapter').empty('');
     	//console.log('subject_id' + subject_id);
		var classid=$('#class_id').val();
		var school_id = $('#school_id').val();
		var state_id = $('#state_id').val();
		var city_id = $('#city_id').val();
		
		$.ajax({
			type:"POST",
			cache:false,
			url:"action.php",
			data: "action=getSubject&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id,
			success:function(response){
				$("#chapter").html(response);
				//console.log('response'+ response);
			}
		});
	
	
		$.ajax({
			type:"POST",
			cache:false,
			url:"action.php",
			data: "action=getList&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id,
			beforeSend: function() {
				$('#userresult').html("<img src='images/uc.gif' height='80'>").show('fast');	
			},
			success:function(response){
				$("#userresult").html(response);
				$("#subject_id").val(subject_id);
			}
		});
		
		
		$.ajax({
			type:"POST",
			cache:false,
			url:"chart_details.php",
			data:"action=getChartDetails&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id,
			// contentType: "application/json",
			// dataType:"josn",
			success:function(chartData){
				
				var your_object = JSON.parse(chartData);
				// var json_text = JSON.stringify(your_object, null, 2);
				
				var A1 = ( your_object.A1 ) ? your_object.A1 : 0;
				var A2 = ( your_object.A2 ) ? your_object.A2 : 0;
				var B1 = ( your_object.B1 ) ? your_object.B1 : 0;
				var B2 = ( your_object.B2 ) ? your_object.B2 : 0;
				var C1 = ( your_object.C1 ) ? your_object.C1 : 0;
				var C2 = ( your_object.C2 ) ? your_object.C2 : 0;
				var D = ( your_object.D ) ? your_object.D : 0;
				var E1 = ( your_object.E1 ) ? your_object.E1 : 0;
				var E2 = ( your_object.E2 ) ? your_object.E2 : 0;
				
				var dynamicData = [];
				if ( A1 > 0 ) dynamicData.push({ label: "A1", "y": A1, color: "green" },);
				if ( A2 > 0 ) dynamicData.push({ label: "A2", "y": A2, color: "#46d246" },);
				if ( B1 > 0 ) dynamicData.push({ label: "B1", "y": B1, color: "#e6e600" },);
				if ( B2 > 0 ) dynamicData.push({ label: "B2", "y": B2, color: "yellow" },);
				if ( C1 > 0 ) dynamicData.push({ label: "C1", "y": C1, color: "#ff9900" },);
				if ( C2 > 0 ) dynamicData.push({ label: "C2", "y": C2, color: "#ffb84d" },);
				if ( D > 0 ) dynamicData.push({ label: "D", "y": D , color: "blue"},);
				if ( E1 > 0 ) dynamicData.push({ label: "E1", "y": E1, color: "#B22222" },);
				if ( E2 > 0 ) dynamicData.push({ label: "E2", "y": E2, color: "red" },);
				
				var dynamicData = [
					{ label: "A1 (90-100)", "y": A1, color: "green" },
					{ label: "A2 (80-90)", "y": A2, color: "#46d246" },
					{ label: "B1 (70-80)", "y": B1, color: "#e6e600" },
					{ label: "B2 (60-70)", "y": B2, color: "yellow" },
					{ label: "C1 (50-60)", "y": C1, color: "#ff9900" },
					{ label: "C2 (40-50)", "y": C2, color: "#ffb84d" },
					{ label: "D (30-40)", "y": D, color: "blue"},
					{ label: "E1 (20-30)", "y": E1, color: "#B22222" },
					{ label: "E2 (0-20)", "y": E2, color: "red" },
				]; 
				
				/* pie charts used  */
				var chart = new CanvasJS.Chart("chartContainer", {
				theme: "dark2", // "light1", "light2", "dark1", "dark2"
				exportEnabled: true,
				animationEnabled: true,				
				title: {
					text: "Performance Chart"					
				},				
				subtitles: [{
					text: ""
				}],
				data: [{
					type: "pie",
					//yValueFormatString: "#,##0.00\"%00.4\"",
					showInLegend: "true",
					legendText: "{label}",
					yValueFormatString: "#,##0\"-Total-count\"",
					indexLabel: "{label} ({y})",
					dataPoints: dynamicData
				}]			
			});		
					
			/* End pie charts used  */
			chart.render();
			}
		});
}


function changeChapter(id,chapter_name)
{
//alert(id);
	var class_id = $('#class_id').val();
    var subject_id = $("#subject_id").val();
    var school_id = $('#school_id').val();
    var state_id = $('#state_id').val();
    var city_id = $('#city_id').val();
	
   $('li').click(function (event) {
		$(this).addClass('active').siblings().removeClass('active');
		 $('.sidebar-submenu').collapse('hide');
	});
	
	$.ajax({
        type:"POST",
        cache:false,
        url:"studentresult.php",
        data: "action=getchapter&chapter_id="+id+"&school_id="+school_id+"&class_id="+class_id+"&subject_id="+subject_id+"&state_id="+state_id+"&city_id="+city_id,
        beforeSend: function() {
            $('#userresult').html("<img src='images/uc.gif' height='80'>").show('fast');    
        },
        success:function(response){
            $("#userresult").html(response);
            
        }
    });
	$.ajax({
		type:"POST",
		cache:false,
		url:"graphaction.php",
		data: "action=getchaptercounts&chapter_id="+id+"&school_id="+school_id+"&class_id="+class_id+"&subject_id="+subject_id+"&state_id="+state_id+"&city_id="+city_id,
		// dataType:"json",
		beforeSend: function() {
			$('#chartContainer').html("<img src='images/uc.gif' height='80'>").show('fast');	
		},
		success:function(response){
			//alert(response);
			var dataval = JSON.parse(response);
			var chart = new CanvasJS.Chart("chartContainer",
			{
			backgroundColor: "#ffffff",
			title:{
				text: chapter_name
			},
			axisY:{
				title:"No of Students"
			},
			toolTip: {
				shared: true,
				reversed: true
			},
			data: dataval
		 });
		
			chart.render();
		}
	});
}


function changeTopic(id){
	//$('#chapter').empty('');
     //console.log('subject_id' + subject_id);
	// alert('subtopic ID '+id);
	
	var classid=$('#class_id').val();
	var school_id = $('#school_id').val();
	var state_id = $('#state_id').val();
	var city_id = $('#city_id').val();
	var subject_id = $('#subject_id').val();
	//var subtopic = $('#subtopic').val();
		
		$.ajax({
			type:"POST",
			cache:false,
			url:"action.php",
			data: "action=getListsubtopic&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id+"&subtopic="+id,
			beforeSend: function() {
				$('#userresult').html("<img src='images/uc.gif' height='80'>").show('fast');
			},
			success:function(response){
				$("#userresult").html(response);
				//console.log('id'+ id);
			}
		});
		
		$.ajax({
			type:"POST",
			cache:false,
			url:"chart_details.php",
			data:"action=getChartDetails&&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id+"&subtopic="+id,
			// contentType: "application/json",
			// dataType:"josn",
			success:function(chartData){
				
				 //console.log('chartData'+chartData);
				 
				var your_object = JSON.parse(chartData);
				// var json_text = JSON.stringify(your_object, null, 2);
				
				var A1 = ( your_object.A1 ) ? your_object.A1 : 0;
				var A2 = ( your_object.A2 ) ? your_object.A2 : 0;
				var B1 = ( your_object.B1 ) ? your_object.B1 : 0;
				var B2 = ( your_object.B2 ) ? your_object.B2 : 0;
				var C1 = ( your_object.C1 ) ? your_object.C1 : 0;
				var C2 = ( your_object.C2 ) ? your_object.C2 : 0;
				var D = ( your_object.D ) ? your_object.D : 0;
				var E1 = ( your_object.E1 ) ? your_object.E1 : 0;
				//var E2 = ( your_object.E2 ) ? your_object.E2 : 0;
				
				var dynamicData = [];
				if ( A1 > 0 ) dynamicData.push({ label: "A1", "y": A1, color: "green" },);
				if ( A2 > 0 ) dynamicData.push({ label: "A2", "y": A2, color: "green" },);
				if ( B1 > 0 ) dynamicData.push({ label: "B1", "y": B1, color: "yellow" },);
				if ( B2 > 0 ) dynamicData.push({ label: "B2", "y": B2, color: "yellow" },);
				if ( C1 > 0 ) dynamicData.push({ label: "C1", "y": C1, color: "blue" },);
				if ( C2 > 0 ) dynamicData.push({ label: "C2", "y": C2, color: "blue" },);
				if ( D > 0 ) dynamicData.push({ label: "D", "y": D , color: "blue"},);
				if ( E1 > 0 ) dynamicData.push({ label: "E1", "y": E1, color: "red" },);
				//if ( E2 > 0 ) dynamicData.push({ label: "E2", "y": E2, color: "red" },);
				
				/* var dynamicData = [
								{ label: "A1", "y": A1, color: "green" },
								{ label: "A2", "y": A2, color: "green" },
								{ label: "B1", "y": B1, color: "yellow" },
								{ label: "B2", "y": B2, color: "yellow" },
								{ label: "C1", "y": C1, color: "blue" },
								{ label: "C2", "y": C2, color: "blue" },
								{ label: "D", "y": D , color: "blue"},
								{ label: "E1", "y": E1, color: "red" },
							]; */
				
				/* pie charts used  */
				var chart = new CanvasJS.Chart("chartContainer", {
					animationEnabled: true,			
					title: {
						text: ""						
					},					
					subtitles: [{
						text: ""
					}],
					data: [{
						type: "pie",
						//yValueFormatString: "#,##0.00\"%00.4\"",
						yValueFormatString: "#,##0\"-Total-count\"",
						indexLabel: "{label} ({y})",
						dataPoints: dynamicData
					}]				
				});
					
				/* End pie charts used  */
				chart.render();
			}
		});
	
}

function myFunction() {
	//alert("Page is loaded");
	var classid=$('#class_id').val();
	var school_id = $('#school_id').val();
	var state_id = $('#state_id').val();
	var city_id = $('#city_id').val();
	var board_id = '<?php echo $board_id; ?>';

	$.ajax({
		type:"POST",
		cache:false,
		url:"action.php",
		data: "action=getSubjects&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id,
		success:function(response){
			$("#chapter").html(response);
		}
	});	

	$.ajax({
		type:"POST",
		cache:false,
		url:"action.php",
		data: "action=get_board_class_wise_subjects&board_id="+board_id+"&class_id="+classid,
		success:function(response){
			$("#tab_subjects").html(response);
		}
	});	

  	//$('#all').addClass('active');
  	changeClass('all');
}


// function getPdf()
// {
// 	var studentids = $('#studentids').val();
// 	//$('#btn').disabled = true;
// 	//alert(studentids);
// 	$.ajax({
// 		type:"POST",
// 		cache:false,
// 		url:"dashboard.php",
// 		data: "action=getuserDetails&studentids="+studentids,
// 		beforeSend: function() {
// 			$('#userresults').html("<img src='images/loads.gif' height='150'>").show('fast');	
// 			$("#btn").prop('disabled', true);
// 		},
// 		complete: function() {
// 			$('#userresults').html("<img src='images/loads.gif' height='150'>").hide();
// 			$("#btn").prop('disabled', false)
// 		},
// 		success:function(response){
// 			window.open('dashboard.php?action=getuserDetails&studentids='+ studentids,'_blank');
// 			//alert(response);
// 			//$("#muhtml").html(response);
// 			//console.log('response'+response);
// 			// var w = window.open('about:blank');
// 			// w.document.open();
// 			// w.document.write(response);
// 			// w.document.close();
// 		}
// 	});
// }

// function getPdf()
// {
// var studentids = $('#studentids').val();

// //window.open('dashboard.php?action=getuserDetails&studentids='+ studentids,'_blank');

// //alert(studentids);
//  $.ajax({
// 	type:"POST",
// 	cache:false,
// 	url:"user_details.php",
// 	data: "action=getuserDetails&studentids="+studentids,
// 	beforeSend: function() {
// 		$('#userresults').html("<img src='images/loads.gif' height='150' width='300'>").show('fast');
// 			$('#btn').attr("disabled", true);
// 	},
	
// 	complete: function() {
// 		$('#userresults').html("<img src='images/loads.gif'>").hide();
// 		    $('#btn').attr("disabled", false);
// 	},
// 	success:function(response){
// 		var popUp = window.open('user_details.php?action=getuserDetails&studentids='+ studentids,'_blank');
// 		if (popUp == null || typeof(popUp)=='undefined') { 	
// 	alert('Please enable your pop-up blocker and click the "Open" link again.'); 
// 	} else { 	
// 		popUp.focus();
// 	}
// 		//alert(response);
// 		//$("#muhtml").html(response);
// 		//console.log('response'+response);
// 		// var w = window.open('about:blank');
// 		// w.document.open();
// 		// w.document.write(response);
// 		// w.document.close();
// 	}
// }); 
// } 

// $(document).ready(function () {
// 	$("#firstli").attr("class","active");
// });
$(document).ready(function(){
  $(".dropdown-toggle").dropdown();
});
</script>