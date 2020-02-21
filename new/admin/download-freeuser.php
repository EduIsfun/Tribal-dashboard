<?php
session_start();
  //error_reporting(0);
   include('include/db_config.php');
   header('Content-Type: text/csv');
   header('Content-Disposition: attachment;filename=Free User-.csv');

	 $select_table="SELECT * FROM `session` AS a WHERE NOT EXISTS(SELECT * FROM `serial_key` AS b WHERE b.`deviceID` = a.`deviceID`) GROUP BY a.`userID`"; 
		$results=mysqli_query($conn,$select_table) or die(mysql_error($conn));
	     $rows = mysqli_fetch_assoc($results);
	     if ($rows)
	     {
		   getcsv(array_keys($rows));
	     }
		//print_r($rows);
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
				$field_name = '' . str_replace('','', $field_name) . '';
			}
			echo $separate . $field_name;
			//sepearte with the comma
			$separate = ',';
		}
		//make new row and line
		echo "\r\n";
	}

?>