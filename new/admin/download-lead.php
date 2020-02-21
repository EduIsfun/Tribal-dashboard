<?php
session_start();
  //error_reporting(0);
   include('include/db_config.php');
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=Lead-.csv');

	 $select_table="select email AS Email,mobile AS Mobile,game AS Game,platform AS Platfrom from lead";
	 $results=mysqli_query($conn,$select_table);
	 $rows = mysqli_fetch_assoc($results);
	if ($rows)
	{
		getcsv(array_keys($rows));
	}
	
	while($rows)
	{
		getcsv($rows);
		// $results=mysqli_query($con,$select_table);
		$rows = mysqli_fetch_assoc($results);
	}
		

// get total number of fields present in the database
	function getcsv($no_of_field_names)
	{
		$separate = '';
		// do the action for all field names as field name
		foreach ($no_of_field_names as $field_name)
		{
			if (preg_match('/\\r|\\n|,|"/', $field_name))
			{
				$field_name = '' . str_replace('', $field_name) . '';
			}
			echo $separate . $field_name;
			$separate = ',';
		}
		//make new row and line
		echo "\r\n";
	}
		
?>