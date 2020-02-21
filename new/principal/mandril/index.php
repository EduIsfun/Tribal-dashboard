<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <title>Send Email via  Mandrill API Using PHP</title>      
    </head>
    <body>
        <?php
		global $config;
        include ("lib/Mandrill.php");
        $mandrill = new Mandrill('bcWowVfYFDUZLx1dEaYIjQ');
       
        $box_msg = ''.$config['EMAIL_HEADER'].'
			<div style="width:80%; margin:auto;  font-size:15px; padding:10px; background-color:#EEE;">
			<p>Dear rajnish,<br><br>
			You have applied for Delhi-NCR Super 50 Exam. Your transaction has been successfully completed. Please find your transaction details - <p>
			<table  style="width:100%;  border-collapse: collapse; background-color:#FFF; ">
			<tr style=" border-bottom: 1px solid #D0D0D0;">
			<td style=" width:40%; border-bottom: 1px solid #D0D0D0; padding:5px"> Name </td>
			<td style=" border-bottom: 1px solid #D0D0D0; padding:5px"> rajnish </td>
			</tr>
			<tr style=" border-bottom: 1px solid #D0D0D0;">
			<td style=" width:40%; border-bottom: 1px solid #D0D0D0; padding:5px">Order Date </td>
			<td style=" border-bottom: 1px solid #D0D0D0; padding:5px"> 5885 </td>
			</tr>
			<tr style=" border-bottom: 1px solid #D0D0D0;">
			<td style=" width:40%; border-bottom: 1px solid #D0D0D0; padding:5px">Total Amount </td>
			<td style=" border-bottom: 1px solid #D0D0D0; padding:5px"> INR 500 </td>
			</tr>
			<tr style=" border-bottom: 1px solid #D0D0D0;">
			<td style=" width:40%; border-bottom: 1px solid #D0D0D0; padding:5px">Transaction Number </td>
			<td style=" border-bottom: 1px solid #D0D0D0; padding:5px">9589855965ssss </td>
			</tr>
			<tr style=" border-bottom: 1px solid #D0D0D0;">
			<td style=" border-bottom: 1px solid #D0D0D0; padding:5px"> Email </td>
			<td style=" border-bottom: 1px solid #D0D0D0; padding:5px;"><a href="#" style="text-decoration:none;"> rajnishsitamarhi@gmail.com </a></td>
			</tr>
			<tr style=" border-bottom: 1px solid #D0D0D0;">
			<td style=" border-bottom: 1px solid #D0D0D0; padding:5px ; width:40%;" > Phone Number </td>
			<td style=" border-bottom: 1px solid #D0D0D0; padding:5px"> 8745937175 </td>
			</tr>
			</table><br><br>
			
			'.$config['EMAIL_FOOTER'].'';
			
        $message = array();
        $to = array();
		$to[] = array(
            'email' =>'rajnishsitamarhi@gmail.com',
            'name' => 'rajnish', 
            'type' => 'to' 
        );
		
		
		
        $message['subject'] = 'test email';
        $message['html'] = $box_msg;
        $message['from_email'] ='support@lotustelco.Net';
        $message['from_name'] ='PACE SUPER50';
        $message['to'] = $to;
		
		
        $result = $mandrill->messages->send($message);		
        print_r($result);
       
        ?>  

            <div id="note">
                
            </div>
        </div>
      
    </body>
</html>


