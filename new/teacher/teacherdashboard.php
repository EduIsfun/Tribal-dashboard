<?php
session_start();
/*error_reporting(E_ALL);
ini_set("display_errors",1);*/
include('../db_config.php');

if(empty($_SESSION['uid'])){
	header('Location:index.php');
}

function __autoload($classname){
	include("Classes/$classname.class.php");
}

$dashboard = new Dashboard();
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Dashboard</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="css/style.css" rel="stylesheet">
</head>
<body onload="getchapters('math')">
<div class="container-fluid">
	<nav class="navbar navbar-default nav_height">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
			<a href="#"><img src="images/logo.png"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse Sb-navbar_padd">
            <ul class="nav navbar-nav sb_navbar_top" id="navPrincipal">
                <li><a href="#"><i class="fa fa-home" aria-hidden="true" ></i> &nbsp Dashboard</a></li>
                <li><a href="#"><i class="fa fa-user" aria-hidden="true" ></i> &nbsp PROFILE</a></li>
                <li><a href="#"><i class="fa fa-cog" aria-hidden="true" ></i> &nbsp Change Password</a></li>
				<li><a href="logout.php"><i class="fa fa-power-off" aria-hidden="true" ></i> &nbsp Logout</a></li>
            </ul>
        </div>
    </nav>
</div>

 

<!-- Dashboard -->
<div class="main_head">
	<div class="container-fluid"><h2>Teacher Dashboard</h2></div>
</div>

<div class="containt_area">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6 col-sm-6">
			<form>
			<div class="form-group dash_group">
				<label>School</label> &nbsp;
				<span class="plain-select">
				<select class="form-control form_dash" id="schoolid" onchange='return getresult();'>
					<?php
						$resultlist = $dashboard->getschoolResult();
						while($schoolist = mysqli_fetch_assoc($resultlist)){
					?>
						<option value="<?php echo $schoolist['school']?>" ><?php echo $schoolist['school']?></option>
					<?php   }   ?>	
				</select> 
				</span>
			</div>
			</form>
			</div>
			<div class="col-md-6 col-sm-6">
			<form>
			<div class="form-group dash_group">
				<label>Class </label> &nbsp;
				<span class="plain-select">
				<select class="form-control form_dash" id="classid" onchange='return getresult();'>
					<?php
						$schoollist = $dashboard->getclassResult();
						while($schooobj = mysqli_fetch_assoc($schoollist)){
					?>
						<option value="<?php echo $schooobj['grade']?>"><?php echo $schooobj['grade']?></option>
					<?php   }   ?>	
				</select> 
				</span>
			</div>
			</form>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="stream">
					<ul>
						<li><button type="button" class="btn first_sub" id='math' onclick="getchapters('math');">Mathematics</button></li>
						<li><button type="button" class="btn second_sub" id='science'onclick="getchapters('science');">Science</button></li>
						<li><button type="button" class="btn second_sub" id='all' onclick="getchapters('all');">All</button></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
			<form>
				<div class="form-group dash_group">
				<label>Chapter</label>
				<span class="plain-select" style="max-width: 250px;">
				<select class='form-control form_dash' name='chaptername' id='chaptername' onchange='return getresult();' >
				<option value=""></option>
				
				</select> 
				</span>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>
<div class="container"> 
<div id="studentresult" class="table-scroll">
 
</div>
</div>

	<!--<td><div class="popupHoverElement" id="hovers" ><span class="green">A1</span>
	  <div id="two" class="popupBox1">
	  <h2>Application of Trignometery</h2>
	  <table style="width:100%;">
	  <tr>
	  <td>70%</td>
	 <td>10%</td>
	  <td>10%</td>
	  <td>10%</td>
	  </tr>
	  </table>
	  </div>
	  </div></td>-->
<!-- end Dashboard -->
<script>// requires jquery library
jQuery(document).ready(function() {
   jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');   
 });
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script>
function getchapters(subjectid) {
	
    $.ajax({
		type: "POST",
		cache:false,
		url: "chapters.php",
		data: "action=getchapter&subjectid="+ subjectid ,
		success: function(result){
			if(result=='0'){
				alert("There is an error to update post type");
			} else{
				$("#chaptername").html(result);
				if (subjectid=="math"){
					$("#math").removeClass('btn second_sub').addClass('btn first_sub');
					$("#science").removeClass('btn first_sub').addClass('btn second_sub');
					$("#all").removeClass('btn first_sub').addClass('btn second_sub');
				} else if (subjectid=="science"){
					$("#science").removeClass('btn second_sub').addClass('btn first_sub');
					$("#math").removeClass('btn first_sub').addClass('btn second_sub');
					$("#all").removeClass('btn first_sub').addClass('btn second_sub');
				} else {
					$("#all").removeClass('btn second_sub').addClass('btn first_sub');
					$("#science").removeClass('btn first_sub').addClass('btn second_sub');
					$("#math").removeClass('btn first_sub').addClass('btn second_sub');
				}	
				
			}
		}
	});
}

function getresult() {
	var schoolid =$('#schoolid').val();
	var classid =$('#classid').val();
	var chapterid =$('#chaptername').val();
	
	if (chapterid=="all"){
		var phppage = "allstudentresult.php";
		var phpdata= "action=getresult&schoolid="+schoolid+"&classid="+classid+"&chapterid=all";
	} else {
		var phppage = "studentresult.php";
		var phpdata = "action=getresult&schoolid="+schoolid+"&classid="+classid+"&chapterid="+chapterid;
	}
	
    $.ajax({
		type: "POST",
		cache:false,
		url: phppage,
		data: phpdata,
		beforeSend: function() {
			$('#studentresult').html("<img src='images/loader.gif' height='40'>").show('fast');	
			},
		success: function(result){
			if(result=='0'){
				alert("There is an error to update post type");
			} else{
				$("#studentresult").html(result);
			}
		}
	});
	getsubjectenable(chapterid);
}

function getsubjectenable(chapterid) {
	
	$.ajax({
		type: "POST",
		cache:false,
		url: "subjectresult.php",
		data: "action=getsubject&chapterid="+chapterid,
		
		success: function(result){
			
			if(result=='0'){
				//alert("There is an error to update post type");
			} else{
				result=result.trim();
				if (result=="1"){
					$("#math").removeClass('btn second_sub').addClass('btn first_sub');
					$("#science").removeClass('btn first_sub').addClass('btn second_sub');
					//$("#all").removeClass('btn first_sub').addClass('btn second_sub');
				} else if ((result=="2") || (result=="3") || (result=="8") || (result=="14")){
					$("#science").removeClass('btn second_sub').addClass('btn first_sub');
					$("#math").removeClass('btn first_sub').addClass('btn second_sub');
					//$("#all").removeClass('btn first_sub').addClass('btn second_sub');
				} else {
					$("#all").removeClass('btn second_sub').addClass('btn first_sub');
					$("#science").removeClass('btn first_sub').addClass('btn second_sub');
					$("#math").removeClass('btn first_sub').addClass('btn second_sub');
				}	
			}
		}
	});
	
}
</script>
</body>
</html>