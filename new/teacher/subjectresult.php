<?php

session_start();
include('../db_config.php');

if(empty($_SESSION['uid'])){
	header('Location:index.php');
}

function __autoload($classname){
	include("Classes/$classname.class.php");
}

if (isset($_POST['action']) && ($_POST['action']=="getsubject")){
	$chapterid=isset($_POST['chapterid'])?$_POST['chapterid']:'';
	if($chapterid!=''){
		$dashboard = new Dashboard();
		$cond = "chapterID = '$chapterid'";
		$subjectlist = $dashboard->getsubjectResult($cond);
		$subject=mysqli_fetch_assoc($subjectlist);
		$subjectid = $subject['subjectID'];  
	}	else  {
		$subjectid	= 0;
	}	
}
echo $subjectid;

?>	