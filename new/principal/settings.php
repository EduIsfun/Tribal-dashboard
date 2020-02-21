<?php 
session_start();
if(empty($_SESSION['uids'])){
	header('Location:index.php');
}
error_reporting(0);
include('db_config.php');
global $conn;

if(isset($_POST['update'])){
	$user_id = $_POST['user_id'];
	$class_rank = $_POST['class_rank'];
	$global_rank = $_POST['global_rank'];
	$alias_rank = $_POST['alias_rank'];
	$query="UPDATE user_admin_settings set class_rank='".$class_rank."',global_rank='".$global_rank."',alias_rank='".$alias_rank."'  WHERE id ='".$user_id."'"; 
	//echo $query;
	$result = mysqli_query($conn,$query);
	$msg=1;
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
				$sql = "SELECT * FROM `teacher_login` tl INNER JOIN `user_admin_settings` uas ON tl.`id`=uas.`user_id` WHERE tl.`userID`= '".$_GET['id']."'";
				$result = mysqli_query($conn,$sql);
				$obj = mysqli_fetch_object($result);
				?>
				<h3 class="text-center">Admin Settings</h3>
				<form method="post" action="">
				<div class="sb_border-shadow">
					<table class="table inner_tabel">
						<thead>
							<tr>
								<th>Username</th>
								<td><?php echo $obj->userID?></td>
							</tr>
							<tr>	
								<th>Class Rank</th>
								<td><input type ="checkbox" name ="class_rank" <?php if ($obj->class_rank=="on"){ echo "checked";}?>></td>
							</tr>
							<tr>
								<th>Global Rank</th>
								<td><input type ="checkbox" name ="global_rank" <?php if ($obj->global_rank=="on"){ echo "checked";}?>></td>
								
							</tr>
							<tr>
								<th><?php echo $obj->alias;?> Rank</th>
								<td><input type ="checkbox" name ="alias_rank" <?php if ($obj->alias_rank=="on"){ echo "checked";}?>></td>
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