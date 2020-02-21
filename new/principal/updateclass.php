<?php 
session_start();
if(empty($_SESSION['uids'])){
	header('Location:index.php');
}
include('db_config.php');
include('functions.php');
global $conn;

if(isset($_POST['update'])){
	$schoollist= 	implode("|",$_POST['school']);
	$schoolname = str_replace("|", "','", $schoollist );
	$sql = "SELECT * FROM user_school_details WHERE school IN ('$schoolname')";
	$result = mysqli_query($conn,$sql);	
	while($row = mysqli_fetch_object($result)){
		$studentid =$row->userID;
		$school =$row->school;
		$grade =$row->grade;
		$class_enabled =$row->class_enabled;
		$classupdate= max(explode(",",$class_enabled));
		$updatedgrade = ConverToRoman($classupdate);
			//echo $grade."=".$updatedgrade."|".$studentid  ;
			//echo "<br>";
		if ($grade==$updatedgrade){
			//echo $grade."=".$updatedgrade ;
			//echo "<br>";
		} else {	
			if ($updatedgrade!=''){
				$updatequery  = "update  user_school_details set grade= '$updatedgrade' WHERE userID=  '$studentid'";
				//echo $updatequery ;
				//echo "<br>";
				mysqli_query($conn,$updatequery);
				$msg=1;
			}	
		}	
	}	
	
}
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
</head>
<body>
<div class="nav-top"> 
	<div class="container-fluid"><?php include('header_admin.php');?></div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3"> 
			<div class="top_head"> 
				<?php 
					if(isset($msg) && ($msg!=' ')){
						echo '<p style="color:green; margin-left:110px;margin-top:30px;"> Record has been updated successfully!</p>';
					}
				?>
				<?php 
				$sql = "SELECT * FROM `teacher_login` WHERE `userID`= '".$_GET['id']."'";
				$result = mysqli_query($conn,$sql);
				$obj = mysqli_fetch_object($result);
				$schoolarray=array();
				$schoolarray = explode("|",$obj->school);
				?>
				<h3 class="text-center">Update Grade of Students</h3>
				<form method="post" action="">
				<div class="sb_border-shadow">
					<table class="table inner_tabel">
						<thead>
							<tr>
								<th>Select School</th>
								<td>	<select name="school[]" id="school" multiple required class="form-control input-md">
								<option value="">Select School</option>
								<?php  foreach($schoolarray  as $value ){ ?>
								
									<option value="<?php echo  $value;?>"><?php echo  $value;?></option>
										<?php } ?>	
									<?php if ($obj->main_school!=''){?>
									<option value="<?php echo  $obj->main_school;?>"><?php echo $obj->main_school;?></option>
									<?php } ?>		
								</select>	
								</td>
							</tr>
							<tr>
								<th><input type="hidden" value="<?php echo $obj->id;?>" name="user_id"></th>
								<td colspan=2><input onclick="return formsubmit()" type="submit"  class="btn btn-primary" name="update" value="Update"></td>
							</tr>
						</thead>
				
					</table>
					
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<script>
function formsubmit() {
   return confirm('Do you really want to submit the form?');
}
</script>