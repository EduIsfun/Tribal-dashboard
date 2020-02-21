<?php
	 $servername = "localhost";
	 $username= "paceedu";
	 $password= "Akhajgdf@7354$";
	 $dbname= "products";
	
	$conn = mysqli_connect($servername,$username,$password,$dbname);
	if($conn)
	{
		//echo "Database Selected";
	}
	else
	{
	    echo "database not selected";
	}
	

?>