<?php
		$servername = "localhost";
	$username= "paceedu";
	$password= "Akhajgdf@7354$";
	
	$dbname= "products";

	
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