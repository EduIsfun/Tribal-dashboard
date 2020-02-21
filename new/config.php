<?php
global $config;

//SMTP detail
$config['WEBHOST']	= 'http://eduisfun.in/dashboard/new/'; 
$config['HOST']	= 'mail.etcsndt.com'; 
$config['USERNAME'] = 'phpsmtp@etcsndt.com';
$config['PASSWORD'] = '8q13kahynX3q';
$config['PORT'] = '25';
$config['FROMEMAIL']= 'support@eduisfun.com';
$config['ADMIN']='support@eduisfun.com';
	
								
function sendSms($number,$text)
{
global $config;	
$user="pyin-eduisfun"; //your username
	
$password="98765"; //your password   
$senderid="EDUFUN"; //Your senderid 
 $url="http://103.16.101.52/sendsms/bulksms?";   
 $message = urlencode($text);
 $ch = curl_init();
 if (!$ch){die("Couldn't initialize a cURL handle");}
 $ret = curl_setopt($ch, CURLOPT_URL,$url);
 curl_setopt($ch, CURLOPT_POST, 1);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
 curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$user&password=$password&type=0&dlr=1&destination=$number&source=$senderid&message=$message");
 $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;   
 $result = curl_exec($ch);
 //print_r($result);
 curl_close($ch);
$curlresponse=$result;

   
 
}
$config['EMAIL_HEADER']='<html xmlns="http://www.w3.org/1999/xhtml"><head>                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />                <title>EDUISFUN</title>                </head>				<body style="color:#69625B;background-color:#f9f9f9;border:#333333; margin-left:50px; padding:0; margin:0; padding:0; font-family: Source Sans Pro,Helvetica,Arial,sans-serif;">               <br>';				  $config['EMAIL_FOOTER']=' 				<div>				<p>				Thank you <br/>				Team EduisFun<br/>				support@eduisfun.com   <br/> 				http://eduisfun.in/dashboard/new<br/>     				</p>							</div>				<div style="margin:auto;width:none;text-align:center; background-color:#27272E; padding:1px;">                </div>				</body>                </html>';								
				

?>