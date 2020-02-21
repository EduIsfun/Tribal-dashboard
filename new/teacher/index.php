<?php
	 session_start();
 include('../db_config.php');
 

 if(isset($_POST['submit']))
  {
	  $username=$_POST['username'];
	  $password =$_POST['password']; 
	 
	  $select="select * from teacher_password WHERE email ='$username' AND password= '$password'";
	  $query=mysqli_query($conn,$select);
      if(mysqli_num_rows($query)>0){
     $_SESSION['uid']=$username;
	 echo "<script>window.open('teacherdashboard.php','_self')</script>";
   }else 
	  {
		 echo "<script>alert('username or password invaild')</script>";
 } 
   }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>EduFun</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="css/style1.css" rel="stylesheet">
</head>
<body>
<div class="sb_login">
	<div class="container">
		<div class="row">
			<div class="col-md-5 col-md-offset-4">
				<div class="login text-center">
				<img src="images/logo.png" alt="">
					<form method="post">
						<input type="text" name="username"  class="sb_input-form" placeholder="Username" required="required" />
						<input type="password" name="password" class="sb_input-form"placeholder="Password" required="required" />
						
					
						<!--<div class="Sb_left">
							<label class="checkbox">
							<input type="checkbox" value="remember-me">Remember Me
							</label>
						</div>-->
							<div class="Sb_right">
							<p class="omb_forgotPwd">
							<!--<a href="forgotpassword.php">Forgot password?</a>-->
							</p>
							</div>
						<input type="submit" name="submit" value="Login" class="btn btn-primary sb_login-btn">
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
