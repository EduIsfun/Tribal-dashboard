<?php
	$post_data = $_POST; 
    // echo "<pre>"; print_r($post_data); echo "</pre>"; die('end of code');
    $limit = isset($post_data['length'])?$post_data['length']:'';
    $start = isset($post_data['start'])?$post_data['start']:0;
    $school_name = isset($post_data['school_name'])?$post_data['school_name']:'';
    $school_name = str_replace(',','',$school_name);
    $school_name = str_replace(' ','_',$school_name);
    $chapter_id = isset($post_data['chapter_id'])?$post_data['chapter_id']:'';
    $page_no = ($start/10)+1;

    $page_count = 1;
    if($start>0){
        $page_count=$start+1;    
    }
    if(empty($post_data['search']['value'])){
        // $url = "https://0e3r24lsu5.execute-api.ap-south-1.amazonaws.com/Prod/tribalchapterapi?schoolId=".$school_name."&page=".$page_no."&chapterId=".$chapter_id;
        $url = 'https://0e3r24lsu5.execute-api.ap-south-1.amazonaws.com/Prod/tribalchapterapi?schoolId=EMRS_Shendegaon&page='.$page_no.'&chapterId=6TS0001';
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
        // echo "<pre>"; print_r($response['chapter']['Overall_score']); echo "</pre>"; die('end of code');
        $err = curl_error($curl);
        curl_close($curl);
        $totalData = $response['chapter']['total_count']; // need total count from api
        $totalFiltered = $totalData;
        // echo "<pre>"; print_r($posts); echo "</pre>"; die("end of posts yoyo");
    }
    $data = array();
	$no = $start;
	$button_color_array = array('A1'=>'greenr12','A2'=>'greenr2','B1'=>'yello1','B2'=>'yello2','C1'=>'oran1','C2'=>'oran2','D'=>'blue2','E1'=>'red','E2'=>'red1');
    foreach ($user_array as $user) {
    	// echo "<pre>"; print_r($user); echo "</pre>"; die('end of code');
		$no++;
		$row = array();
		$row[] = $no;
		$row[] = $user['name'];
		foreach ($topics_list as $key => $value) {
			$is_match = 0;
			foreach ($user['score'] as $ukey => $uvalue) {
				if($value['name'] == $uvalue['chapter_name']){
					$is_match = 1;
					$row[]='<span class="'.$button_color_array[$uvalue['grade']].'">'.$uvalue['grade'].'</span>';		
				}
			}
			if(!$is_match){
				$row[]='<span class="grey2">NA</span>';
			}
		}
		$row[] = '<span class="'.$button_color_array[$user['treasure_grade']].'">'.$user['treasure_grade'].'</span>';
		$row[] = '<span class="'.$button_color_array[$user['overall_grade']].'">'.$user['overall_grade'].'</span>';
		$data[] = $row;
	}
	// echo "<pre>"; print_r($data); echo "</pre>"; die('end of code');
    $output = array(
    	// "draw"            => intval($post_data['draw']),  
        "recordsTotal"    => intval($totalData),  
        "recordsFiltered" => intval($totalFiltered), 
        "data" => $data,
	);
	// echo "<pre>"; print_r($output); echo "</pre>"; die('end of code');
    //output to json format
   	echo json_encode($output);
?>
