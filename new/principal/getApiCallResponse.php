<?php 

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // echo "<pre>"; print_r($_REQUEST['url']); echo "</pre>"; die('end of code');
    if(isset($_REQUEST['url'])){
        $url = $_REQUEST['url'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "accept: application/json",
              "cache-control: no-cache",
              "content-type: application/json"
            ),
        ));

        $response = json_decode(curl_exec($curl),true);
        // echo "<pre>"; print_r($response); echo "</pre>"; die('end of code');
        $err = curl_error($curl);
        curl_close($curl);

        // reset($response);
        if($response){
            $data=reset($response);    
        }else{
            $data=array();    
        }
        echo json_encode($data);
    }
  
?>