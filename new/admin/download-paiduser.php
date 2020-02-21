<?php
session_start();
  error_reporting(0);
   include('include/db_config.php');
   header('Content-Type: text/csv');
   header('Content-Disposition: attachment;filename=Paiduser.csv');

		 $select_table="SELECT se.`userID`,se.`deviceID`,se.created_timestamp,se.game,se.timestamp,u.name,us.grade,s.`platform`,s.`serial_key`,us.`school` FROM `session` se 
            INNER JOIN `serial_key` s ON se.deviceID=s.deviceID
            INNER JOIN `user` u ON se.`userID`=u.userID
            LEFT JOIN `user_school_details` us ON se.`userID`=us.userID
            WHERE `serial_key` IS NOT NULL 
            GROUP BY deviceID ORDER BY se.timestamp DESC";
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
				$field_name = '' . str_replace('',$field_name) . '';
			}
			echo $separate . $field_name;
			//sepearte with the comma
			$separate = ',';
		}
		//make new row and line
		echo "\r\n";
	}

?>