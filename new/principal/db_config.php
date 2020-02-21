<?php
	$servername = "localhost";
	$username= "root";
	$password= "";
	
	$dbname= "old_db_200220";


	
$conn=mysqli_connect($servername,$username,$password,$dbname)or die('error'.mysqli_error());
if($conn)
{
	//echo "Database Selected";
}
else
{
	echo "database not selected";
}
	

?>