<?php 
session_start();
if(empty($_SESSION['adminuids'])){
	header('Location:index.php');
}
error_reporting(0);
include('db_config.php');
global $conn;


$sql = "SELECT tl.*,b.`board`,tc.`name` AS cityname,ts.`name`AS statename FROM teacher_login tl INNER JOIN
board b ON tl.board_id=b.id INNER JOIN tbl_cities tc ON tl.city_id=tc.id INNER JOIN `tbl_states` ts ON 
tl.`state_id`=ts.`id` WHERE (userID =   '".$_POST['school']."'   or tl.id=    '".$_GET['id']."')";
$result = mysqli_query($conn,$sql);
$obj = mysqli_fetch_object($result);
$_SESSION['currentuser'] = $obj;
$currentUser  = $_SESSION['currentuser'];
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
					if(isset($_GET['msg']) && $_GET['msg']=='updated'){
						echo '<p style="color:green; margin-left:110px;margin-top:30px;"> Record has been updated successfully!</p>';
					}
				?>
				<h3 class="text-center">Admin Details</h3>
				<form method="post" action="edit-profile.php">
				<div class="sb_border-shadow">
					<table class="table inner_tabel">
						<thead>
							<tr>
								<th>Username</th>
								<td><?php echo ucfirst($currentUser->userID)?></td>
							</tr>
							<tr>
								<th>Name</th>
								<td><?php echo ucfirst($currentUser->name)?></td>
							</tr>
							<tr>	
								<th>Password</th>
								<td><?php echo $currentUser->password?></td>
							</tr>
							<tr>	
								<th>Email</th>
								<td><?php echo $currentUser->email?></td>
							</tr>
							<tr>
								<th>Mobile</th>
								<td><?php echo $currentUser->phone?></td>
							</tr>
							<tr>
								<th>User Type</th>
								<td><?php echo $currentUser->usertype?></td>
							</tr>
							<tr>
								<th>Board</th>
								<td><?php echo $currentUser->board?></td>
							</tr>
							<tr>
								<th>City</th>
								<td><?php echo $currentUser->cityname?></td>
							</tr>
							<tr>
								<th>State</th>
								<td><?php echo $currentUser->statename?></td>
							</tr>
							<tr>
								<th>Class</th>
								<td><?php echo $currentUser->classstd?></td>
							</tr>
							<tr>
								<th>Grouped School</th>
								<td><?php echo $currentUser->school?></td>
							</tr>
							<tr>
								<th>Main school</th>
								<td><?php echo $currentUser->main_school?></td>
							</tr>
							<tr>
								<th>Alias</th>
								<td><?php echo $currentUser->alias?></td>
							</tr>
							<tr>
								<th>Switch Permission</th>
								<td><?php if ($currentUser->switch_permission=="on") {echo "Yes";} else {echo "No";}?></td>
							</tr>
							<?php if ($currentUser->schoollogo!=''){	?>
							<tr>
								<th>Logo</th>
								<td><img src="images/<?php echo $currentUser->schoollogo;?>" width="100" height="100"> </td>
							</tr>
							<?php } ?>
						</thead>
				
					</table>
					<a href="user_edit-profile.php?id=<?php echo $currentUser->id;?>" class="sb_login-btn "><i class="fa fa-pencil-square-o" aria-hidden="true" style="margin-right:10px;"></i></span>Edit Profile</a>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>