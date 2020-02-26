<?php 

// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; die('end of code');

    // $columns = array( 
    //     0 =>'id', 
    //     1 =>'fullname',
    //     2=> 'user_id',
    //     3=> 'grade',
    //     4=> 'time_spend',
    //     5=> 'rank',
    // );
    $post_data = $_POST; 
    // echo "<pre>"; print_r($post_data); echo "</pre>"; die('end of code');
    $limit = isset($post_data['length'])?$post_data['length']:'';
    $start = isset($post_data['start'])?$post_data['start']:0;
    $school_name = isset($post_data['school_name'])?$post_data['school_name']:'';
    $school_name = str_replace(',','',$school_name);
    $school_name = str_replace(' ','_',$school_name);
    $chapter_id = isset($post_data['chapter_id'])?$post_data['chapter_id']:'';
    $page_no = ($start/10)+1;
    // echo "<pre>"; print_r($school_name); echo "</pre>"; die('end of code');
    // $order = $columns[$post_data['order'][0]['column']];
    // $dir = $post_data['order'][0]['dir'];
    $page_count = 1;
    if($start>0){
        $page_count=$start+1;    
    }
    if(empty($post_data['search']['value'])){
        $url = "https://0e3r24lsu5.execute-api.ap-south-1.amazonaws.com/Prod/tribalchapterapi?schoolId=".$school_name."&page=".$page_no."&chapterId=".$chapter_id;
        // $url = 'https://0e3r24lsu5.execute-api.ap-south-1.amazonaws.com/Prod/tribalchapterapi?schoolId=EMRS_Shendegaon&page=1&chapterId=6TS0001';
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
        $user_array = isset($response['chapter']['Overall_score'])?$response['chapter']['Overall_score']:array();
        $topics_list = $response['chapter']['topics'];
        // echo "<pre>"; print_r($topics_list); echo "</pre>"; die('end of code');
        $err = curl_error($curl);
        curl_close($curl);
        $totalData = $response['chapter']['total_count']; // need total count from api
        $totalFiltered = $totalData;
        // echo "<pre>"; print_r($posts); echo "</pre>"; die("end of posts yoyo");
    }else {
        $search = $post_data['search']['value']; 
        // $posts =  $this->Student_Model->studentGradeSearch($limit,$start,$search,$order,$dir);
        // $totalFiltered = $this->Student_Model->studentGradeSearchCount($search);
    }

    $data = array();
    if(!empty($user_array)){
        // $new_column_array = ['id','fullname','overall_score','overall_grade'];
        $new_column_array = ['id','fullname'];
        $button_color_array = array('A1'=>'greenr12','A2'=>'greenr2','B1'=>'yello1','B2'=>'yello2','C1'=>'oran1','C2'=>'oran2','D'=>'blue2','E1'=>'red','E2'=>'red1');    
        foreach ($user_array as $user){
            // echo "<pre>"; print_r($user); echo "</pre>"; die('end of code');
            $nestedData['id'] = $page_count;
            $nestedData['fullname'] = '<span class="span_inline" style="color:#333;font-size:14px;"> <img src="images/green.png" alt="icon"> &nbsp; &nbsp; <a href="#" target="_blank">'.$user['name'].'  </a></span>';  
            // foreach ($user['score'] as $key => $value) {
            //     $nestedData[$value['chapter_name']]='<span class="'.$button_color_array[$value['grade']].'">'. $value['grade'].'</span>';
            //     array_push($new_column_array, $value['chapter_name']);
            // }
            // echo "<pre>"; print_r($topics_list); echo "</pre>"; die('end of code');
            foreach ($topics_list as $key => $value) {
                // echo "<pre>"; print_r($value); echo "</pre>"; die('end of code');
                $is_match = 0;
                foreach ($user['score'] as $ukey => $uvalue) {
                    if(trim($value['name']) == trim($uvalue['chapter_name'])){
                        $is_match = 1;
                        $nestedData[strtoupper($value['node'])]='<span class="'.$button_color_array[$uvalue['grade']].'">'.$uvalue['grade'].'</span>';       
                    }
                }
                if(!$is_match){
                    $nestedData[strtoupper($value['node'])]='<span class="grey2">NA</span>';
                }
                array_push($new_column_array, strtoupper($value['node']));
            }
            $nestedData['Treasure'] = '<span class="'.$button_color_array[$user['treasure_grade']].'">'.$user['treasure_grade'].'</span>';
            $nestedData['Overall'] = '<span class="'.$button_color_array[$user['overall_grade']].'">'.$user['overall_grade'].'</span>';
            // echo "<pre>"; print_r($nestedData); echo "</pre>"; die('end of code');
            $data[] = $nestedData;
        $page_count++;
        }
        $final_column_array = array();
        $i=0;
        array_push($new_column_array, 'Treasure');
        array_push($new_column_array, 'Overall');
        foreach (array_unique($new_column_array) as $key => $value) {
            $final_column_array[$i]['title'] = $value;
            $final_column_array[$i]['data'] =  $value;
            $i++;
        }
        // echo "<pre>"; print_r($final_column_array); echo "</pre>"; die('end of code');
    }
    // echo "<pre>"; print_r($final_column_array); echo "</pre>"; die('end of code');
    $json_data = array(
                // "draw"            => intval($post_data['draw']),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data,
                // "columns_head"    => array_unique($new_column_array),   
                // "topics_list"     => $topics_list,   
                "columns"         => $final_column_array,   
                );
        
    echo json_encode($json_data);

?>