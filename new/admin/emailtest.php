<?php
	require ("PHPMailer-master/PHPMailerAutoload.php");
	include("connection.php");
	global $config;
				$to ='rajnishsitamarhi@gmail.com';
				$subject = 'TEST EMAIL FROM SMTP'; 
				$message=$config['EMAIL_HEADER'];
				$message.='Hello Sir,<br><br>
				This is SMTP email for testing, once you get this email confirmed me on rajnishsitamarhi@gmail.com.<br><br>
				Thanks & Regard<br>Rajnish Kumar<br><br>';
				$message.=$config['EMAIL_FOOTER'];
				$too   = $email; 
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->Host = ''.$config['HOST'].''; //Non secure
				$mail->Port = ''.$config['PORT'].'';//Non Secure
				$mail->SMTPAuth = true;
				$mail->Username = ''.$config['USERNAME'].'';
				$mail->Password = ''.$config['PASSWORD'].'';
				$mail->setFrom(''.$config['FROMEMAIL'].'');
				$mail->addAddress(''.$to.'');
				//$mail->AddBCC($config['SUPPORT_MAIL'], "TEST MAIL");
				$mail->Subject = $subject;
				$mail->Body = $message;
				$mail->IsHTML(true); 
			if (!$mail->send()) {
							
					echo $email_logs=$mail->ErrorInfo;
							
						  
					} 
				else {
				echo $email_logs='Message sent';
				
				
			}
			
?>