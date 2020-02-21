<?php
session_start();
//error_reporting(0);
 error_reporting(E_ALL);
 ini_set("display_errors",1);
if(empty($_SESSION['uid']))
{
	header("location:index.php");
}
include('db_config.php');
include('model/Teacher.class.php');
global $conn;
$teacher = new Teacher();

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

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i" rel="stylesheet">

	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" /> 
	
	<link rel="stylesheet" href="css/style.css"/>  
	
 <style>
 table.table.sub_table1 tbody > tr:first-child {
   /* display: none;*/
}
label {
    display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: 700;
    margin-top: 10px!important;
  
    
}
 </style> 
</head>
<body>
<!-- header -->
<div class="container-fluid">

        <nav class="navbar navbar-default">
            <h2>Dashboard</h2>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
					<a href="#"><img src="images/logo.png"></a> 
                </div>
                <div id="navbar" class="navbar-collapse collapse navbar-right">
                    <ul class="nav navbar-nav">
                       <li><a href="profile.php">Profile</a></li> 
                        <li><a href="change.php">Change Password</a></li>
						<li><a href="logout.php">Logout</a></li>
                     
                    </ul>
                </div>
            
        </nav>
</div>
<!-- header -->

<!-- Dashboard -->
	 
		<div class="container">
			<div class="row">
				<div class="col-md-12" style="text-align: center;">
					<h2 class="main_head">Admin Dashboard</h2>
				</div>
			</div>
		</div>
		
		
			<div class="container">
				<div class="Section-1">
				<div class="row">
					<div class="col-md-12" style="padding-left: 5%;">
						<ul class="button-inline">
							<li>
								<div class="select top_input">
									<select name="state" id="state" onchange="ChangeCity(this.value);">
									<option value="">State</option>
									<?php 
									$countryid =101;	
									$result = $teacher->getStateResult($countryid);
									while($obj=mysqli_fetch_object($result)):			
									?>
									<option value="<?php echo $obj->id?>" <?php if(!empty($_POST['state'])){ echo 'selected';} ?>><?php echo $obj->name?></option>
									<?php endwhile; ?>
									</select>
								</div>
							</li>
							<li>
								<div class="select top_input">
									<select name= "city" id="city" onchange="changeBoardName(this.value);">
									<option value=""> Choose City</option>
									</select>
								</div>
							</li>
							<li>
								<div class="select top_input">
									<select name="board" id="board" onchange="changeBoard(this.value);">
									<option value="">Board</option>									
									<?php 
										$result = $teacher->getBoardResult();
										while($obj=mysqli_fetch_object($result)):		
											?>
											<option value="<?php echo $obj->id?>"><?php echo $obj->board?></option>
											<?php 
										endwhile; 
									?>
									<option value="All">Other</option>
									</select>
								</div>
							</li>
							<li>
								<div class="select top_input">
									<select name="school" id="school" onchange = "changeSchool(this.value);">
									<option value="">School</option>
									</select>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div class="container">
				<div class="Section-1">
				<div class="row">
					<div class="col-md-12" style="padding-left: 15%;">
						<ul class="button-inline">
						
							<li>
								<div class="select top_input">
									<select id="classid" name="classid" onchange="changeClass(this.value);">
									<option value="">Class</option>
									<?php 
										$classresult = $teacher->getclassResult();
										while($objclass = mysqli_fetch_object($classresult)):
										$clsID = ConverToRoman($objclass->id);
										?>
										<option value="<?php echo $clsID;?>"><?php echo $objclass->class2?></option>
										<?php 
										endwhile
									?>
									</select>
								</div>
							</li>
							<!--<li>
								<div class="select">
									<select>
									<option value="">Division</option>
									<option value="1">Pure CSS</option>
									<option value="2">No JS</option>
									<option value="3">Nice!</option>
									</select>
								</div>
							</li>-->
							<li>
								<div class="select top_input">
									<select name="subject" id="subject" onchange="changeSubject(this.value);">
									    <option value="">Choose Subject</option>
										<!-- <?php 
											$subjectresult = $teacher->getSubjectResult();
											while($objsubject = mysqli_fetch_object($subjectresult)):
										?>
										<option value="<?php echo $objsubject->subjectID?>"><?php echo $objsubject->subject?></option>
										<?php endwhile;?> -->
								  </select>
								</div>
							</li>
							<li>
								<div class="select top_input">
									<select name="chapter" id="chapter" onchange="changeChapter(this.value);">
										<option value="">Choose Chapter</option>
									</select>
								</div>
							</li>
							
							<!-- <li>
								<div class="select top_input">
									<select name="subchapterid" id="subchapterid" onchange="changeSubChapters(this.value);">
									<option value="">Choose Topic</option>
									
									</select>
								</div>
							</li> -->
						</ul>
					</div>
				</div>
			</div>
		</div> 
<!-- end -->



<!-- table -->

<div class="container">   
	<div class="dash-table" id="userresult"></div>
</div>
<!-- end -->

<!-- Note -->
<div class="container">
	<div class="row">
		<div class="col-md-12"> 
			<div class="tiles" style="text-align: center;"> 
			<span>View Grade Description.</span>
					<a class="collapsed button blue" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
						Click Here
					</a> 
			</div>
			<div class="panel-group" id="accordion" style="padding-top: 15px;">
				<div class="panel panel-default">
					 
					<div id="collapseOne" class="panel-collapse collapse">
						<div class="panel-body"> 
					<table class="table table-fill table-hover">
						<tbody class="table-hover" style="text-align: center;">
							<tr>
								<th class="text-left">A1</th>
								<td class="text-left">90-100</td>
								<td class="text-left green"><i></i></td> 
							</tr>
							<tr>
								<th class="text-left">A2</th>
								<td class="text-left">80-90</td>
								<td class="text-left green"><i></i></td> 
							</tr>
							<tr>
								<th class="text-left">B1</th>
								<td class="text-left">70-80</td>
								<td class="text-left yellow"><i></i></td> 
							</tr>
							<tr>
								<th class="text-left">B2</th>
								<td class="text-left">60-70</td>
								<td class="text-left yellow"><i></i></td> 
							</tr>
							<tr>
								<th class="text-left">C1</th>
								<td class="text-left">50-60</td>
								<td class="text-left blue"><i></i></td> 
							</tr>
							<tr>
								<th class="text-left">C2</th>
								<td class="text-left">40-50</td>
								<td class="text-left blue"><i></i></td> 
							</tr>
							
							<tr>
								<th class="text-left">D</th>
								<td class="text-left">30-40</td>
								<td class="text-left blue"><i></i></td> 
							</tr>
							<tr>
								<th class="text-left">E1</th>
								<td class="text-left">20-30</td>
								<td class="text-left red"><i></i></td> 
							</tr>
							<tr>
								<th class="text-left">E2</th>
								<td class="text-left">0-20</td>
								<td class="text-left red"><i></i></td> 
							</tr>
						</tbody>
					</table>
			 
						</div>
					</div>
	
					</div>
				</div>
			   
			</div> 
		</div>

<!-- end -->
<div id="muhtml"></div>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>

 /* -----+----+--- Get city result ---+---+--*/  

function ChangeCity(city_name)
{	 
	$("#board").val('');	 
	$("#classid").val('');	 
	$("#subject").val('');	 
	$("#chapter").val('');	 
	$("#subchapterid").val('');	
 	$("#userresult").empty();	 
 	$('#school').find('option').remove().end().append('<option value="">School</option>');

	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=getCity&city_id="+ city_name,
		success:function(response){
			$("#city").html(response);
			//console.log('response'+ response);
		}
	});
	
	var classid = ($("#classid option:selected").val()) ? $("#classid option:selected").val() : 0;
	var schoolid = ($("#school option:selected").val()) ? $("#school option:selected").val(): 0;
	var subchapterid = ($("#subchapterid option:selected").val()) ? $("#subchapterid option:selected").val(): 0;
	//console.log('subchapterid' + subchapterid);
	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=getList&state_id="+ city_name+"&classid="+classid+"&schoolid="+schoolid+"&subchapterid="+subchapterid,
		beforeSend: function() {
			$('#userresult').html("<img src='images/pleasewait.gif' height='80'>").show('fast');	
		},
		success:function(response){
			
			$("#userresult").html(response);
			//console.log('response'+ response);
		}
	}); 
	
}

function changeBoardName()
{
	$("#board").val('');
	$("#classid").val('');
	$("#subject").val('');
	$('#chapter').find('option').remove().end().append('<option value="">Choose Chapter</option>');
	$('#subchapterid').find('option').remove().end().append('<option value="">Choose Topic</option>');
	$('#school').find('option').remove().end().append('<option value="">School</option>');
	$("#userresult").empty();
	
	
	var classid = ($("#classid option:selected").val()) ? $("#classid option:selected").val() : 0;
	var schoolid = ($("#school option:selected").val()) ? $("#school option:selected").val(): 0;
	var subchapterid = ($("#subchapterid option:selected").val()) ? $("#subchapterid option:selected").val(): 0;
	var state_id=($("#state option:selected").val()) ? $("#state option:selected").val(): 0;
	var city_id=($("#city option:selected").val()) ? $("#city option:selected").val(): 0;
	//console.log('subchapterid' + subchapterid);

	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=getList&state_id="+state_id+"&classid="+classid+"&schoolid="+schoolid+"&subchapterid="+subchapterid+"&city_id="+city_id,
		beforeSend: function() {
			$('#userresult').html("<img src='images/pleasewait.gif' height='80'>").show('fast');	
		},
		success:function(response){
				//alert(response);
			$("#userresult").html(response);
			//console.log('response'+ response);
		}
	}); 
}
 
/* ---+-----+---- Get Board result list ---+---+--*/  

function changeBoard(board) {
	//$('#classid').val('');
	$('#subject').val('');
	$('#chapter').find('option').remove().end().append('<option value="">Choose Chapter</option>');
	$('#subchapterid').find('option').remove().end().append('<option value="">Choose Topic</option>');
	$('#school').find('option').remove().end().append('<option value="">School</option>');
	$("#userresult").empty();
	
	var city = $("#city option:selected").val();
	
	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=getBoard&board_id="+board+"&city="+city,
		success:function(response){
			//alert(response);
			$("#school").html(response);
			//console.log('response'+ response);
			
		}
	});
	
	var classid = ($("#classid option:selected").val()) ? $("#classid option:selected").val() : 0;
	var schoolid = ($("#school option:selected").val()) ? $("#school option:selected").val(): 0;
	var subchapterid = ($("#subchapterid option:selected").val()) ? $("#subchapterid option:selected").val(): 0;
	var state_id=($("#state option:selected").val()) ? $("#state option:selected").val(): 0;
	var chapter_id=($("#chapter option:selected").val()) ? $("#chapter option:selected").val(): 0;
	//console.log('subchapterid' + subchapterid);changeBoard

	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=getList&state_id="+state_id+"&classid="+classid+"&schoolid="+schoolid+"&subchapterid="+subchapterid+"&board_id="+board+"&city_id="+city+"&chapter_id="+chapter_id,
		beforeSend: function() {
			$('#userresult').html("<img src='images/pleasewait.gif' height='80'>").show('fast');	
		},
		success:function(response){
				//alert(response);
			$("#userresult").html(response);
			console.log('response'+ response);
		}
	}); 

	var board_id=$('#board').val();
	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=get_boardwise_subjects&board_id="+board_id,
		success:function(response){
			$("#subject").html(response);			
		}
	});
}


/* ---+-----+---- Get subject result ---+---+--*/ 


function changeSubject(subject){
	$('#subchapterid').find('option').remove().end().append('<option value="">Choose Topic</option>');
	var classid = ($("#classid option:selected").val()) ? $("#classid option:selected").val() : 0;
	
	
	var schoolid = ($("#school option:selected").val()) ? $("#school option:selected").val(): 0;
	var subtopic = $("#subchapterid").val();
	var state_id=($("#state option:selected").val()) ? $("#state option:selected").val(): 0;
	var city_id=($("#city option:selected").val()) ? $("#city option:selected").val(): 0;
	var board_id=($("#board option:selected").val()) ? $("#board option:selected").val(): 0;
	var chapter_id=($("#chapter option:selected").val()) ? $("#chapter option:selected").val(): 0;
	var subject=$("#subject").val();
	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=getSubject&classid="+classid+"&subject="+subject+"&board_id="+board_id,
		success:function(response){
			$("#chapter").html(response);
			//console.log('response'+ response);
		}
	});
	
	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=getList&state_id="+state_id+"&classid="+classid+"&schoolid="+schoolid+"&subtopicid="+subtopic+"&board_id="+board_id+"&city_id="+city_id+"&chapter_id="+chapter_id+"&subject_id="+subject,
		beforeSend: function() {
			
			$('#userresult').html("<img src='images/pleasewait.gif' height='80'>").show('fast');	
		},
	
		success:function(response){
			//alert(response);
			$("#userresult").html(response);
			//console.log('response'+ response);
		}
	});
} 

/* ---+-----+---- Get user result list with class---+---+--*/ 


function changeClass(school){
	$("#subject").val('');
	$('#chapter').find('option').remove().end().append('<option value="">Choose Chapter</option>');
	$('#subchapterid').find('option').remove().end().append('<option value="">Choose Topic</option>');
	var classid = ($("#classid option:selected").val()) ? $("#classid option:selected").val() : 0;
	var schoolid = ($("#school option:selected").val()) ? $("#school option:selected").val(): 0;
	var subchapterid = ($("#subchapterid option:selected").val()) ? $("#subchapterid option:selected").val(): 0;
	var state_id=($("#state option:selected").val()) ? $("#state option:selected").val(): 0;
	var city_id=($("#city option:selected").val()) ? $("#city option:selected").val(): 0;
	var board_id=($("#board option:selected").val()) ? $("#board option:selected").val(): 0;
	var chapter_id=($("#chapter option:selected").val()) ? $("#chapter option:selected").val(): 0;

	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=getList&state_id="+state_id+"&classid="+classid+"&schoolid="+schoolid+"&subchapterid="+subchapterid+"&board_id="+board_id+"&city_id="+city_id+"&chapter_id="+chapter_id,
		beforeSend: function() {
			$('#userresult').html("<img src='images/pleasewait.gif' height='80'>").show('fast');	
		},
		success:function(response){
			$("#userresult").html(response);
			console.log('response'+ response);
		}
	});

	//data: "action=get_classwise_subjects&classid="+classid+"&board_id="+board_id;
	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=get_classwise_subjects&classid="+classid+"&board_id="+board_id,
		success:function(response){
			$("#subject").html(response);			
		}
	});
	
}

/* ---+-----+---- Get user result list with school ---+---+--*/ 

function changeSchool(school){
	
	$("#classid").val('');
	$("#subject").val('');
	$('#chapter').find('option').remove().end().append('<option value="">Choose Chapter</option>');
	$('#subchapterid').find('option').remove().end().append('<option value="">Choose Topic</option>');
	
	var classid = ($("#classid option:selected").val()) ? $("#classid option:selected").val() : 0;
	var schoolid = ($("#school option:selected").val()) ? $("#school option:selected").val(): 0;
	//var chapterid = ($("#chapter option:selected").val()) ? $("#chapter option:selected").val(): 0;
	var subchapterid = ($("#subchapterid option:selected").val()) ? $("#subchapterid option:selected").val(): 0;
	var state_id=($("#state option:selected").val()) ? $("#state option:selected").val(): 0;
	var city_id=($("#city option:selected").val()) ? $("#city option:selected").val(): 0;
	var board_id=($("#board option:selected").val()) ? $("#board option:selected").val(): 0;
	var chapter_id=($("#chapter option:selected").val()) ? $("#chapter option:selected").val(): 0;
	//alert(board_id);
	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=getList&state_id="+state_id+"&classid="+classid+"&schoolid="+schoolid+"&subchapterid="+subchapterid+"&board_id="+board_id+"&city_id="+city_id+"&chapter_id="+chapter_id,
		beforeSend: function() {
			$('#userresult').html("<img src='images/pleasewait.gif' height='80'>").show('fast');	
		},
		success:function(response){
			$("#userresult").html(response);
			//console.log('response'+ response);
		}
	});
	
}


/* ---+-----+---- Get user result list with chapter ---+---+--*/ 

function changeSubChapters(){
	var classid = ($("#classid option:selected").val()) ? $("#classid option:selected").val() : 0;
	var schoolid = ($("#school option:selected").val()) ? $("#school option:selected").val(): 0;
	var subtopic = $("#subchapterid").val();
	var state_id=($("#state option:selected").val()) ? $("#state option:selected").val(): 0;
	var city_id=($("#city option:selected").val()) ? $("#city option:selected").val(): 0;
	var board_id=($("#board option:selected").val()) ? $("#board option:selected").val(): 0;
	var chapter_id=($("#chapter option:selected").val()) ? $("#chapter option:selected").val(): 0;
	
	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=getListsubtopic&state_id="+state_id+"&classid="+classid+"&schoolid="+schoolid+"&subtopicid="+subtopic+"&board_id="+board_id+"&city_id="+city_id+"&chapter_id="+chapter_id,
		beforeSend: function() {
			$('#userresult').html("<img src='images/pleasewait.gif' height='80'>").show('fast');	
		},
		success:function(response){
			$("#userresult").html(response);
			//console.log('response'+ response);
		}
	});
	
}

/* ---+-----+---- Get  result list with subchapter ---+---+--*/ 

function changeChapter(subchapter)
{
	
	$.ajax({
		type:"POST",
		cache:false,
		url:"process/postaction.php",
		data: "action=getsubSubject&subchapter="+subchapter,
		success:function(response){
			$("#subchapterid").html(response);
			// console.log('response'+response);
		}
	});
	
	var classid = ($("#classid option:selected").val()) ? $("#classid option:selected").val() : 0;
	var schoolid = ($("#school option:selected").val()) ? $("#school option:selected").val(): 0;
	var subchapterid = ($("#subchapterid option:selected").val()) ? $("#subchapterid option:selected").val(): 0;
	var state_id=($("#state option:selected").val()) ? $("#state option:selected").val(): 0;
	var city_id=($("#city option:selected").val()) ? $("#city option:selected").val(): 0;
	var board_id=($("#board option:selected").val()) ? $("#board option:selected").val(): 0;
	var chapter_id=($("#chapter option:selected").val()) ? $("#chapter option:selected").val(): 0;
	var subject_id=($("#subject option:selected").val()) ? $("#subject option:selected").val(): 0;
	$.ajax({
		type:"POST",
		cache:false,
		url:"studentresult.php",
		data: "action=getchapter&state_id="+state_id+"&classid="+classid+"&schoolid="+schoolid+"&subchapterid="+subchapterid+"&board_id="+board_id+"&city_id="+city_id+"&chapter_id="+chapter_id+"&subject_id="+subject_id,
		beforeSend: function() {
			$('#userresult').html("<img src='images/pleasewait.gif' height='80'>").show('fast');	
		},
		success:function(response){
			$("#userresult").html(response);
			//console.log('response'+ response);
		}
	});
	
}
function getPdf()
{
	var studentids = $('#studentids').val();
	
	//window.open('dashboard.php?action=getuserDetails&studentids='+ studentids,'_blank');
	
	//alert(studentids);
	 $.ajax({
		type:"POST",
		cache:false,
		url:"user_details.php",
		data: "action=getuserDetails&studentids="+studentids,
		beforeSend: function() {
			$('#userresults').html("<img src='images/loads.gif' height='150' width='300'>").show('fast');
				$('#btn').attr("disabled", true);
		},
		
		complete: function() {
			$('#userresults').html("<img src='images/loads.gif'>").hide();
			    $('#btn').attr("disabled", false);
		},
		success:function(response){
			var popUp = window.open('user_details.php?action=getuserDetails&studentids='+ studentids,'_blank');
			if (popUp == null || typeof(popUp)=='undefined') { 	
		alert('Please enable your pop-up blocker and click the "Open" link again.'); 
		} else { 	
			popUp.focus();
		}
			//alert(response);
			//$("#muhtml").html(response);
			//console.log('response'+response);
			// var w = window.open('about:blank');
			// w.document.open();
			// w.document.write(response);
			// w.document.close();
		}
	}); 
} 
 
</script>
</body>
</html>