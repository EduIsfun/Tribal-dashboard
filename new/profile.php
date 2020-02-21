<?php 

	session_start();
	include('db_config.php');

	if(empty($_SESSION['currentusernew'])){
		header('Location:index.php');
	}
	$currentUser  =$_SESSION['currentusernew'];	
	
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>EduisFun</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/form.css">

</head>
<body>
<div class="top_background">
<div class="container-fluid">

        <nav class="navbar navbar-default nav_height">
            
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
					<a href="edufun.php"><img src="images/logo.png"></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse Sb-navbar_padd ">
                    <ul class="nav navbar-nav sb_navbar_top" id="navPrincipal">
                       <!-- <li><a href="dashboard.php"><span><img src="images/left_arrow.png"></span>go back</a></li>-->
                      
                       <li><a href="edufun.php"><span><img src="images/home.png"></span>Dashboard</a></li>
                       
                        <li><a href="profile.php"><span><img src="images/profile.png" ></span>PROFILE</a></li>
                        <li><a href="change.php"><span><img src="images/setting.png"></span>Change Password</a></li>
						<li><a href="logout.php"><i class="fa fa-power-off" aria-hidden="true" style="margin-right:20px;"></i>Logout</a></li>
                     
                    </ul>
                </div>
            
        </nav>
		</div>
</div>
  <?php 
        if(isset($_GET['id']) && $_GET['id']=='Updated'){
			echo '<p style="color:green; margin-left:110px;margin-top:30px;"> Record has been updated successfully!</p>';
		}

 ?>
<div class="container">


	<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="top_head">
		<h3>My User account</h3>
		</div>
		<div class="table-responsive sb_border-shadow">		
			<table class="table inner_tabel">
				<form method="post" action="edit-profile.php">
				
				<tr> 
					<td>Name</td><td><?php echo $currentUser['name']; ?></td>
				</tr>
				<tr>
					<td>Email</td><td><?php echo $currentUser['gmailID']; ?></td>
				</tr>
				<tr>
					<td>City</td><td><?php echo $currentUser['city']; ?></td>
				</tr>
				<tr>
					<td>Mobile</td><td><?php echo $currentUser['mobile']; ?></td>
				</tr>
				<tr>
					<td>Country</td><td><?php echo $currentUser['country']; ?></td>
				</tr>
			
				
				
				</form>
			</table>
	 
                     <a href="edit-profile.php" class="sb_login-btn"><i class="fa fa-pencil-square-o" aria-hidden="true" style="margin-right:10px;"></i></span>Edit Profile</a>
          
		</div>

		</div>

	</div>

</div>


  <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
