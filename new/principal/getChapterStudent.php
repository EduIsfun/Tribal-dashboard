<?php 

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
    $school_name = isset($post_data['school_name'])?$post_data['school_name']:'';
    $chapter_id = isset($post_data['chapter_id'])?$post_data['chapter_id']:'';
    $page_no = ($start/10)+1;
    // echo "<pre>"; print_r($page_no); echo "</pre>"; die('end of code');
    $order = $columns[$post_data['order'][0]['column']];
    $dir = $post_data['order'][0]['dir'];
    $page_count = 1;
    if($start>0){
        $page_count=$start+1;    
    }
    if(empty($post_data['search']['value'])){
        $url = "https://0e3r24lsu5.execute-api.ap-south-1.amazonaws.com/Prod/tribalchapterapi?schoolId=EMRS_Shendegaon&page=".$page_no."&chapterId=6TS0001";
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
        $user_array = isset($response['chapter']['Overall_score'])?$response['chapter']['Overall_score']:array();
        // echo "<pre>"; print_r($response['chapter']['Overall_score']); echo "</pre>"; die('end of code');
        $err = curl_error($curl);
        curl_close($curl);
        $totalData = 100; // need total count from api
        $totalFiltered = $totalData;
        // echo "<pre>"; print_r($posts); echo "</pre>"; die("end of posts yoyo");
    }else {
        $search = $post_data['search']['value']; 
        // $posts =  $this->Student_Model->studentGradeSearch($limit,$start,$search,$order,$dir);
        // $totalFiltered = $this->Student_Model->studentGradeSearchCount($search);
    }

$data = array();
if(!empty($user_array)){
    $new_column_array = ['id','fullname'];    
    foreach ($user_array as $user){
        // echo "<pre>"; print_r($user); echo "</pre>"; die('end of code');
        $nestedData['id'] = $page_count;
        $nestedData['fullname'] = '<span class="span_inline" style="color:#333;font-size:14px;"> <img src="images/green.png" alt="icon"> &nbsp; &nbsp; <a href="#" target="_blank">'.$user['name'].'  </a></span>';  
        $nestedData['overall_score'] = '<span>'.round($user['overall_score']).'</span>';
        foreach ($user['score'] as $key => $value) {
            $nestedData[$value['chapter_name']]=$value['grade'];
            array_push($new_column_array, $value['chapter_name']);
        }
        // echo "<pre>"; print_r($nestedData); echo "</pre>"; die('end of code');
        $data[] = $nestedData;
    $page_count++;
    }
    $final_column_array = array();
    foreach (array_unique($new_column_array) as $key => $value) {
        $final_column_array[$key]['title'] = $value;
        $final_column_array[$key]['data'] =  $value;
    }
    // echo "<pre>"; print_r($final_column_array); echo "</pre>"; die('end of code');
}
// echo "<pre>"; print_r($data); echo "</pre>"; die('end of code');
$json_data = array(
            "draw"            => intval($post_data['draw']),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data,
            "columns_head"         => array_unique($new_column_array),   
            "columns"         => $final_column_array   
            );
    
echo json_encode($json_data);

?>