<?php

function ConverToRoman($num){ 
	$n = intval($num); 
	$res = ''; 

	$romanNumber_Array = 
	array( 
		'M'  => 1000, 
		'CM' => 900, 
		'D'  => 500, 
		'CD' => 400, 
		'C'  => 100, 
		'XC' => 90, 
		'L'  => 50, 
		'XL' => 40, 
		'X'  => 10, 
		'IX' => 9, 
		'V'  => 5, 
		'IV' => 4, 
		'I'  => 1
	); 

	foreach ($romanNumber_Array as $roman => $number){ 
			//divide to get  matches
		$matches = intval($n / $number); 
			//assign the roman char * $matches
		$res .= str_repeat($roman, $matches); 
		//substract from the number
		$n = $n % $number; 
	} 

		// return the result
	return $res; 
} 

function Romannumeraltonumber($input_roman){
	$di=array(
		'I'=>1,
		'V'=>5,
		'X'=>10,
		'L'=>50,
		'C'=>100,
		'D'=>500,
		'M'=>1000
	);
	$result=0;
	if($input_roman=='') return $result;
		//LTR
	for($i=0;$i<strlen($input_roman);$i++){ 
		$result=(($i+1)<strlen($input_roman) and 
		$di[$input_roman[$i]]<$di[$input_roman[$i+1]])?($result-$di[$input_roman[$i]]) 
		:($result+$di[$input_roman[$i]]);
	}
	return $result;
}

function outputCSV($data) {
	$outstream = fopen("php://output", "a");
	$headers= 'Class, Name, Rank, Global Rank, Time Spent,Overall Grade';
	$headers.= "\n";
	fwrite($outstream,$headers);
	function __outputCSV(&$vals, $key, $filehandler) {
		fputcsv($filehandler, $vals); // add parameters if you want
	}
	array_walk($data, "__outputCSV", $outstream);
	fclose($outstream);
}
?>