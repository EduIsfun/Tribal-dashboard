<?php
session_start();
include('db_config.php');
if(empty($_SESSION['uids'])){
	header('Location:index.php');
}
$currentUser  = $_SESSION['currentuser'];
if(isset($_POST['submit']) AND $_POST['submit'] == 'GO'){
	$schoolname=$_POST['school'];
	if ($schoolname!=''){
		$_SESSION['schoolname']=$schoolname;
		echo "<script>window.open('principaldashboard.php','_self')</script>";		
	} 
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/form.css">
</head>
<body>
<div class="nav-top"> 
	<div class="container-fluid"><?php include('header.php');?></div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="top_head">
				<h3>School List</h3>
			</div>
			<form method="post" >
			<div class="table-responsive sb_border-shadow">		
				<table class="table inner_tabel">
					<tr> 
					<td>SELECT </td>
					<td>School</td>
				</tr>
				<?php

				if(!empty($_SESSION['uids'])){
					$sql = "SELECT * FROM teacher_login WHERE userID = '".$_SESSION['uids']."'";
					$result = mysqli_query($conn,$sql);
					$obj = mysqli_fetch_object($result);
					$schoolarray=array();
					$schoolarray = explode("|",$obj->school);
					foreach($schoolarray  as $value ){

					?>
					<tr> 
						<td><input type="radio" name="school" value="<?php echo $value; ?>" ></td>
						<td><?php echo $value; ?></td>
					
				<?php } 
				}?>
				</tr>
						<tr> 
						<td><input type="radio" name="school" value="<?php echo $obj->main_school; ?>" ></td>
						<td><?php echo $obj->main_school; ?></td>
					</tr>
				</table>
				<input type="submit" name="submit" onclick="return formsubmit()"  value="GO" class="btn btn-primary sb_login-btn">
			</div>
			</form>
		</div>
	</div>
</div>
<!-- jQuery Version 1.11.1 -->
<script src="js/jquery.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
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
</script>
</body>
</html>