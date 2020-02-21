<?php
session_start();
if(empty($_SESSION['uids'])){
	header("location:index.php");
}
error_reporting(0);
include('db_config.php');
include('model/Teacher.class.php');
include('model/Download.class.php');
include('functions.php');
global $conn;

$teacher = new Teacher();
$download = new Download();


if (isset($_SESSION['schoolname']) && ($_SESSION['schoolname']!='')){
		$schoolname =$_SESSION['schoolname'];
		$schoolarray = str_replace("|", "','",  $schoolname );
} else {
		echo "<script>window.open('logout.php','_self')</script>";
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
<?php include('header.php');?>

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
							foreach ($classstdarray as $classstd){
							//while($objclass = mysqli_fetch_object($classresult)){
								$clsID = ConverToRoman($classstd);	
						?> 
						<li class="active" id ="<?php echo $clsID; ?>">
							<a href="#submenu<?php echo $countclass; ?>" data-toggle="collapse" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
							<div class="d-flex w-100 justify-content-start align-items-center">
								<img src="images/key.png" style="opacity: .6;" alt="" /> &nbsp 
								<span name="classid" id="classid" onclick="changeClass('<?php echo $clsID; ?>');" class="menu-collapsed">Class <?php echo $classstd?></span>
								<span class="submenu-icon"></span>
							</div>
							</a>
						</li>
						<div id='submenu<?php echo $countclass; ?>' class="collapse sidebar-submenu"></div>
						<?php  $countclass++;
						} 
						?>	
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-7 no-padd">
			<div class="tab-section" onload="mySubject()">
				<ul class="nav nav-tabs" id="tab_subjects">
				</ul>
				<div class="tab-content">
					<div id="chartContainer" style="height: 370px; width: 100%;overflow: auto; overflow-y: hidden;">
						<!-- <img src="images/dashboardnew3.png" class="img-responsive" alt="" /> -->
					</div>
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
					$html.= '<thead><tr>
						<th>No.</th>
						<th>Nameq</th>
						<th>Class Rank</th>
						<th>Global Rank </th>
						<th>Time Spent</th> 
						<th>Overall Grade</th>
					</tr>
					<div class="box-tools">
							<a href="download-report.php?state_id='.$_POST['state_id'].'&city_id='.$city_id.'&classid='.$classid.'&subject_id=1" class="btn btn-success button-loading pull-right" 
							id="btns" onclick="getExport()";>Export Record1</a>
						</div>';
				
			
					$is = 1;
					$ids = $download->getuserResultIDss($schoolarray,$state_id,$city_id);
					$user = $teacher->getusermainpageResult($schoolarray,$state_id,$city_id);
				
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
							$subtopicid='';
							$overallgrade = $teacher->getoverallGrade($userlist->userID,$subtopicid);	
							$allgrade = mysqli_fetch_object($overallgrade);	
							$learnincurrancy =$allgrade->currency;
								if (mysqli_num_rows($overallgrade)>0) {
									if (($allgrade->currency=="") || ($allgrade->currency=="0")) {
										$learnincurrancy =0;
										$showlearningavg ="E2";
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
							$overallresult = $teacher->getrankuserResult(Romannumeraltonumber($userlist->grade),$schoolarray);
							while($classrank = mysqli_fetch_object($overallresult)){
								if($classrank->userid==$userlist->userID){
									$rank = $i;
								}
								$i++;
							}
							
							if (!empty($rank)){
								$html.= '<tr> 
								<td style="color:white;">'.$is.'</td>
								<td><span class="span_inline" style="color:white;"><img src="'.$img_grade.'" alt="icon" /> &nbsp &nbsp <a href="edufun.php?id='.$userlist->userID.'" target="_blank">'.ucfirst($userlist->name).'  </a></span></td>';
								$i=1;
								$html.= '<td class="dark"><span>'.$rank.'</span></td>';
									 '<td class="orange"><span>'.$rank.'</span></td>';
									 
								$i=1;				
								$globalranks = 0;				
								/*$globalresult = $teacher->getglobalrankResult($classid,$schoolarray);	
								while($globalrank = mysqli_fetch_object($globalresult)){
									if($globalrank->userid==$userlist->userID){						
										$globalranks = $i;					
									}					
									$i++;				
								}*/
								
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
					$html.= '</table>';
					$html.="</div></div>";
					//echo $html;
					
				}	
			?>
			</div>
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
		
	$.ajax({
		type:"POST",
		cache:false,
		url:"action.php",
		data: "action=getSubject&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id+"&board_id="+board_id,
		success:function(response){
			$("#chapter").html(response);
			//console.log('response'+ response);
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
			
			//console.log('chartData'+chartData);
			 
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
			var sumall = A1+A2+B1+B2+C1+C2+D+E1+E2;
		
			var A1per =  ((A1*100)/sumall).toFixed(0);
			var A2per =  ((A2*100)/sumall).toFixed(0);
			var B1per =  ((B1*100)/sumall).toFixed(0);
			var B2per =  ((B2*100)/sumall).toFixed(0);
			var C1per =  ((C1*100)/sumall).toFixed(0);
			var C2per =  ((C2*100)/sumall).toFixed(0);
			var Dper =  ((D*100)/sumall).toFixed(0);
			var E1per =  ((E1*100)/sumall).toFixed(0);
			var E2per =  ((E2*100)/sumall).toFixed(0);
			var dynamicData = [];
			if ( A1 > 0 ) dynamicData.push({ label: "A1 (90-100)("+A1per+"%)", "y": A1, color: "green", bottomlabel: "A1 (90-100)" },);
			if ( A2 > 0 ) dynamicData.push({ label: "A2 (80-90)("+A2per+"%)", "y": A2, color: "#46d246",bottomlabel: "A2 (80-90)" },);
			if ( B1 > 0 ) dynamicData.push({ label: "B1 (70-80)("+B1per+"%)", "y": B1, color: "#e6e600", bottomlabel: "B1 (70-80)" },);
			if ( B2 > 0 ) dynamicData.push({ label: "B2 (60-70)("+B2per+"%)", "y": B2, color: "yellow",bottomlabel: "B2 (60-70)" },);
			if ( C1 > 0 ) dynamicData.push({ label: "C1 (50-60)("+C1per+"%)", "y": C1, color: "#ff9900", bottomlabel: "C1 (50-60)" },);
			if ( C2 > 0 ) dynamicData.push({  label: "C2 (40-50)("+C2per+"%)", "y": C2, color: "#ffb84d", bottomlabel: "C2 (40-50)"  },);
			if ( D > 0 ) dynamicData.push({ label: "D (30-40)("+Dper+"%)", "y": D, color: "blue", bottomlabel: "D (30-40)"},);
			if ( E1 > 0 ) dynamicData.push({ label: "E1 (20-30)("+E1per+"%)", "y": E1, color: "#B22222",bottomlabel: "E1 (20-30)"  },);
			if ( E2 > 0 ) dynamicData.push({  label: "E2 (0-20)("+E2per+"%)", "y": E2, color: "red", bottomlabel: "E2 (0-20)"  },);
			
			/*var dynamicData = [
				 { "y": A1, color: "green", bottomlabel: "A1 (90-100)" },
				 { "y": A2, color: "#46d246",bottomlabel: "A2 (80-90)" },
				 { "y": B1, color: "#e6e600", bottomlabel: "B1 (70-80)" },
				 { "y": B2, color: "yellow",bottomlabel: "B2 (60-70)" },
				 { "y": C1, color: "#ff9900", bottomlabel: "C1 (50-60)" },
				 { "y": C2, color: "#ffb84d", bottomlabel: "C2 (40-50)" },
				 { "y": D, color: "blue", bottomlabel: "D (30-40)"},
				 { "y": E1, color: "#B22222",bottomlabel: "E1 (20-30)" },
				 { "y": E2, color: "red", bottomlabel: "E2 (0-20)" },
			]; 
			*/
			/* pie charts used  */
			var chart = new CanvasJS.Chart("chartContainer", {
				theme: "dark2", // "light1", "light2", "dark1", "dark2"
				exportEnabled: true,
				animationEnabled: true,				
				title: {
					text: "Performance Chart",
				},				
				subtitles:[
				  {
					text: sumall,
					verticalAlign: "center",
					dockInsidePlotArea: false ,
					fontSize: 30,
					color:"#fff"
				  },
				 
				  {
					text: "Students",
					padding: {
					 top: 50,
					 right: 1,
					 bottom: 0,
					 left: 2
					},
					verticalAlign: "center",
					dockInsidePlotArea: false ,
					fontSize: 20,
					color:"#fff"
				  }],
								
				
				data: [{
					//type: "pie",
					type: "doughnut",
					innerRadius: 50,
					//yValueFormatString: "#,##0.00\"%00.4\"",
					showInLegend: "true",
					legendText: "{bottomlabel}",
					yValueFormatString: "",
					//indexLabel: "{label} ({y})",
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
	var board_id = '<?php echo $board_id; ?>';
		
	$.ajax({
		type:"POST",
		cache:false,
		url:"action.php",
		data: "action=getSubject&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id+"&board_id="+board_id,
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
			
			//console.log('chartData'+chartData);
			 
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
			var sumall = A1+A2+B1+B2+C1+C2+D+E1+E2;
		
			var A1per =  ((A1*100)/sumall).toFixed(0);
			var A2per =  ((A2*100)/sumall).toFixed(0);
			var B1per =  ((B1*100)/sumall).toFixed(0);
			var B2per =  ((B2*100)/sumall).toFixed(0);
			var C1per =  ((C1*100)/sumall).toFixed(0);
			var C2per =  ((C2*100)/sumall).toFixed(0);
			var Dper =  ((D*100)/sumall).toFixed(0);
			var E1per =  ((E1*100)/sumall).toFixed(0);
			var E2per =  ((E2*100)/sumall).toFixed(0);
			var dynamicData = [];
			if ( A1 > 0 ) dynamicData.push({ label: "A1 (90-100)("+A1per+"%)", "y": A1, color: "green", bottomlabel: "A1 (90-100)" },);
			if ( A2 > 0 ) dynamicData.push({ label: "A2 (80-90)("+A2per+"%)", "y": A2, color: "#46d246",bottomlabel: "A2 (80-90)" },);
			if ( B1 > 0 ) dynamicData.push({ label: "B1 (70-80)("+B1per+"%)", "y": B1, color: "#e6e600", bottomlabel: "B1 (70-80)" },);
			if ( B2 > 0 ) dynamicData.push({ label: "B2 (60-70)("+B2per+"%)", "y": B2, color: "yellow",bottomlabel: "B2 (60-70)" },);
			if ( C1 > 0 ) dynamicData.push({ label: "C1 (50-60)("+C1per+"%)", "y": C1, color: "#ff9900", bottomlabel: "C1 (50-60)" },);
			if ( C2 > 0 ) dynamicData.push({  label: "C2 (40-50)("+C2per+"%)", "y": C2, color: "#ffb84d", bottomlabel: "C2 (40-50)"  },);
			if ( D > 0 ) dynamicData.push({ label: "D (30-40)("+Dper+"%)", "y": D, color: "blue", bottomlabel: "D (30-40)"},);
			if ( E1 > 0 ) dynamicData.push({ label: "E1 (20-30)("+E1per+"%)", "y": E1, color: "#B22222",bottomlabel: "E1 (20-30)"  },);
			if ( E2 > 0 ) dynamicData.push({  label: "E2 (0-20)("+E2per+"%)", "y": E2, color: "red", bottomlabel: "E2 (0-20)"  },);
			
			/*var dynamicData = [
				 { "y": A1, color: "green", bottomlabel: "A1 (90-100)" },
				 { "y": A2, color: "#46d246",bottomlabel: "A2 (80-90)" },
				 { "y": B1, color: "#e6e600", bottomlabel: "B1 (70-80)" },
				 { "y": B2, color: "yellow",bottomlabel: "B2 (60-70)" },
				 { "y": C1, color: "#ff9900", bottomlabel: "C1 (50-60)" },
				 { "y": C2, color: "#ffb84d", bottomlabel: "C2 (40-50)" },
				 { "y": D, color: "blue", bottomlabel: "D (30-40)"},
				 { "y": E1, color: "#B22222",bottomlabel: "E1 (20-30)" },
				 { "y": E2, color: "red", bottomlabel: "E2 (0-20)" },
			]; 
			*/
			/* pie charts used  */
			var chart = new CanvasJS.Chart("chartContainer", {
				theme: "dark2", // "light1", "light2", "dark1", "dark2"
				exportEnabled: true,
				animationEnabled: true,				
				title: {
					text: "Performance Chart",
				},				
				subtitles:[
				  {
					text: sumall,
					verticalAlign: "center",
					dockInsidePlotArea: false ,
					fontSize: 30,
					color:"#fff"
				  },
				 
				  {
					text: "Students",
					padding: {
					 top: 50,
					 right: 1,
					 bottom: 0,
					 left: 2
					},
					verticalAlign: "center",
					dockInsidePlotArea: false ,
					fontSize: 20,
					color:"#fff"
				  }],
								
				
				data: [{
					//type: "pie",
					type: "doughnut",
					innerRadius: 50,
					//yValueFormatString: "#,##0.00\"%00.4\"",
					showInLegend: "true",
					legendText: "{bottomlabel}",
					yValueFormatString: "",
					//indexLabel: "{label} ({y})",
					dataPoints: dynamicData
				}]			
			});				
			/* End pie charts used  */
			chart.render();
		}
	});
}


function changeChapter(id,chapter_name){
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
			if (response==0){
				$('#chartContainer').html("No Record found").show('fast');	
			} else {	
				var dataval = JSON.parse(response);
				var chart = new CanvasJS.Chart("chartContainer",
				{
				backgroundColor: "#ffffff",
				title:{
					text: chapter_name
				},
				axisY:{
					title:"No of Students",
				},
				 axisX:{
				  labelAutoFit: false,  
				   labelMaxWidth: 100,  
				   labelWrap: false,
				   labelAngle: 20,
				 },
				toolTip: {
					shared: true,
					reversed: false,
					contentFormatter: function (e) {
					var content = " ";
					for (var i = 0; i < e.entries.length; i++) {
						if (e.entries[i].dataPoint.y!=0){
						content += e.entries[i].dataSeries.name + " " + "<strong>" + e.entries[i].dataPoint.y + "</strong>";
						content += "<br/>";
						}
					}
					return content;
				}
					
					
				},
				data: dataval
			 });
			
				chart.render();
			}
		}	
	});
}


function changeTopic(id){
	//$('#chapter').empty('');
     //console.log('subject_id' + subject_id);
	//alert('subtopic ID '+id);
	
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
			data:"action=getChartDetails&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id+"&subtopic="+id,
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
			var D =	( your_object.D ) ? your_object.D : 0;
			var E1 = ( your_object.E1 ) ? your_object.E1 : 0;
			var E2 = ( your_object.E2 ) ? your_object.E2 : 0;
			var sumall = A1+A2+B1+B2+C1+C2+D+E1+E2;
		
			var A1per =  ((A1*100)/sumall).toFixed(0);
			var A2per =  ((A2*100)/sumall).toFixed(0);
			var B1per =  ((B1*100)/sumall).toFixed(0);
			var B2per =  ((B2*100)/sumall).toFixed(0);
			var C1per =  ((C1*100)/sumall).toFixed(0);
			var C2per =  ((C2*100)/sumall).toFixed(0);
			var Dper =  ((D*100)/sumall).toFixed(0);
			var E1per =  ((E1*100)/sumall).toFixed(0);
			var E2per =  ((E2*100)/sumall).toFixed(0);
			var dynamicData = [];
			if ( A1 > 0 ) dynamicData.push({ label: "A1 (90-100)("+A1per+"%)", "y": A1, color: "green", bottomlabel: "A1 (90-100)" },);
			if ( A2 > 0 ) dynamicData.push({ label: "A2 (80-90)("+A2per+"%)", "y": A2, color: "#46d246",bottomlabel: "A2 (80-90)" },);
			if ( B1 > 0 ) dynamicData.push({ label: "B1 (70-80)("+B1per+"%)", "y": B1, color: "#e6e600", bottomlabel: "B1 (70-80)" },);
			if ( B2 > 0 ) dynamicData.push({ label: "B2 (60-70)("+B2per+"%)", "y": B2, color: "yellow",bottomlabel: "B2 (60-70)" },);
			if ( C1 > 0 ) dynamicData.push({ label: "C1 (50-60)("+C1per+"%)", "y": C1, color: "#ff9900", bottomlabel: "C1 (50-60)" },);
			if ( C2 > 0 ) dynamicData.push({  label: "C2 (40-50)("+C2per+"%)", "y": C2, color: "#ffb84d", bottomlabel: "C2 (40-50)"  },);
			if ( D > 0 ) dynamicData.push({ label: "D (30-40)("+Dper+"%)", "y": D, color: "blue", bottomlabel: "D (30-40)"},);
			if ( E1 > 0 ) dynamicData.push({ label: "E1 (20-30)("+E1per+"%)", "y": E1, color: "#B22222",bottomlabel: "E1 (20-30)"  },);
			if ( E2 > 0 ) dynamicData.push({  label: "E2 (0-20)("+E2per+"%)", "y": E2, color: "red", bottomlabel: "E2 (0-20)"  },);
			
			/*var dynamicData = [
				 { "y": A1, color: "green", bottomlabel: "A1 (90-100)" },
				 { "y": A2, color: "#46d246",bottomlabel: "A2 (80-90)" },
				 { "y": B1, color: "#e6e600", bottomlabel: "B1 (70-80)" },
				 { "y": B2, color: "yellow",bottomlabel: "B2 (60-70)" },
				 { "y": C1, color: "#ff9900", bottomlabel: "C1 (50-60)" },
				 { "y": C2, color: "#ffb84d", bottomlabel: "C2 (40-50)" },
				 { "y": D, color: "blue", bottomlabel: "D (30-40)"},
				 { "y": E1, color: "#B22222",bottomlabel: "E1 (20-30)" },
				 { "y": E2, color: "red", bottomlabel: "E2 (0-20)" },
			]; 
			*/
			/* pie charts used  */
			var chart = new CanvasJS.Chart("chartContainer", {
				theme: "dark2", // "light1", "light2", "dark1", "dark2"
				exportEnabled: true,
				animationEnabled: true,				
				title: {
					text: "Performance Chart",
				},				
				subtitles:[
				  {
					text: sumall,
					verticalAlign: "center",
					dockInsidePlotArea: false ,
					fontSize: 30,
					color:"#fff"
				  },
				 
				  {
					text: "Students",
					padding: {
					 top: 50,
					 right: 1,
					 bottom: 0,
					 left: 2
					},
					verticalAlign: "center",
					dockInsidePlotArea: false ,
					fontSize: 20,
					color:"#fff"
				  }],
								
				
				data: [{
					//type: "pie",
					type: "doughnut",
					innerRadius: 50,
					//yValueFormatString: "#,##0.00\"%00.4\"",
					showInLegend: "true",
					legendText: "{bottomlabel}",
					yValueFormatString: "",
					//indexLabel: "{label} ({y})",
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
		data: "action=getSubject&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id+"&board_id="+board_id,
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

function getdownload(){
	var downloadtype = $('#downloadtype').val();
	var state_id = $('#state_id').val();
	var city_id = $('#city_id').val();
	var schoolname = $('#school_id').val();
	var classid=$('#class_id').val(); 
    var subject_id = $("#subject_id").val();
    var board_id = '<?php echo $board_id; ?>';
	var data= "downloadtype="+downloadtype+"&state_id="+state_id+"&city_id="+city_id+"&schoolname="+schoolname+"&classid="+classid+"&subject_id="+subject_id+"&board_id="+board_id;
	document.location.href = 'download-report.php?'+data;
}	

$(document).ready(function(){
  $(".dropdown-toggle").dropdown();
});
</script>