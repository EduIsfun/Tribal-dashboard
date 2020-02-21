<?php

session_start();
include('../db_config.php');

if(empty($_SESSION['uid'])){
	header('Location:index.php');
}

function __autoload($classname){
	include("Classes/$classname.class.php");
}

$dashboard = new Dashboard();
$html="<select class='form-control form_dash' name='chaptername' id='chaptername' onchange='return getresult();' >";
if (isset($_POST['action']) && ($_POST['action']="getchapter")){
	$subjectid=isset($_POST['subjectid'])?$_POST['subjectid']:'';
	if ($subjectid=="math"){
		$cond = "subjectID IN  ('1')";
	} elseif ($subjectid=="science"){
		$cond = "subjectID IN  ('2','3','8','14')";
	} else {
		//$cond = "subjectID NOT IN  ('1','2','3','8')";
		$cond = "1=1";
	}	
	
	$list = $dashboard->getchapterResult($cond);
	$html.="<option value=all>All</option>";
	while($chapterlist = mysqli_fetch_assoc($list)){ 
		$html.="<option value=".$chapterlist['chapterID'].">".$chapterlist['chapter']."</option>";
	} 
}
$html.="</select>";
echo $html;
?>	