<?php 
include('functions.php');
// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; die('end of code');

    $columns = array( 
        0 =>'id', 
        1 =>'fullname',
        2=> 'user_id',
        3=> 'grade',
        4=> 'time_spend',
        5=> 'rank',
    );
    $post_data = $_POST; 
    // echo "<pre>"; print_r($post_data); echo "</pre>"; die('end of code');
    $limit = $post_data['length'];
    $start = $post_data['start'];
    $school_name = isset($post_data['school_id'])?$post_data['school_id']:'';
    $board_id = isset($post_data['board_id'])?$post_data['board_id']:'';
    $classid = isset($post_data['classid'])?$post_data['classid']:'';
    $page_no = ($start/10)+1;
    // echo "<pre>"; print_r($page_no); echo "</pre>"; die('end of code');
    $order = $columns[$post_data['order'][0]['column']];
    $dir = $post_data['order'][0]['dir'];
    $page_count = 1;
    if($start>0){
        $page_count=$start+1;    
    }
    // echo "<pre>"; print_r($page_count); echo "</pre>"; die('end of code');
    if(empty($post_data['search']['value'])){
        $url_condition = '';
        if($classid != 'all'){
            $url_condition = '&gradeId='.Romannumeraltonumber($classid);
        }
        // $url = "https://0e3r24lsu5.execute-api.ap-south-1.amazonaws.com/Prod/dummyapi?schoolId=".$school_name."&page=".$page_count;
        $url = "https://0e3r24lsu5.execute-api.ap-south-1.amazonaws.com/Prod/tribalhomepageapi?schoolId=".$school_name."&page=".$page_no.$url_condition;
        // echo "<pre>"; print_r($url); echo "</pre>"; die('end of code');
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

        if(!empty($response['school']['user'])){
        	$user_array = $response['school']['user'];
        }else{
        	$user_array = array();
        }
        // echo "<pre>"; print_r($user_array); echo "</pre>"; die('end of code api_response');
        $totalData = $response['school']['total_count'];
        $totalFiltered = $totalData;
        // echo "<pre>"; print_r($posts); echo "</pre>"; die("end of posts yoyo");
    }else {
        $search = $post_data['search']['value']; 
        // $posts =  $this->Student_Model->studentGradeSearch($limit,$start,$search,$order,$dir);
        // $totalFiltered = $this->Student_Model->studentGradeSearchCount($search);
    }

    $data = array();
    if(!empty($user_array)){    
        foreach ($user_array as $user){
            // echo "<pre>"; print_r($user); echo "</pre>"; die('end of code');
            $nestedData['id'] = $page_count;
            $nestedData['fullname'] = '<span class="span_inline" style="color:#333;font-size:14px;"> <img src="images/green.png" alt="icon"> &nbsp; &nbsp; <a href="edufun.php?id='.$user['user_id'].'" target="_blank">'.$user['name'].'  </a></span>';  
            $nestedData['class'] = '<span>'.$user['class'].'</span>';
            $nestedData['grade'] = (isset($user['grade']))?'<span>'.$user['grade'].'</span>':'E2';
            $nestedData['learning_score'] = '<span>'.$user['learning_score'].'</span>';
            $nestedData['time_spend'] = '<div class="dark"><span> <ul class="time-inline">'.date('H:i:s', $user['time_spend']).'</ul></span></div>';
            $nestedData['rank'] = '<span>'.$user['emrs_rank'].'</span>';
            // echo "<pre>"; print_r($nestedData); echo "</pre>"; die('end of code');
            $data[] = $nestedData;
        $page_count++;
        }
    }
    // echo "<pre>"; print_r($data); echo "</pre>"; die('end of code');
    $json_data = array(
                "draw"            => intval($post_data['draw']),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data   
                );
        
    echo json_encode($json_data);

?>