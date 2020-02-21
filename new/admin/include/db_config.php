<?php

 // print_r($_POST);
 global $dashboardbox;
  
  if (isset($_POST['db']) && $_POST['db']!=''){
	 unset($_SESSION['dbname']);
     $dbname=$_POST['db'];
	 $_SESSION['dbname']=$_POST['db']; 
	  
  } else if(isset($_SESSION['dbname']) && $_SESSION['dbname']!='' ) {
	
	$dbname=$_SESSION['dbname'];
  
  } else  {
	$dbname="products";
	$_SESSION['dbname']="products";	

  }	 
  
  if($_SESSION['dbname']=="products"){ 
  $dashboardbox{'User'}=true;
  $dashboardbox{'Lead'}=true;
  $dashboardbox{'Invite_Friends'}=true;
  $dashboardbox{'Call_Me'}=true;
  $dashboardbox{'User_Password'}=true;
  $dashboardbox{'Paid_User'}=true;
  $dashboardbox{'free_User'}=true;
  $dashboardbox{'Manage_User'}=true;
  $dashboardbox{'Manage_Call_Me'}=true;
  $dashboardbox{'Manage_Invite_Friends'}=true;
  $dashboardbox{'Manage_Lead'}=true;
  $dashboardbox{'Manage_Paid_User'}=true;
  $dashboardbox{'Manage_Free_User'}=true;
  $dashboardbox{'Manage_User_Password'}=true;
  $dashboardbox{'Manage_Subject'}=true;
  $dashboardbox{'Manage_chapter'}=true;
  $dashboardbox{'Manage_Aop_User'}=true;
  }
  else{
  $dashboardbox{'User'}=true;
  $dashboardbox{'Lead'}=false;
  $dashboardbox{'Invite_Friends'}=false;
  $dashboardbox{'Call_Me'}=false;
  $dashboardbox{'User_Password'}=true;
  $dashboardbox{'Paid_User'}=false;
  $dashboardbox{'free_User'}=false;
  $dashboardbox{'Manage_User'}=true;
  $dashboardbox{'Manage_Call_Me'}=false;
  $dashboardbox{'Manage_Invite_Friends'}=false;
  $dashboardbox{'Manage_Lead'}=false;
  $dashboardbox{'Manage_Paid_User'}=false;
  $dashboardbox{'Manage_Free_User'}=false;
  $dashboardbox{'Manage_User_Password'}=true;
  $dashboardbox{'Manage_Subject'}=true;
  $dashboardbox{'Manage_chapter'}=true;
  $dashboardbox{'Manage_Aop_User'}=false;
  }
 
	$servername="localhost";
	$username="paceedu";
	$password="Akhajgdf@7354$";
	$dbname=isset($dbname)?$dbname:'products';
	$conn=mysqli_connect($servername,$username,$password,$dbname);
	
	 if($conn)
	 {
	    echo mysqli_error();
	 }
	else
	{
	   // echo "database not selected";
	}


?>