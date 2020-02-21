<?php
session_start();
  error_reporting(0);
   include('include/db_config.php');
   header('Content-Type: text/csv');
   header('Content-Disposition: attachment;filename=Invite Friends.csv');

		 $select_table="SELECT i.UserID,i.name,i.mobile,i.emailid,i.time_stamp,u.dob,u.country,u.`created_timestamp`,u.`updated_timestamp`,u.city,u.type,u.countrycode FROM invite_friends i
         INNER JOIN `user` u ON i.UserID=u.userID order by i.time_stamp desc";
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