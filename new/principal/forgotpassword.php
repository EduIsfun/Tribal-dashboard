<?php 
	session_start();
	include('db_config.php');
	
	
	function __autoload($classname){
      include("classes/$classname.class.php");
    }
	 $userdata = new student();
	 require("sendgrid-php/sendgrid-php.php");
	$sendgrid = new SendGrid("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");
	 include('config.php');
	   global $conn;
	   
		
	if(isset($_POST['submit']) AND $_POST['submit'] == 'SEND'){
		$userId=trim($_POST['username']);
		$result= $userdata->checkUserid($userId);
		$count=$result->num_rows;
			if($count<=0){
					$resultmobile= $userdata->checkUsermobile($userId);
					$countmobile=$resultmobile->num_rows;
					if($countmobile<=0){
						 $error='<p style="color:red">Invalid mobile number</p>';
					}
					else{
							$rowmobile=mysqli_fetch_object($resultmobile);
							$userIdget=$rowmobile->userID;
							$mobiluserid=$rowmobile->mobile;
							if($userId==$mobiluserid){
									$userinfo=$userdata->checkUserid($userIdget);
									$userpassword=mysqli_fetch_object($userinfo);
									// print_r($userpassword);
									// die();
										$name  =$rowmobile->name;
										$email = $rowmobile->gmailID;
										$number =  $rowmobile->mobile;
										
										$text='Dear '.$name.', you have have successfully changed your password. UserID/Mobile :'.$userIdget.'/'.$number.' , Password:'.$userpassword->password.'.';
										sendSms($number,$text);
										if(!empty($email)){
										$to = $email;
										$subject = "Success";
										$message = "<html></body>";
										$message .= "<strong>Username/Mobile : </strong>".$userIdget."/".$number."<br>";
										$message .= "<strong>password : </strong>".$userpassword->password."<br>";
										$message .= "</body></html>";
										
										$message.=$config['EMAIL_FOOTER'];				
										$mail = new PHPMailer;
										$mail->isSMTP();
										$mail->Host = ''.$config['HOST'].''; //Non secure
										$mail->Port = ''.$config['PORT'].'';//Non Secure
										$mail->SMTPAuth = true;
										$mail->Username = ''.$config['USERNAME'].'';
										$mail->Password = ''.$config['PASSWORD'].'';
										$mail->setFrom(''.$config['FROMEMAIL'].'');
										$mail->addAddress(''.$to.'');
										$mail->Subject = $subject;
										$mail->Body = $message;
										$mail->IsHTML(true); 
										$mail->smtpConnect([
												'ssl' => [
													'verify_peer' => false,
													'verify_peer_name' => false,
													'allow_self_signed' => true
												]
											]);
											
										if (!$mail->send()) {
												echo "Mailer Error: " . $mail->ErrorInfo;
										} else {
												$error='Reset password  has been sent on registered email and mobile number.';
										}
								}
								else {
										$error='Reset password  has been sent on registered email and mobile number.';
										}
							}
							else{
								$error='<p style="color:red">Invalid mobile number</p>';	
							}
						
						}
			}
			else{
				$rowuserid=mysqli_fetch_object($result); 
				$userid=$rowuserid->userID;
				$resultuser= $userdata->checkMobile($userId);
				$userinfo=mysqli_fetch_object($resultuser); 
				$mobile=isset($userinfo->mobile)?$userinfo->mobile:'';
				$email=isset($userinfo->gmailID)?$userinfo->gmailID:'';
				$userIdget=isset($userinfo->userID)?$userinfo->userID:'';
				$password=isset($rowuserid->password)?$rowuserid->password:'';
				if(!empty($mobile) && !empty($email)){
					$number=$mobile;
					$text='Dear Sir, you have have successfully changed your password. UserID/Mobile :'.$userIdget.'/'.$mobile.' , Password:'.$password.'.';
					sendSms($number,$text);
					$to = $email;
					$subject = "Success";
					$message = "<html></body>";
					$message .= "<strong>Username/Mobile : </strong>".$userIdget."/".$number."<br>";
					$message .= "<strong>Password : </strong>".$password."<br>";
					$message .= "</body></html>";
					$message.=$config['EMAIL_FOOTER'];				
					$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->Host = ''.$config['HOST'].''; //Non secure
					$mail->Port = ''.$config['PORT'].'';//Non Secure
					$mail->SMTPAuth = true;
					$mail->Username = ''.$config['USERNAME'].'';
					$mail->Password = ''.$config['PASSWORD'].'';
					$mail->setFrom(''.$config['FROMEMAIL'].'');
					$mail->addAddress(''.$to.'');
					$mail->Subject = $subject;
					$mail->Body = $message;
					$mail->IsHTML(true); 
					$mail->smtpConnect([
							'ssl' => [
								'verify_peer' => false,
								'verify_peer_name' => false,
								'allow_self_signed' => true
							]
						]);
					$mail->send();
				$error='<p style="color:green">New password has been sent on email and mobile number.</p>';
				}
				else if(!empty($mobile)){
					$number=$mobile;
					$text='Dear Sir, you have have successfully changed your password. UserID/Mobile :'.$userIdget.'/'.$mobile.' , Password:'.$password.'.';
					sendSms($number,$text);
					$error='<p style="color:green">New password has been sent on mobile number.</p>';
				}
				else if(!empty($email)){
					$number=$mobile;
					$to = $email;
					$subject = "Success";
					$message = "<html></body>";
					$message .= "<strong>Username/Mobile : </strong>".$userIdget."/".$number."<br>";
					$message .= "<strong>Password : </strong>".$password."<br>";
					$message .= "</body></html>";
					$message.=$config['EMAIL_FOOTER'];				
					$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->Host = ''.$config['HOST'].''; //Non secure
					$mail->Port = ''.$config['PORT'].'';//Non Secure
					$mail->SMTPAuth = true;
					$mail->Username = ''.$config['USERNAME'].'';
					$mail->Password = ''.$config['PASSWORD'].'';
					$mail->setFrom(''.$config['FROMEMAIL'].'');
					$mail->addAddress(''.$to.'');
					$mail->Subject = $subject;
					$mail->Body = $message;
					$mail->IsHTML(true); 
					$mail->smtpConnect([
							'ssl' => [
								'verify_peer' => false,
								'verify_peer_name' => false,
								'allow_self_signed' => true
							]
						]);
					$mail->send();
					$error='<p style="color:green">New password has been sent on email .</p>';
				}
				else{
					$error='<p style="color:red">Sorry we are unable to send password.</p>';	
				}
			}
			
		
   }
	

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>EduisFun</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<ul class="nav navbar-nav sb_navbar_top" id="navPrincipal">          

				<li><a href="login.php"><span><img src="images/logo.png"></span></a></li>

			</ul>
		</div>
	</div>
</div>
<div class="sb_login">
	<div class="container">
		<div class="row">
		
			<div class="col-md-5 col-md-offset-3">
			<form method="post">
				<div class="login text-center">
				<div><?=isset($error)?$error:''?></div>
				<img src="images/logo.png" alt="">
					
						<br><input type="text" name="username"  class="sb_input-form" placeholder="Mobile" required="required" />
						<!--<input type="password" name="p" class="sb_input-form"placeholder="New password" required="required" />
						<input type="password" name="p" class="sb_input-form"placeholder="Confirm password" required="required" />-->
						
					</form>
					<input type="submit" name="submit" value="SEND" class="btn btn-primary sb_login-btn">		
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
