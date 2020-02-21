<?php
session_start();
  //error_reporting(0);
   include('include/db_config.php');
   header('Content-Type: text/csv');
   header('Content-Disposition: attachment;filename = Aop User.csv');

     global $conn;
	if($_GET['id']){
	$date=$_GET['id'];
	
	$select_table = "SELECT `name` AS `Name`,mobile AS Mobile,email AS Email,city,created_timestamp FROM aop_user  
	WHERE (MONTH(created_timestamp))='$date' ";
	}
	else if($_GET['year']){
	$date=$_GET['year'];
	$select_table = "SELECT `name` AS `Name`,mobile AS Mobile,email AS Email,city,created_timestamp FROM aop_user  
	WHERE (YEAR(created_timestamp))='$date'";
	}
	
	else if($_GET['created_timestamp']){
	$date=$_GET['created_timestamp'];
	$select_table = "SELECT `name` AS `Name`,mobile AS Mobile,email AS Email,city,created_timestamp FROM aop_user  
	WHERE (Date(created_timestamp))='$date'";
	}
	
	else if($_GET['city']){
	$date=$_GET['city'];
	$select_table = "SELECT `name` AS `Name`,mobile AS Mobile,email AS Email,city,created_timestamp FROM aop_user  
	WHERE city ='$date' ";
	}
     else{
		
	$select_table = "SELECT `name` AS `Name`,mobile AS Mobile,email AS Email,city,created_timestamp FROM aop_user";
	}
	 $results = mysqli_query($conn,$select_table) or die(mysql_error($conn));
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