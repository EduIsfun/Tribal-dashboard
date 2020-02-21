<?php
	session_start();
	//session_unset();
	include('db_config.php');
	  function __autoload($classname){
      include("classes/$classname.class.php");
    }
	$userdata = new student();
	$currentUsers  = $_SESSION['currentinfo'];
	include('config.php');
	
if(isset($_POST['submit']) AND ($_POST['submit'] == 'SEND OTP' || $_POST['submit'] == 'RESEND' )){
	
		$mobile=$number=trim($_POST['mobile']);
		$otp=rand(1, 1000000); 
		$text='Dear user, your OTP is '.$otp.'. Please use this to verify your mobile number.';
		$getotp=$userdata ->getOtp($mobile);
		$countotp=$getotp->num_rows;
			if($countotp<=0){
					sendSms($number,$text);
					$data=$userdata ->addOtp($otp,$mobile);
					
					if($data){
						$error='<p style="color:green">OTP has been sent</p>';
					}
					else{
						$error='<p style="color:red">Error to send OTP</p>';
					}
				}
			else{
					sendSms($number,$text);
					$data=$userdata ->updateOtp($otp,$mobile);
									
						if($data){
							$error='<p style="color:green">OTP has been sent</p>';
						}
						else{
							$error='<p style="color:red">Error to send OTP</p>';
						}
			}
		
	}
	
	if(isset($_POST['submit']) AND $_POST['submit'] == 'VERIFY'){
	
		$mobile=$number=trim($_POST['mobile']);
		$otp=trim($_POST['otp']); 
		$getotp=$userdata ->getOtpstatus($mobile,$otp);
		$countotp=$getotp->num_rows;
			if($countotp<=0){
					$error='<p style="color:red">Invalid OTP</p>';
				}
			else{
				$userid=$currentUsers['userid'];
				$updamobile=$userdata ->updateMobile($mobile,$userid);
				$userdata ->updateOtpstatus($otp,$mobile);
				if($updamobile){
								unset($_SESSION['currentinfo']);
								$uid=$currentUsers['userid'];
								$resultuser= $userdata->getUserinfo($uid);
								$row =mysqli_fetch_array($resultuser);
								$_SESSION['currentinfo']= $row;
								echo "<script>window.open('userlist.php','_self')</script>";
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
						<input type="text" name="mobile" value="<?php if(isset($_POST['mobile'])) { echo $_POST['mobile'];} ?>" class="sb_input-form" placeholder="Username/Mobile" required="required" />
						<?php if(isset($_POST['mobile'])){?>
							
							<input type="text" name="otp" class="sb_input-form" placeholder="OTP"  />
						
						<?php }?>
					
							<div class="Sb_right">
							<p class="omb_forgotPwd">
							<a href="index.php">Login</a>
							</p>
							</div>
						<input type="submit" name="submit" value="<?php if(isset($_POST['mobile'])) { echo 'RESEND';} else{ echo 'SEND OTP';} ?>" class="btn btn-primary sb_login-btn">
						<?php 
						if(isset($_POST['mobile'])){?>
							
							<input type="submit" name="submit" value="VERIFY" class="btn btn-primary sb_login-btn">
					
						<?php }?>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
