<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

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
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; die('end of code');

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
				<div class="row">
					<div id="export_section" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<span id="userresults"></span>
						<select name="downloadtype" id="downloadtype" class="form-control input-sm">
							<option value="csv">CSV</option>
							<option value="pdf">PDF</option>
							<option value="xls">XLS</option>
						</select>
						<input type="button" onclick="getdownload();" id="btn" class="btn btn-success name=" pdf"="" value="Export" style="margin-left:20px;">
						<div class="dt-buttons btn-group" style="display: none; margin-right: 10px;"><button class="btn btn-default buttons-pdf buttons-html5" tabindex="0" aria-controls="example1" type="button"><span>PDF</span></button> </div>
					</div>
				</div>
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr role="row">
							<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Sr.No.: activate to sort column descending" style="width: 39px;">Sr.No.</th>
							<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" style="width: 240px;">Name</th>
							<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Class: activate to sort column ascending" style="width: 37px;">Class</th>
							<!-- <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="EMRS Rank: activate to sort column ascending" style="width: 131px;">EMRS Rank</th> -->
							<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Time Spent: activate to sort column ascending" style="width: 166px;">Time Spent</th>
							<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Overall Grade: activate to sort column ascending" style="width: 98px;">Rank</th></tr>
					</thead>
				</table>
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
    //$('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });

    var school_id = 1;
 	var board_id = 7;

    $('#example1').DataTable({
        "searching": false,
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ajax":{
        "url": 'getStudentDataList.php',
        "dataType": "json",
        "type": "POST",
        "data":{'school_id': school_id,'board_id':board_id}
    },
    "columns": [
        { "data": "id" },
        { "data": "fullname" },
        { "data": "grade" },
        { "data": "time_spend" },
        { "data": "rank" },
    ]

    });
  });
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

	// $.ajax({
	// 	type:"POST",
	// 	cache:false,
	// 	url:"action.php",
	// 	data: "action=getClass&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id,
	// 	success:function(response){
	// 		$("#chapter").html(response);
	// 		//$("chartContainer").html(response);
	// 		console.log('response'+response);
	// 	}
	// });

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
	school_id = 1;
	board_id = 7;
	var url="https://0e3r24lsu5.execute-api.ap-south-1.amazonaws.com/Prod/dummyapi?schoolId="+school_id+"&boardId="+board_id;
	$.ajax({
		type:"POST",
		cache:false,
		url:"getApiCallResponse.php",
		data: {url:url},
		dataType: "JSON",
		success:function(response){
			console.log(response);
			var total_count = response.total_count;
			var Overall_score = response.Overall_score;
			console.log('Overall_score:'+Overall_score);

			var color = {'A1':'green','A2':'#46d246','B1':'#e6e600','B2':'yellow','C1':'#ff9900','C2':'#ffb84d','D':'blue','E1':'#B22222','E2':'red'};
			var dynamicData = [];
			jQuery.each( Overall_score, function( i, val ) {
			  	console.log('i: '+i);
			  	console.log('val: '+val.Percent);
			  	var percent = val.Percent;
			  	var student_count = val.StudentCount;
			  	var grade = val.grade;
			  	var student_percentage = parseInt((student_count/total_count) * 100)+'%'; 
			  	dynamicData.push({ label: grade+" "+percent+"("+student_percentage+"%)", "y": student_count, color: color[grade], bottomlabel: grade+" "+"("+percent+")" });
			});
			console.log(dynamicData);

			// var dynamicData = [
			// 	 { "y": 'A1', color: "green", bottomlabel: "A1 (90-100)" },
			// 	 { "y": 'A2', color: "#46d246",bottomlabel: "A2 (80-90)" },
			// 	 { "y": 'B1', color: "#e6e600", bottomlabel: "B1 (70-80)" },
			// 	 { "y": 'B2', color: "yellow",bottomlabel: "B2 (60-70)" },
			// 	 { "y": 'C1', color: "#ff9900", bottomlabel: "C1 (50-60)" },
			// 	 { "y": 'C2', color: "#ffb84d", bottomlabel: "C2 (40-50)" },
			// 	 { "y": 'D', color: "blue", bottomlabel: "D (30-40)"},
			// 	 { "y": 'E1', color: "#B22222",bottomlabel: "E1 (20-30)" },
			// 	 { "y": 'E2', color: "red", bottomlabel: "E2 (0-20)" },
			// ]; 
			var chart = new CanvasJS.Chart("chartContainer", {
				theme: "dark2", // "light1", "light2", "dark1", "dark2"
				exportEnabled: true,
				animationEnabled: true,				
				title: {
					text: "Performance Chart",
				},				
				subtitles:[
				  {
					text: total_count,
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
	//alert(classid+'===');		// return json_encode

	// $.ajax({
	// 	type:"POST",
	// 	cache:false,
	// 	url:"chart_details.php",
	// 	data:"action=getChartDetails&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id,
	// 	// contentType: "application/json",
	// 	// dataType:"josn",
	// 	success:function(chartData){
			
	// 		//console.log('chartData'+chartData);
			 
	// 		var your_object = JSON.parse(chartData);
	// 		// var json_text = JSON.stringify(your_object, null, 2);
			
	// 		var A1 = ( your_object.A1 ) ? your_object.A1 : 0;
	// 		var A2 = ( your_object.A2 ) ? your_object.A2 : 0;
	// 		var B1 = ( your_object.B1 ) ? your_object.B1 : 0;
	// 		var B2 = ( your_object.B2 ) ? your_object.B2 : 0;
	// 		var C1 = ( your_object.C1 ) ? your_object.C1 : 0;
	// 		var C2 = ( your_object.C2 ) ? your_object.C2 : 0;
	// 		var D =	( your_object.D ) ? your_object.D : 0;
	// 		var E1 = ( your_object.E1 ) ? your_object.E1 : 0;
	// 		var E2 = ( your_object.E2 ) ? your_object.E2 : 0;
	// 		var sumall = A1+A2+B1+B2+C1+C2+D+E1+E2;
		
	// 		var A1per =  ((A1*100)/sumall).toFixed(0);
	// 		var A2per =  ((A2*100)/sumall).toFixed(0);
	// 		var B1per =  ((B1*100)/sumall).toFixed(0);
	// 		var B2per =  ((B2*100)/sumall).toFixed(0);
	// 		var C1per =  ((C1*100)/sumall).toFixed(0);
	// 		var C2per =  ((C2*100)/sumall).toFixed(0);
	// 		var Dper =  ((D*100)/sumall).toFixed(0);
	// 		var E1per =  ((E1*100)/sumall).toFixed(0);
	// 		var E2per =  ((E2*100)/sumall).toFixed(0);
	// 		var dynamicData = [];
	// 		if ( A1 > 0 ) dynamicData.push({ label: "A1 (90%-100%)("+A1per+"%)", "y": A1, color: "green", bottomlabel: "A1 (90%-100%)" },);
	// 		if ( A2 > 0 ) dynamicData.push({ label: "A2 (80%-90%)("+A2per+"%)", "y": A2, color: "#46d246",bottomlabel: "A2 (80%-90%)" },);
	// 		if ( B1 > 0 ) dynamicData.push({ label: "B1 (70%-80%)("+B1per+"%)", "y": B1, color: "#e6e600", bottomlabel: "B1 (70%-80%)" },);
	// 		if ( B2 > 0 ) dynamicData.push({ label: "B2 (60%-70%)("+B2per+"%)", "y": B2, color: "yellow",bottomlabel: "B2 (60%-70%)" },);
	// 		if ( C1 > 0 ) dynamicData.push({ label: "C1 (50%-60%)("+C1per+"%)", "y": C1, color: "#ff9900", bottomlabel: "C1 (50%-60%)" },);
	// 		if ( C2 > 0 ) dynamicData.push({  label: "C2 (40%-50%)("+C2per+"%)", "y": C2, color: "#ffb84d", bottomlabel: "C2 (40%-50%)"  },);
	// 		if ( D > 0 ) dynamicData.push({ label: "D (30%-40%)("+Dper+"%)", "y": D, color: "blue", bottomlabel: "D (30%-40%)"},);
	// 		if ( E1 > 0 ) dynamicData.push({ label: "E1 (20%-30%)("+E1per+"%)", "y": E1, color: "#B22222",bottomlabel: "E1 (20%-30%)"  },);
	// 		if ( E2 > 0 ) dynamicData.push({  label: "E2 (0%-20%)("+E2per+"%)", "y": E2, color: "red", bottomlabel: "E2 (0%-20%)"  },);
			
	// 		/*var dynamicData = [
	// 			 { "y": A1, color: "green", bottomlabel: "A1 (90-100)" },
	// 			 { "y": A2, color: "#46d246",bottomlabel: "A2 (80-90)" },
	// 			 { "y": B1, color: "#e6e600", bottomlabel: "B1 (70-80)" },
	// 			 { "y": B2, color: "yellow",bottomlabel: "B2 (60-70)" },
	// 			 { "y": C1, color: "#ff9900", bottomlabel: "C1 (50-60)" },
	// 			 { "y": C2, color: "#ffb84d", bottomlabel: "C2 (40-50)" },
	// 			 { "y": D, color: "blue", bottomlabel: "D (30-40)"},
	// 			 { "y": E1, color: "#B22222",bottomlabel: "E1 (20-30)" },
	// 			 { "y": E2, color: "red", bottomlabel: "E2 (0-20)" },
	// 		]; 
	// 		*/
	// 		/* pie charts used  */
	// 		console.log(dynamicData);
	// 		var chart = new CanvasJS.Chart("chartContainer", {
	// 			theme: "dark2", // "light1", "light2", "dark1", "dark2"
	// 			exportEnabled: true,
	// 			animationEnabled: true,				
	// 			title: {
	// 				text: "Performance Chart",
	// 			},				
	// 			subtitles:[
	// 			  {
	// 				text: sumall,
	// 				verticalAlign: "center",
	// 				dockInsidePlotArea: false ,
	// 				fontSize: 30,
	// 				color:"#fff"
	// 			  },
				 
	// 			  {
	// 				text: "Students",
	// 				padding: {
	// 				 top: 50,
	// 				 right: 1,
	// 				 bottom: 0,
	// 				 left: 2
	// 				},
	// 				verticalAlign: "center",
	// 				dockInsidePlotArea: false ,
	// 				fontSize: 20,
	// 				color:"#fff"
	// 			  }],
								
				
	// 			data: [{
	// 				//type: "pie",
	// 				type: "doughnut",
	// 				innerRadius: 50,
	// 				//yValueFormatString: "#,##0.00\"%00.4\"",
	// 				showInLegend: "true",
	// 				legendText: "{bottomlabel}",
	// 				yValueFormatString: "",
	// 				//indexLabel: "{label} ({y})",
	// 				dataPoints: dynamicData
	// 			}]			
	// 		});				
	// 		/* End pie charts used  */
	// 		chart.render();
	// 	}
	// });

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
	
	// $.ajax({
	// 	type:"POST",
	// 	cache:false,
	// 	url:"action.php",
	// 	data: "action=getList&classid="+classid+"&subject_id="+subject_id+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id,
	// 	beforeSend: function() {
	// 		$('#userresult').html("<img src='images/uc.gif' height='80'>").show('fast');	
	// 	},
	// 	success:function(response){				
	// 		$("#userresult").html(response);
	// 		$("#class_id").val(classid);		
	// 	}
	// });
		
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

	// $.ajax({
	// 	type:"POST",
	// 	cache:false,
	// 	url:"action.php",
	// 	data: "action=getList&subject_id="+subject_id+"&classid="+classid+"&school_id="+school_id+"&state_id="+state_id+"&city_id="+city_id,
	// 	beforeSend: function() {
	// 		$('#userresult').html("<img src='images/uc.gif' height='80'>").show('fast');	
	// 	},
	// 	success:function(response){
	// 		$("#userresult").html(response);
	// 		$("#subject_id").val(subject_id);
	// 	}
	// });
		
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
			if ( A1 > 0 ) dynamicData.push({ label: "A1 (90%-100%)("+A1per+"%)", "y": A1, color: "green", bottomlabel: "A1 (90%-100%)" },);
			if ( A2 > 0 ) dynamicData.push({ label: "A2 (80%-90%)("+A2per+"%)", "y": A2, color: "#46d246",bottomlabel: "A2 (80%-90%)" },);
			if ( B1 > 0 ) dynamicData.push({ label: "B1 (70%-80%)("+B1per+"%)", "y": B1, color: "#e6e600", bottomlabel: "B1 (70%-80%)" },);
			if ( B2 > 0 ) dynamicData.push({ label: "B2 (60%-70%)("+B2per+"%)", "y": B2, color: "yellow",bottomlabel: "B2 (60%-70%)" },);
			if ( C1 > 0 ) dynamicData.push({ label: "C1 (50%-60%)("+C1per+"%)", "y": C1, color: "#ff9900", bottomlabel: "C1 (50%-60%)" },);
			if ( C2 > 0 ) dynamicData.push({  label: "C2 (40%-50%)("+C2per+"%)", "y": C2, color: "#ffb84d", bottomlabel: "C2 (40%-50%)"  },);
			if ( D > 0 ) dynamicData.push({ label: "D (30%-40%)("+Dper+"%)", "y": D, color: "blue", bottomlabel: "D (30%-40%)"},);
			if ( E1 > 0 ) dynamicData.push({ label: "E1 (20%-30%)("+E1per+"%)", "y": E1, color: "#B22222",bottomlabel: "E1 (20%-30%)"  },);
			if ( E2 > 0 ) dynamicData.push({  label: "E2 (0%-20%)("+E2per+"%)", "y": E2, color: "red", bottomlabel: "E2 (0%-20%)"  },);
			
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


function changeTopic(id,sval){
	//$('#chapter').empty('');
     //console.log('subject_id' + subject_id);

	//lert(sval);
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
			if ( A1 > 0 ) dynamicData.push({ label: "A1 (90%-100%)("+A1per+"%)", "y": A1, color: "green", bottomlabel: "A1 (90%-100%)" },);
			if ( A2 > 0 ) dynamicData.push({ label: "A2 (80%-90%)("+A2per+"%)", "y": A2, color: "#46d246",bottomlabel: "A2 (80%-90%)" },);
			if ( B1 > 0 ) dynamicData.push({ label: "B1 (70%-80%)("+B1per+"%)", "y": B1, color: "#e6e600", bottomlabel: "B1 (70%-80%)" },);
			if ( B2 > 0 ) dynamicData.push({ label: "B2 (60%-70%)("+B2per+"%)", "y": B2, color: "yellow",bottomlabel: "B2 (60%-70%)" },);
			if ( C1 > 0 ) dynamicData.push({ label: "C1 (50%-60%)("+C1per+"%)", "y": C1, color: "#ff9900", bottomlabel: "C1 (50%-60%)" },);
			if ( C2 > 0 ) dynamicData.push({  label: "C2 (40%-50%)("+C2per+"%)", "y": C2, color: "#ffb84d", bottomlabel: "C2 (40%-50%)"  },);
			if ( D > 0 ) dynamicData.push({ label: "D (30%-40%)("+Dper+"%)", "y": D, color: "blue", bottomlabel: "D (30%-40%)"},);
			if ( E1 > 0 ) dynamicData.push({ label: "E1 (20%-30%)("+E1per+"%)", "y": E1, color: "#B22222",bottomlabel: "E1 (20%-30%)"  },);
			if ( E2 > 0 ) dynamicData.push({  label: "E2 (0%-20%)("+E2per+"%)", "y": E2, color: "red", bottomlabel: "E2 (0%-20%)"  },);
			
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
			
			if (sval){
				var title1 = sval;
			} else {
				var title1 = "Performance Chart ";	
			}	
			var chart = new CanvasJS.Chart("chartContainer", {
				theme: "dark2", // "light1", "light2", "dark1", "dark2"
				exportEnabled: true,
				animationEnabled: true,				
				title: {
					text: title1,
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

var datatableExample = '';
function getdownload(){
	var downloadtype = $('#downloadtype').val();
	if(downloadtype == 'pdf') {
		datatableExample.buttons(0,0).trigger();
		return;
	}

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
<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
//echo 'Page generated in '.$total_time.' seconds.';
?>