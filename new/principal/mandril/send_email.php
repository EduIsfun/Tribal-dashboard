<?php
 include ("lib/Mandrill.php");
	
	function sendEmail($api_key,$form_name,$messages,$to_email,$subject,$form_email,$adminemail){
		$mandrill = new Mandrill($api_key);
		$str='ZXhhbXBsZSBmaWxl';
		$data=base64_encode($str);	
        $message = array();
        $to[] = array(
            'email' =>$to_email,
            'name' => 'ikan', 
            'type' => 'to' 
        );
		$to[] = array(
            'email' =>$adminemail,
            'name' => 'ikan', 
            'type' => 'cc' 
        );
		$message['subject'] = $subject;
        $message['html'] = $messages;
        $message['from_email'] =$form_email;
        $message['from_name'] =$form_name;
        $message['to'] = $to;
		$result = $mandrill->messages->send($message);	
		return $result;
		
	}
	
?>
