<?php

	session_start();
	//session_unset();
	include('db_config.php');
	  function __autoload($classname){
      include("classes/$classname.class.php");
    }
	 $userdata = new student();
	 
	if(isset($_POST['login']))
	{
		$userId=trim($_POST['username']);
		$password =trim($_POST['password']);
		
		$result= $userdata->checkUserid($userId);
		$count=$result->num_rows;
			if($count<=0){
					$resultmobile= $userdata->checkUsermobile($userId);
					$countmobile=$resultmobile->num_rows;
					if($countmobile<=0){
						 $error='<p style="color:red">Invalid mobile number</p>';
					}
					else{
						// for($i=0;$i<=$countmobile;$i++){
							// echo 'TEST';
							// echo '<br>';
						// }
						// die();
						while($rowmobile=mysqli_fetch_object($resultmobile)){
							$mobiluserid=$rowmobile->userID;
							$resultuser= $userdata->checkLogin($mobiluserid,$password);
							$countuser=$resultuser->num_rows;
							
							if($countuser>0){
							$uid=$mobiluserid;
							$resultuser= $userdata->getUserinfo($uid);
							$row =mysqli_fetch_array($resultuser);
							$_SESSION['currentinfo'] = $row;
								$error='<p style="color:red">Password matched</p>';
							 	echo "<script>window.open('userlist.php','_self')</script>";
							}
						
							else{
								$error='<p style="color:red">Invalid password</p>';
							}
						}
						
						
					}
			}
			else{
				$rowuserid=mysqli_fetch_object($result); 
				$userid=$rowuserid->userID;
				$resultuser= $userdata->checkLogin($userid,$password);
				$countuser=$resultuser->num_rows;
				if($countuser<=0){
					 $error='<p style="color:red">Invalid password</p>';
				}
				else{
					$userId=$rowuserid->userID;
					$resultmobile= $userdata->checkMobile($userId);
					$countmobile=$resultmobile->num_rows;
					if($countmobile<=0){
						
						 $error='<p style="color:red">Invalid username</p>';
					}
					else{
						$mobiledata=mysqli_fetch_object($resultmobile);
						$getmobile=isset($mobiledata->mobile)?$mobiledata->mobile:'';
							if(!empty($getmobile)){
								$uid=$userId;
								$resultuser= $userdata->getUserinfo($uid);
								$row =mysqli_fetch_array($resultuser);
								$_SESSION['currentinfouserid']= $uid;
								echo "<script>window.open('userlist.php','_self')</script>";
							}
							
							else{
								$uid=$userId;
								$resultuser= $userdata->getUserinfo($uid);
								$row =mysqli_fetch_array($resultuser);
								$_SESSION['currentinfo']= $row;
								echo "<script>window.open('verify.php','_self')</script>";
							}
						
					}	
					
				}
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
  <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="sb_login">
	<div class="container">
		<div class="row">
			<div class="col-md-5 col-md-offset-4">
				<div class="login text-center">
				<span><?php if(!empty($error)){ echo $error;} ?></span>
				<img src="images/logo.png" alt="">
					<form method="post">
						<input type="text" name="username"  class="sb_input-form" placeholder="Username/Mobile" required="required" />
						<input type="password" name="password" class="sb_input-form"placeholder="Password" required="required" />
						
					
						<!--<div class="Sb_left">
							<label class="checkbox">
							<input type="checkbox" value="remember-me">Remember Me
							</label>
						</div>-->
							<div class="Sb_right">
							<p class="omb_forgotPwd">
							<a href="forgotpassword.php">Forgot password?</a>
							</p>
							</div>
						<input type="submit" name="login" value="Login" class="btn btn-primary sb_login-btn">
						
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
