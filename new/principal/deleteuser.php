<?php 
session_start();
if(empty($_SESSION['adminuids'])){
	header('Location:index.php');
}

include('db_config.php');
global $conn;

if ($_POST['action']=="del"){
	if ($_POST['id']) {
		$sql = "delete from teacher_login where userID ='".$_POST['id']."' ";
		
		$result = mysqli_query($conn,$sql);
		$obj = mysqli_fetch_object($result);
		echo "success";
	}	
	
}	
