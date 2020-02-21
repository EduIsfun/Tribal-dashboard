<?php
session_start();
include('db_config.php');
include('functions.php');

if(empty($_SESSION['adminuids'])){
	header('Location:index.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>Dashboard</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/form.css">
</head>
<body>
<div class="nav-top"> 
	<div class="container-fluid"><?php include('header_admin.php');?></div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-12 ">
			
			<form method="post"  action="user_profile.php" >
			<div class="dash-table" id="userresult" style="">
				<div class="top_head">
					<h3>User List</h3>
				</div>
						<table id="example1" class="table table-bordered table-striped" data-page-length="25">
							<thead><tr>
								<th>SELECT </th>
								<th>User Name</th>
								<th>Name</th>
								<th>Main School</th>
								<th>Grouped School</th>
								<th>Switch Permission</th>
							</tr>
							<tbody>
							<?php

							if(!empty($_SESSION['adminuids'])){
								$sql = "SELECT * FROM teacher_login where usertype!='admin' order by userID asc";
								$result = mysqli_query($conn,$sql);
								while ($obj = mysqli_fetch_object($result)){
							
								?>
								<tr > 
									<td style="color:#000 !important"><input type="radio" name="school" id="school" value="<?php echo $obj->userID; ?>" ></td>
									<td style="color:#000 !important"><?php echo $obj->userID; ?></td>
									<td style="color:#000 !important"><?php echo $obj->name; ?></td>
									<td style="color:#000 !important"><?php echo $obj->main_school; ?></td>
									<td style="color:#000 !important"><?php echo $obj->school; ?></td>
									<td style="color:#000 !important"><?php  if ($obj->switch_permission=="on") {echo "Yes";} else {echo "No";}?></td>
									
								</tr>
							<?php } 
							}?>
							</tbody>
							</table>
						<div class="col-md-12 text-left" style>
							<input type="submit" name="submit" onclick="return formsubmit()"  value="Edit Details" class="btn btn-primary sb_login-btn">
							<input type="button" name="submit" onclick="return formsubmitdel()"  value="Delete Details" class="btn btn-primary sb_login-btn">
							<input type="submit" name="submit" onclick="return register()" value="New User" class="btn btn-primary sb_login-btn">
							<input type="submit" name="submit" onclick="return formsettings()" value="Settings" class="btn btn-primary sb_login-btn">
							<input type="submit" name="submit" onclick="return updateclass()" value="Update Class" class="btn btn-primary sb_login-btn">
						</div>
			</div>
			</form>
		</div>
	</div>
</div>

<script>
function formsubmit(){
	var schoolname = document.getElementsByName("school");
	var chesch =0 ;
	for (var i=0; i<schoolname.length; i++) {
		// If you have more than one radio group, also check the name attribute
		// for the one you want as in && chx[i].name == 'choose'
		// Return true from the function on first match of a checked item
		if (schoolname[i].type == 'radio' && schoolname[i].checked) {
			chesch=1;
		 
		} 
	}
	if (chesch==0){
		alert("Please select atleast one school");
		return false;
	}	
  // End of the loop, return false
   return true;
}

function formsubmitdel(){
	var schoolname = document.getElementsByName("school");

	var chesch =0 ;
	for (var i=0; i<schoolname.length; i++) {
		if (schoolname[i].type == 'radio' && schoolname[i].checked) {
			var schoolval = schoolname[i].value;
			chesch=1;
		} 
	}
	
	if (chesch==0){
		alert("Please select atleast one school");
		return false;
	}	
	
	if (confirm("Are you sure you want to delete?") == true) {
	$.ajax({
		type: 'POST',
		url:"deleteuser.php",
		data: 'action=del&id='+schoolval,
		success: function(data){
			if (data!=""){
					location.reload(false);
			}
		}
	});		
  }
	//return false;
}


function register(){
	window.location = 'register.php';
    return false;
}

function formsettings(){
	
	var schoolname = document.getElementsByName("school");

	var chesch =0 ;
	for (var i=0; i<schoolname.length; i++) {
		if (schoolname[i].type == 'radio' && schoolname[i].checked) {
			var schoolval = schoolname[i].value;
			chesch=1;
		} 
	}
	
	if (chesch==0){
		alert("Please select atleast one school");
		return false;
	}	
	
	
	window.location = 'settings.php?id='+schoolval;
    return false;
}

function updateclass(){
	
	var schoolname = document.getElementsByName("school");

	var chesch =0 ;
	for (var i=0; i<schoolname.length; i++) {
		if (schoolname[i].type == 'radio' && schoolname[i].checked) {
			var schoolval = schoolname[i].value;
			chesch=1;
		} 
	}
	
	if (chesch==0){
		alert("Please select atleast one school");
		return false;
	}	
	
	
	window.location = 'updateclass.php?id='+schoolval;
    return false;
}
</script>
<script src='js/jquery.min.js'></script>
<script src='js/bootstrap.min.js'></script>	
<script src='js/jquery.dataTables.min.js'></script>
<script src='js/dataTables.bootstrap.min.js'></script>
<script>
	$(function () {
		$('#example1').DataTable({
			'paging'      : true,
			'lengthChange': false,
			'searching'   : false,
			'ordering'    : true,
			'info'        : true,
			'autoWidth'   : false
		})
	})
</script>
</body>
</html>