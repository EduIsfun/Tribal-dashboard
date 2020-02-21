<?php
//error_reporting(0);
include('db_config.php');

global $conn;
include('model/Dashboard.class.php');

$dashboard = new Dashboard();

if (isset($_POST['action']) && ($_POST['action']="chartChapter")){
	//$chapter_name="Measurement and Patterns";
	$chapter_name=isset($_POST['chapter_id'])?$_POST['chapter_id']:'';

	$resultchapterid = $dashboard->getChapterid($chapter_name);
	
	$chapterlist=mysqli_fetch_object($resultchapterid);
	$subject_id = isset($_POST['subject_id'])?$_POST['subject_id']:'';
	$classid =isset($_POST['classid'])?$_POST['classid']:'';
    $chapter_id=$chapterlist->chapterID;
	
			$resultskill = $dashboard->getskillResult($chapter_id);
			$htm='';
			while($rowskill = mysqli_fetch_object($resultskill)){
				$skillid=$rowskill->skillID;
				$topic = $rowskill->skill;
				$htm.='{ label: "Topic", y:"'.$topic.'"},';
			}		
			echo $htm;	
			die();
}

?>
