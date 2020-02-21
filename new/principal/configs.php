<?php
global $config;
date_default_timezone_set("Asia/Calcutta");
$config['salt']='eCwWELxi';//live salt
$config['key']='gtKFFx';//live key

$config['WEB']['HOST']	= "http://".$_SERVER['SERVER_NAME'];// test
// echo "<pre>"; print_r($_SERVER['SERVER_NAME']); echo "</pre>"; die('end of code');
 if ($_SERVER['SERVER_NAME']=="localhost"){
	$config['server'] = "http://eduisfun.in/dashboard/new/principal/" ;
	$config['server'] = "http://localhost/Tribal-dashboard/new/principal/" ;
	
	// $config['WEB_DB']['HOST'] = '148.66.145.137'; 
    // $config['WEB_DB']['USERNAME'] = 'cross_developer';
    // $config['WEB_DB']['PASSWORD'] = 'qowiPT0ITLrI';
    // $config['WEB_DB']['DATABASE'] = 'ikansuper30';
	
    $config['HOST'] = 'smtpout.asia.secureserver.net'; 
	$config['PORT'] = 80;
	$config['USERNAME'] ='phpmail@pacesuper50.com';
	$config['PASSWORD'] = 'g1IazMuC2wIX';
	$config['ADMIN']='noreply@eduisfun.com';//test
	$config['FROMEMAIL']='sarajane1216110021@gmail.com';
	$config['cc']='sarajane1216110021@gmail.com';
	$config['APIKEY']='FtHrNkvo9FXb_5WCXYtTFQ';
	$config['ROOTPATH']=$_SERVER["DOCUMENT_ROOT"].'/';
		
}else{
	$config['server'] = "http://etcsndt.com/edufunhtmldashboard/";
	
	
    $config['HOST'] = 'mail.etcsndt.com'; 
	$config['PORT'] =25;
	$config['USERNAME'] ='phpsmtp@etcsndt.com';
	$config['PASSWORD'] = '8q13kahynX3q';
	$config['FROMEMAIL']= 'noreply@eduisfun.com';
	$config['ADMIN']='sarajane1216110021@gmail.com';
	$config['cc']='sarajane1216110021@gmail.com';
    $config['APIKEY']='FtHrNkvo9FXb_5WCXYtTFQ';
	$config['ROOTPATH']=$_SERVER["DOCUMENT_ROOT"].'/eduisfundashboard/';
}
function sendMail($emailID, $subject, $text) {
        if ($emailID == "") return;
        global $sendgrid;
        $email = new SendGrid\Email();
        $email->addTo($emailID)
        ->addCc("sarajane1216110021@gmail.com")
        ->addCc("sarajane1216110021@gmail.com")
        ->setFrom("support@eduisfun.com")
        ->setSubject($subject)
        ->setHtml($text);
        try {
            $sendgrid->send($email);
            return true;
        }
        catch (SendGrid\Exception $sge) {
            error_log("SendGrid Error: " . $sge);
            return false;
        }
    }

 



/* live server database */
$config['EMAIL_HEADER']='<html xmlns="http://www.w3.org/1999/xhtml"><head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>Eduidfun</title>
                </head>
					
				
<body style="font-family: Roboto, sans-serif;">
<div style="width:100%;background-color:#fff;padding:0px;">
      <div style="margin:0 auto;max-width:580px;padding:0px">
        <table width="100%" style="border-spacing:0;border-bottom:1px solid #ddd">
          <tbody>
            <tr>
              <td style="padding: 5px;background:linear-gradient(to right,#6399b6,#8ab9c7);" align="center">
                <div >
                  <a href="#" target="_blank">
				  <img style="width: 100px;" src="'.$config['server'].'images/logo.png" alt="Eduisfun" />
				  </a>
                </div>
              </td>
            </tr>
          </tbody>
        
		
		
			</table>';
         $config['EMAIL_FOOTER']='
		 <p style=" background: linear-gradient(to right, #12183d, #2d638e, #12183d);color: #fff;margin: 0;padding: 10px;">Regards,<br>eduisfun</p>
			
			 
     
		</div>
		</div>
</body>
                </html>';
				
function sendSms($mobilenumbers,$mess)
{ 

$uid="pyin-paceiit"; //your username
$pin="98765"; //your api pin
$sender="PSUPER"; // approved sender id
$domain="http://103.16.101.52/sendsms/bulksms?"; // connecting url 
$route="13";// 0-Normal,1-Priority
$method="POST";
	
	$mobile='91'.$mobilenumbers;
	$message=$mess;

	$uid=urlencode($uid);
	$pin=urlencode($pin);
	$sender=urlencode($sender);
	$message=urlencode($message);
	
	$parameters="uid=$uid&pin=$pin&route=$route&mobile=$mobile&message=$message ";

	$url="http://$domain/api/sms.php";

	$ch = curl_init($url);

	if($method=="POST")
	{
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);
	}
	else
	{
		$get_url=$url."?".$parameters;

		curl_setopt($ch, CURLOPT_POST,0);
		curl_setopt($ch, CURLOPT_URL, $get_url);
	}

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); 
	curl_setopt($ch, CURLOPT_HEADER,0);  // DO NOT RETURN HTTP HEADERS 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  // RETURN THE CONTENTS OF THE CALL
	$return_val = curl_exec($ch);


	if($return_val==""){
	$success="Process Failed, Please check your connecting domain, username or password.";
	}
	else{
	
	$success="Message Id : $return_val"; //Returning the message id  :  Whenever you are sending an SMS our system will give a message id for each numbers, which can be saved into your database and in future by calling these message id's using correct API's you can update the delivery status.
	}
	return  $success;
}		
				
?>