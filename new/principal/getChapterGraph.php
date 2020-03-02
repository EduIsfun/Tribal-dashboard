<?php
// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; die('end of code');

if (! function_exists('array_column')) {
  function array_column(array $input, $columnKey, $indexKey = null) {
    $array = array();
    foreach ($input as $value) {
      if ( !array_key_exists($columnKey, $value)) {
        trigger_error("Key \"$columnKey\" does not exist in array");
        return false;
      }
      if (is_null($indexKey)) { 
        $array[] = $value[$columnKey];
      }
      else {
        if ( !array_key_exists($indexKey, $value)) {
          trigger_error("Key \"$indexKey\" does not exist in array");
          return false;
        }
        if ( ! is_scalar($value[$indexKey])) {
          trigger_error("Key \"$indexKey\" does not contain scalar value");
          return false;
        }
        $array[$value[$indexKey]] = $value[$columnKey];
      }
    }
    return $array;
  }
}

if(isset($_POST['school_name']) && (isset($_POST['chapter_id']))){
    // echo "<pre>"; print_r($_POST); echo "</pre>"; die('end of code');
	$school_name = $_POST['school_name'];
    $school_name = str_replace(',','',$school_name);
    $school_name = str_replace(' ','_',$school_name);
    if (strpos($school_name, '|') !== false) {
        $school_name = str_replace('|', ',', $school_name);
    }
	$chapter_id = $_POST['chapter_id'];

    $url = 'https://0e3r24lsu5.execute-api.ap-south-1.amazonaws.com/Prod/tribalchapterapi?schoolId='.$school_name.'&page=1&chapterId='.$chapter_id;
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
    // echo "<pre>"; print_r($response['chapter']['topics']); echo "</pre>"; die('end of code');
    $err = curl_error($curl);
    curl_close($curl);

    if($response['chapter']['chapter_graph']){
        $chapter_graph=$response['chapter']['chapter_graph'];    
    }else{
        $chapter_graph=array();    
    }
    $chapter_array = array();
    $color_array = array('A1'=>'#0f3e0f','A2'=>'#46d246','B1'=>'#e6e600','B2'=>'#ffff00','C1'=>'#ff9900','C2'=>'#ffb84d','D'=>'#0033cc','E1'=>'#b32400','E2'=>'#ff471a','NA'=>'grey2');
    if($chapter_graph){
    	$data_points_array = array();
    	// foreach ($chapter_graph as $key => $value) {
    	// 	foreach ($value['score'] as $key => $value1) {
    	// 		$data_points_array['data_points'][$value1['grade']][$value['name']]['y']=$value1['count'];
    	// 		$data_points_array['data_points'][$value1['grade']][$value['name']]['label']=$value['name'];
    	// 	}
    	// }
    	// echo "<pre>"; print_r($chapter_graph); echo "</pre>"; die('end of chapter_graph');
    	foreach ($chapter_graph as $key => $value) {
    		foreach ($value['score'] as $key => $value1) {
    			$chapter_array[$value1['grade']]['type'] = 'stackedColumn';
    			$chapter_array[$value1['grade']]['name'] = $value1['grade'];
    			$chapter_array[$value1['grade']]['showInLegend'] = 'true';
    			$chapter_array[$value1['grade']]['yValueFormatString'] = '#,##';
    			$chapter_array[$value1['grade']]['color'] = $color_array[$value1['grade']];
    			$chapter_array[$value1['grade']]['dataPoints'][$value['name']]['y']=$value1['count'];
    			$chapter_array[$value1['grade']]['dataPoints'][$value['name']]['label']=$value['name'];
    			$chapter_array[$value1['grade']]['dataPoints'][$value['name']]['tooltip']=false;
    		}
    	}
    	$sort_column = array_column($chapter_array, 'name');
		array_multisort($sort_column, SORT_ASC, $chapter_array);
		
        // echo "<pre>"; print_r($response['chapter']['topics']); echo "</pre>"; die('end of code');
        $topic_list = array_column($response['chapter']['topics'],'name');

    	$final_array = array();
    	$i=0;
    	foreach ($chapter_array as $key => $value) {
    		$final_array[$i]['type'] = $value['type'];
    		$final_array[$i]['name'] = $value['name'];
    		$final_array[$i]['showInLegend'] = $value['showInLegend'];
    		$final_array[$i]['yValueFormatString'] = $value['yValueFormatString'];
    		// $final_array[$i]['dataPoints'] = array();
            // echo "<pre>"; print_r($value['dataPoints']); echo "</pre>"; die('end of code');
    		foreach ($value['dataPoints'] as $key1 => $value1) {
    			// echo "<pre>"; print_r($value1); echo "</pre>"; die('end of code');
                $final_array[$i]['dataPoints'][] = $value1;    
    		}
    		$final_array[$i]['color'] = $value['color'];
    		$i++;
    	}
        // echo "<pre>"; print_r($final_array); echo "</pre>"; die('end of code');
        $topic_array = $response['chapter']['topics'];
        $final_column_array = array('id','fullname');
        foreach ($topic_array as $key => $value) {
            $final_column_array[] = $value['name'];
        }
        $final_column_array[] = 'Treasure';
        $final_column_array[] = 'Overall';
        // echo "<pre>"; print_r(json_encode($final_array)); echo "</pre>"; die('end of final_array');
        $return_array['topic_array'] = $topic_array;
        $return_array['topics_list'] = $final_column_array;
        $return_array['final_array'] = json_encode($final_array,true);
    	echo json_encode($return_array,true);
    	// echo "<pre>"; print_r($final_array); echo "</pre>"; die('end of final_array');
    }
}
?>