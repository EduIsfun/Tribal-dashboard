<?php 
session_start();
if(empty($_SESSION['uid'])){
header('Location:index.php');
}
error_reporting(0);
include('db_config.php');
global $conn;

  $sql = "SELECT * FROM teacher_password WHERE email = '".$_SESSION['uid']."'";
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

<div class="container-fluid">

        <nav class="navbar navbar-default">
				<a href="teacherdashboard.php"><h2>Dashboard</h2></a>
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
<div class="container">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
			     
				<div class="top_head">
					<?php 
						if(isset($_GET['id']) && $_GET['id']=='Updated'){
						echo '<p style="color:green; margin-left:110px;margin-top:30px;"> Record has been updated successfully!</p>';
						}
					?>
						<h3 class="text-center">Admin Details</h3>
				</div>
			   <div class="table-responsive sb_border-shadow">
			   
			   <table class="table inner_tabel">
			  <form method="post" action="edit-profile.php">
				<thead>
				
				<tr>
					<th>Name</th> <td><?php echo ucfirst($currentUser->name)?></td>
				</tr>
				<tr>	
					<th>Email</th> <td><?php echo $currentUser->email?></td>
				</tr>
					<tr>
					<th>State</th> <td><?php echo $currentUser->state?></td>
				</tr>
				<tr>
					<th>City</th> <td><?php echo $currentUser->city?></td>
				</tr>
				<tr>
					<th>Mobile</th> <td> <?php echo $currentUser->mobile?></td>
				</tr>
				<tr>
					<th>Zipcode</th> <td><?php echo $currentUser->zipcode?></td>
				</tr>
				<tr>
					<th>Address</th> <td><?php echo $currentUser->address?></td>
				</tr>

				</thead>
				</form>
			  </table>
			 <a href="edit-profile.php" class="sb_login-btn "><i class="fa fa-pencil-square-o" aria-hidden="true" style="margin-right:10px;"></i></span>Edit Profile</a>
			</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
