<?php
session_start();
include('db_config.php');
include('model/Teacher.class.php');
include('model/Dashboard.class.php');
include('model/Download.class.php');
error_reporting(0);
	
$teacher = new Teacher();
$dashboard = new Dashboard();
	
$sql = "SELECT * FROM teacher_login WHERE userID = '".$_SESSION['uids']."'";
$result = mysqli_query($conn,$sql);
$obj = mysqli_fetch_object($result);
$school_id = $obj->school;
	
function Romannumeraltonumber($input_roman){
	$di=array(
		'I'=>1,
		'V'=>5,
		'X'=>10,
		'L'=>50,
		'C'=>100,
		'D'=>500,
		'M'=>1000
	);
	$result=0;
	if($input_roman=='') return $result;
		//LTR
	for($i=0;$i<strlen($input_roman);$i++){ 
		$result=(($i+1)<strlen($input_roman) and 
		$di[$input_roman[$i]]<$di[$input_roman[$i+1]])?($result-$di[$input_roman[$i]]) 
		:($result+$di[$input_roman[$i]]);
	}
	return $result;
}

if (isset($_REQUEST['action']) && ($_REQUEST['action']=="get_board_class_wise_subjects")){
	$res='';
	$board_id = isset($_POST['board_id'])?$_POST['board_id']:'';
	$class_id = isset($_POST['class_id'])?$_POST['class_id']:'';
	$class_id = Romannumeraltonumber($class_id);
	if ($board_id=='' && empty($board_id)) {
		$sql = "SELECT s.subject,s.subjectID FROM chapter as c INNER JOIN subject as s ON c.subjectID=s.subjectID INNER JOIN board as b ON b.board=c.exam WHERE s.subjectID IN (1,2,3,8)";
	} else {
		$sql = "SELECT s.subject,s.subjectID FROM chapter as c INNER JOIN subject as s ON c.subjectID=s.subjectID INNER JOIN board as b ON b.board=c.exam WHERE b.id = '".$board_id."'";
	}
	if ($class_id!='' && !empty($class_id)) {
		$sql.=" AND c.grade='".$class_id."'"; 
	}
	$sql.=" GROUP BY s.subject ORDER BY s.subjectID ASC"; 
	//echo $sql;	
	$result = mysqli_query($conn,$sql);
			
	$res .="<li id='all' style='cursor: pointer;'>";
	$res .="<a name='subject_id' id='subject_id' onclick='changeSubject();' data-toggle='tab'>All Subjects</a>";
	$res .="</li>";

	while($row = mysqli_fetch_object($result)){
	 	//$li_id = ( $li_count == 0 ) ? 'class="active"' : '';
		//$res .="<li ".$li_id." >";
		$res .="<li id='".$row->subjectID."' style='cursor: pointer;'>";
		$res .="<a name='subject_id' id='subject_id' onclick='changeSubject(".$row->subjectID.");' data-toggle='tab'>".$row->subject."</a>";
		$res .="</li>";
		//$li_count++;
	}			
	echo $res;
	die();	
}

if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getCity")){
	$res='';
	$stateid = isset($_POST['state_id'])?$_POST['state_id']:'';
	$cityid = isset($_POST['cityid'])?$_POST['cityid']:'';
	$sql = "select * from tbl_cities where state_id = $stateid";
	$result = mysqli_query($conn,$sql);
	$res .='<option value="">Choose City</option>';
		while($row = mysqli_fetch_object($result)){
			$res .= "<option value='".$row->id."'" ;
			 if ($row->id==$cityid) { $res.= "selected";};
			$res .= ">".$row->name."</option>";  
		}
		echo $res;
		die();					
	}

if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getBoard")){
	$city = $_POST['city'];
	//$board_id = $_POST['board_id'];.
	
	if ($_POST['selectedvalue']!=''){
		$schoolarray=explode("|",$_POST['selectedvalue']);
	}	
		
	$board_id ='';
	$outputs .='<option value="">School</option>';
	$sql ="SELECT ";
	if($board_id=='4'){
		$sql.=" sm.school as school,sm.school_code as school_code";
		} else {
		$sql.=" usd.school as school  ";
	}
	$sql.=" FROM  `knowledgekombat_chapter_time` kct 
	INNER JOIN `user` u ON u.userID = kct .userID 
	INNER JOIN user_school_details usd ON kct.`userID`=usd.userID ";
	if($board_id=='4'){
		$sql.=" INNER Join school_master sm on sm.school_code= usd.school_id";
	}
	$sql.=" where 1=1";

	if ($board_id == 'All' ){
		$sql.=" And usd.city_id = '$city' GROUP BY school ";				
	}else if(!empty($city) && $board_id==''){
		$sql.=" And usd.city_id ='$city' GROUP BY school";	
	}else if($board_id=='4'){
		$sql.=" And usd.city_id ='$city' AND sm.`school_code`!='' GROUP BY school";
	}else{
		$sql.=" And usd.city_id ='$city' AND usd.board_id='$board_id' GROUP BY school ";
	}
	//echo  $sql;		
	$result = mysqli_query($conn,$sql);
	while($obj = mysqli_fetch_object($result)){
		$outputs .="<option value='".$obj->school."'";
		if (in_array($obj->school, $schoolarray)) {$outputs .="selected";}
		$outputs .=" >".ucfirst($obj->school)."</option>";
	}
	echo $outputs;
	die();
}
		
if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getClass")){
	$output = "";
	$subject_id = ( $_POST['subject_id'] ) ? $_POST['subject_id'] : 0;
	if ($_POST['classid']!="all"){
		$classid = Romannumeraltonumber($_POST['classid']);
	}
	$sql = "SELECT * FROM chapter WHERE subjectID = $subject_id ";
	if ($_POST['classid']!="all"){
		$sql.= " AND grade = $classid  GROUP BY chapter ";
	}
	$result = mysqli_query($conn,$sql);
	$output.='<ul class="list-group list-scroll">
					<li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed"><img src="images/key.png" style="opacity: .6;" alt="" /> &nbsp CHAPTER
				</li>';
	$countchapter='1';
	if ($subject_id<=0) {
		$output .= "<li style='text-align: center; padding: 15px; color: white; font-size: 24px;'>Please Select Subject</li>"; 
	} else	{
		while($obj = mysqli_fetch_object($result))	{
			$chapter_id = $obj->chapterID;
			$chapter_name = substr($obj->chapter,0,30);
			$output .= '<li class="" id ="'.$chapter_id.'">';
			$output .= "<a href='#submenu66$countchapter' data-toggle='collapse' aria-expanded='false' class='bg-dark list-group-item list-group-item-action flex-column align-items-start collapsed'>
						<div class='d-flex w-100 justify-content-start align-items-center'>
						<img src='images/key.png' style='opacity: .6;' alt='' />
						&nbsp 
						<input type='button' id='chapter' name='$chapter_id' onclick='changeChapter(this.name,value);' value='$chapter_name' class='menu-collapsed'>
						<span class='submenu-icon'></span>
						</div>
						</a></li>
						<div id='submenu66$countchapter' class='collapse sidebar-submenu'>";
								
			$sql = "SELECT * FROM knowledgekombat_skill WHERE chapterID = '$chapter_id'";
			$subresult = mysqli_query($conn,$sql);
			if(mysqli_num_rows($subresult)>0){
				while($subobj = mysqli_fetch_object($subresult)){
					$skill_id = $subobj->skillID;
					$output.='<a href="#" id="subtopic" class="list-group-item list-group-item-action bg-dark text-white">
					<input type="button" id="subtopic" onclick="changeTopic(this.name);" name="'.$subobj->skillID.'" value="'.$subobj->skill.'" class="menu-collapsed">
					</a>';
				}
			}else{
				$output.='<a href="#" class="list-group-item list-group-item-action bg-dark text-white">
				<span class="menu-collapsed" style="color:red;">Not found topic</span>
				</a>';
			}						
			$output.='</div>';
			$countchapter++;						
		}
	}
	echo $output;
	die();
}
		
if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getSubject")){
	$output = "";
	
	$subject_id = ( $_POST['subject_id'] ) ? $_POST['subject_id'] : '';
	$classid = ( $_POST['classid'] ) ? $_POST['classid'] : "all";
	$boardid = ( $_POST['board_id'] ) ? $_POST['board_id'] : "";
	$schoolname = isset($_POST['school_id'])?$_POST['school_id']:'';
	
	$sql = "SELECT * FROM chapter c";
	if ($boardid!=''){
		$sql.= " INNER JOIN board b on LOWER(b.board)=LOWER(c.exam) ";
	}	
	
	$sql.= " INNER JOIN `knowledgekombat_skill` ks ON ks.`chapterID`=c.`chapterID`
	INNER JOIN `knowledgekombat_skill_attempt` ksa ON ksa.`skillID`=ks.`skillID`
	INNER JOIN `user_school_details` usd ON usd.`userID`=ksa.`userID` ";

	$sql.= " where 1=1 AND usd.`school`='".$schoolname."' ";
	
	if ($classid!="all" && !empty($classid)){
		$classid =  Romannumeraltonumber($classid)?Romannumeraltonumber($classid  ): 1;
		$sql.= " AND c.grade = $classid";
	} 
	if ($subject_id!=''){
		$sql.= " AND c.subjectID = $subject_id";
	}
	if ($boardid!=''){
		$sql.= " AND b.id = $boardid";
	}
	$sql.= "  GROUP BY c.`chapterID` Order BY chapter asc ";
	//$sql.= " GROUP BY chapter";
	//echo $sql;
	
	$result = mysqli_query($conn,$sql);
	$output.='<ul class="list-group list-scroll">
				<li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed"><img src="images/key.png" style="opacity: .6;" alt="" /> &nbsp CHAPTER </li>';
	$countchapter='1';

	//if ($subject_id<=0) {
	//	$output .= "<li style='text-align: center; padding: 15px; color: white; font-size: 24px;'>Please Select Subject</li>"; 
	//} else {
		while($obj = mysqli_fetch_object($result)) {
			$chapter_id = $obj->chapterID;
			$chapter_name = substr($obj->chapter,0,30);
			$output .= '<li class="" id ="'.$chapter_id.'">';
			$output .= "<a href='#submenu66$countchapter' data-toggle='collapse' aria-expanded='false' class='bg-dark list-group-item list-group-item-action flex-column align-items-start collapsed'>
						<div class='d-flex w-100 justify-content-start align-items-center'>
						<img src='images/key.png' style='opacity: .6;' alt='' />
						&nbsp 
						<input type='button' id='chapter' name='$chapter_id' onclick='changeChapter(this.name,value);' value='$chapter_name' class='menu-collapsed'>
						<span class='submenu-icon'></span>
						</div>
						</a></li>
						<div id='submenu66$countchapter' class='collapse sidebar-submenu'>";
			$sql = "SELECT * FROM knowledgekombat_skill WHERE chapterID = '$chapter_id' ORDER BY skillID";
			$subresult = mysqli_query($conn,$sql);
			if(mysqli_num_rows($subresult)>0){
				while($subobj = mysqli_fetch_object($subresult)){
					$skill_id = $subobj->skillID;
					$output.='<a href="#" id="subtopic" class="list-group-item list-group-item-action bg-dark text-white">
					<input type="button" id="subtopic" onclick="changeTopic(this.name);" name="'.$subobj->skillID.'" value="'.$subobj->skill.'" class="menu-collapsed">
					</a>';
				}
			} else {
				$output.='<a href="#" class="list-group-item list-group-item-action bg-dark text-white">
				<span class="menu-collapsed" style="color:red;">Not found topic</span>
				</a>';
			}
			$output.='</div>';
			$countchapter++;
		}
	//}
	echo $output;
	die();
}
		
if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getSubjects")){
	$output = "";
	$subject_id = $_POST['subject_id'];
	$classid = $_POST['classid'];
	if ($classid=="all"){
		$classid ='';
	}	
			 
	$sql = "SELECT * FROM chapter WHERE subjectID = '1'  AND grade = '1'  GROUP BY chapter ";
	$result = mysqli_query($conn,$sql);
	$output.='<ul class="list-group list-scroll">
			<li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed"><img src="images/key.png" style="opacity: .6;" alt="" /> &nbsp CHAPTER </li>';
	$countchapter='1';
	while($obj = mysqli_fetch_object($result)){
		$chapter_id = $obj->chapterID;
		$chapter_name = substr($obj->chapter,0,30);
		$output .= '<li class="" id ="'.$chapter_id.'">';
		$output .= "<a href='#submenu66$countchapter' data-toggle='collapse' aria-expanded='false' class='bg-dark list-group-item list-group-item-action flex-column align-items-start collapsed'>
				<div class='d-flex w-100 justify-content-start align-items-center'>
				<img src='images/key.png' style='opacity: .6;' alt='' />
				&nbsp 
				<input type='button' id='chapter' name='$chapter_id' onclick='changeChapter(this.name,value);' value='$chapter_name' class='menu-collapsed'>
				<span class='submenu-icon'></span>
				</div>
				</a></li>
				<div id='submenu66$countchapter' class='collapse sidebar-submenu'>";
		$sql = "SELECT * FROM knowledgekombat_skill WHERE chapterID = '$chapter_id'";
		$subresult = mysqli_query($conn,$sql);
		if(mysqli_num_rows($subresult)>0){
			while($subobj = mysqli_fetch_object($subresult)){
				$skill_id = $subobj->skillID;
				$output.='<a href="#" id="subtopic" class="list-group-item list-group-item-action bg-dark text-white">
				<input type="button" id="subtopic" onclick="changeTopic(this.name);" name="'.$subobj->skillID.'" value="'.$subobj->skill.'" class="menu-collapsed">
				</a>';
			}
		} else {
			$output.='<a href="#" class="list-group-item list-group-item-action bg-dark text-white">
					<span class="menu-collapsed" style="color:red;">Not found topic</span>
					</a>';
		}						
		$output.='</div>';
		$countchapter++;
	}
	echo $output;
	die();
}
		
if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getList")){
	$classid =isset($_POST['classid'])?$_POST['classid']:'';
	if ($classid=="all"){
		$classid ='';
	}	
	$subject_id =isset($_POST['subject_id'])?$_POST['subject_id']:1;
	$schoolname =isset($_POST['school_id'])?$_POST['school_id']:'';
	$state_id =isset($_POST['state_id'])?$_POST['state_id']:'';
	$city_id =isset($_POST['city_id'])?$_POST['city_id']:'';
	$subtopic =isset($_POST['subtopic'])?$_POST['subtopic']:'';
	$chapter_id =isset($_POST['chapter_id'])?$_POST['chapter_id']:'';
		
	$html='';
	$html .= '<!DOCTYPE html><html>
	<head></head>
	<body>
	<div class="" style="padding:10px;">    
    <div class="">';
	
	$html.= '<table id="example1" class="table table-bordered table-striped">';
	$html.= '<thead><tr>
				<th>Sr.No.</th>
				<th>Name</th>
				<th>Class</th>
				<th>Class Rank</th>
				<th>Global Rank </th>
				<th>Time Spent</th> 
				<th>Overall Grade</th>
			</tr>
			<div class="box-tools">
				<a href="download-report.php?state_id='.$_POST['state_id'].'&city_id='.$city_id.'&schoolname='.$schoolname.'&classid='.$classid.'&subject_id='.$subject_id.'" class="btn btn-success button-loading pull-right" id="btns" onclick="getExport()";>Export Record2</a>
				 <input type="hidden" id="studentids" value="'.$ids.'">
				<input type="button" onclick="getPdf();"  id="btn" class="btn btn-success name="PDF" value="PDF" style="margin-left:452px;">
				<span id="userresults"></span>
			</div>';
						
			$is = 1;
			//$ids = $teacher->getuserResultIDs($classid,$subject_id,$schoolname,$state_id,$city_id,$subtopic);
			$user = $teacher->getuserResult($classid,$subject_id,$schoolname,$state_id,$city_id,$chapter_id,$subtopic);
			
			 	
	$html.='</thead>';
	if(mysqli_num_rows($user)>0){
		$i=1;
		$gradeid = Romannumeraltonumber($classid);
		$overallresult = $teacher->getrankuserResult($gradeid,$schoolname,$city_id,$chapter_id,$subtopic);
		$classrankar =  array();
		$totalcrank =0;
		while($classrank = mysqli_fetch_object($overallresult)){
			$classrankar[]=array('userid'=>$classrank->userid,'rank'=>$classrank->rank);
			$totalcrank++;
		}	
		
		
		$globalresult = $teacher->getglobalrankResult($gradeid,$schoolname,$city_id,$chapter_id,$subtopic);	
		$globalrankar =  array();
		$totalgrank =0;
		while($globalrank = mysqli_fetch_object($globalresult)){
			$globalrankar[]=array('userid'=>$globalrank->userid,'rank'=>$globalrank->rank);
			$totalgrank++;
		}
		
		
							
		while($userlist = mysqli_fetch_object($user)){
			$learnincurrancy =0;
			$coloravg='';
			$img_grade = '';
			$overallgrade = $teacher->getoverallGrade($userlist->userID);	
			$allgrade = mysqli_fetch_object($overallgrade);	
			$learnincurrancy =$allgrade->currency;
			if (mysqli_num_rows($overallgrade)>0) {
				if (($allgrade->currency=="") || ($allgrade->currency=="0")) {
					$learnincurrancy =0;
					$showlearningavg ="E2";
					$coloravg ="redr";
					$img_grade = 'images/red.png';								
				} else {
					if ($learnincurrancy>=90){
						$showlearningavg ="A1";
						$coloravg ="greenr";
						$img_grade = 'images/green.png';	
					} else if (($learnincurrancy>=80) && ($learnincurrancy<90)){
						$showlearningavg ="A2";
						$coloravg ="greenr1";
						$img_grade = 'images/green.png';	
					} else if (($learnincurrancy>=70) && ($learnincurrancy<80)){
						$showlearningavg ="B1";
						$coloravg ="yellowr";
						$img_grade = 'images/yellow.png';
					} else if (($learnincurrancy>=60) && ($learnincurrancy<70)){
						$showlearningavg ="B2";
						$coloravg ="yellowr1";
						$img_grade = 'images/yellow.png';
					} else if (($learnincurrancy>=50) && ($learnincurrancy<60)){
						$showlearningavg ="C1";
						$coloravg ="orng1";
						$img_grade = 'images/orng.png';
					} else if (($learnincurrancy>=40) && ($learnincurrancy<50)){
						$showlearningavg ="C2";
						$coloravg ="orng2";
						$img_grade = 'images/orng.png';
					} else if (($learnincurrancy>=30) && ($learnincurrancy<40)){
						$showlearningavg ="D";
						$coloravg ="blues1";
						$img_grade = 'images/blue.png';
					} else if (($learnincurrancy>=20) && ($learnincurrancy<30)){
						$showlearningavg ="E1";
						$coloravg ="redr";
						$img_grade = 'images/red.png';
					} else if (($learnincurrancy>=0) && ($learnincurrancy<20)){
						$showlearningavg ="E2";
						$coloravg ="redr";
						$img_grade = 'images/red.png';
					} 
				}
			}
			
			/*$classrankVAL  = array_search($userlist->userID, $classrankar); 
			$globalranks  = array_search($userlist->userID, $globalrankar);	*/
					
			$globalrankskey = array_search($userlist->userID, array_column($globalrankar, 'userid'));
			$globalranks =$globalrankar[$globalrankskey]['rank'];
							
							
			$classrankkey = array_search($userlist->userID, array_column($classrankar, 'userid'));
			$classrankVAL =$classrankar[$classrankkey]['rank'];


			
			//if ($rank!=0){					
			$html.= '<tr> 
					<td style="color:white;">'.$is.'</td>
					<td><span class="span_inline" style="color:#333;font-size:14px;"> <img src="'.$img_grade.'" alt="icon" /> &nbsp &nbsp <a href="edufun.php?id='.$userlist->userID.'" target="_blank">'.ucfirst($userlist->name).'  </a></span></td>';			
			$html.='<td><span>'.$userlist->grade.'</span></td>';
			if ($classrankVAL>0) {
				$html.='<td class="dark"><span>'.$classrankVAL.'/'.$totalcrank.'</span></td>';
			} else{
				$html.='<td class="orange"><span>N/A</span></td>';
			}	
			if ($globalranks>0) {
				$html.='<td class="dark"><span>'.$globalranks.'/'.$totalgrank.'</span></td>';
			} else {
				$html.='<td class="orange"><span>N/A</span></td>';
			}				
			// $html.= '<td class="dark"><span>'.$rank.'</span></td>';
			// 		'<td class="orange"><span>'.$rank.'</span></td>';
			// $html.= '<td class="dark"><span>'.$globalranks.'</span></td>';
			$timeresult = $teacher->getrankTimestamp($userlist->userID);			    
			$Totaltime = mysqli_fetch_object($timeresult);
			$html.= '<td class="dark"><span><ul class="time-inline">'.$Totaltime->time.'</ul></span></td>';
			$html.= '<td class='.$coloravg.'><span>'.$showlearningavg.'</span></td>';
			$html.= '</tr>';
			//}	
			$is++; 
		}
	} else {
		$html.= '<tr>';
		$html.= '<td style="color:red;">No user </td>';
		$html.= '<td style="color:red;" colspan="8">No Record found</td>';
		$html.= '</tr>';
	}
	$html.= '</table>';
	$html.="</div></div></body>
			<script src='js/jquery.min.js'></script>
			<script src='js/bootstrap.min.js'></script>	
			<script src='js/jquery.dataTables.min.js'></script>
			<script src='js/dataTables.bootstrap.min.js'></script>
			<script>
				$(function () {
					$('#example1').DataTable()
					$('#example2').DataTable({
					  'paging'      : true,
					  'lengthChange': false,
					  'searching'   : false,
					  'ordering'    : true,
					  'info'        : true,
					  'autoWidth'   : false
					})
				})
			</script></html>";
	echo $html;
	die();
}
	
if (isset($_REQUEST['action']) && ($_REQUEST['action']=="getListsubtopic")){
	$classid =isset($_POST['classid'])?$_POST['classid']:'';
	if ($classid=="all"){
		$classid ='';
	}	
	$subject_id =isset($_POST['subject_id'])?$_POST['subject_id']:1;
	$schoolname =isset($_POST['school_id'])?$_POST['school_id']:'';
	$state_id =isset($_POST['state_id'])?$_POST['state_id']:'';
	$city_id =isset($_POST['city_id'])?$_POST['city_id']:'';
	$subtopic =isset($_POST['subtopic'])?$_POST['subtopic']:'';
	$chapter_id=isset($_POST['chapter_id'])?$_POST['chapter_id']:'';
	// $resultskill = $dashboard->getskillResult($chapter_id);
	// $chaptercount =mysqli_num_rows($resultskill);
	$html='';
	$html.='<!DOCTYPE html><html><head>
			<style>
				@media only screen and (max-width: 992px) {
					#example1_wrapper {
					overflow-x: scroll;
					}
				}
			</style>
		</head>
		<body>
    <div class="" style="padding:10px;">    
		<div class="">';
	$html .= '<table id="example1" class="table table-bordered table-striped" data-page-length="25">';
	$html.= '<thead>
		<tr>
			<th> No.</th>
			<th> Name</th>
			<th>Class Rank</th>
			<th>Global Rank </th>
			<th>Time Spent</th>
			<th>Subtopic Grade</th>	
			<th>Overall Grade</th>
		</tr>';
			
		$ids = $teacher->getuserResultIDs($classid,$subject_id,$schoolname,$state_id,$city_id,$subtopic);
		$user = $teacher->getuserResult($classid,$subject_id,$schoolname,$state_id,$city_id,$chapter_id,$subtopic);
	if($user){
		$count = 1;
	}
					 
	$html.='<div class="box-tools">
				<a href="download-report.php?state_id='.$_POST['state_id'].'&city_id='.$city_id.'&schoolname='.$schoolname.'&classid='.$classid.'&subject_id='.$subject_id.'&chapter_id='.$chapter_id.'&subtopic='.$subtopic.'" class="btn btn-success button-loading pull-right" id="btns" onclick="getExport()";>Export Record</a>
			</div>';
	$html.='<input type="hidden" id="studentids" value="'.$ids.'">
			<span id="userresults"></span>
		</thead>';
	if(mysqli_num_rows($user)>0){
		$htmls='';
		$resultskill = $dashboard->getskillResult($chapter_id);
		$learningaverage =0;
		$overallavgcount =0;
		$overalllavgearning =0;
		$coloravg='';
		$gradeid = Romannumeraltonumber($classid);
		$overallresult = $teacher->getrankuserResult($gradeid,$schoolname,$city_id,$chapter_id,$subtopic);
		$classrankar =  array();
		$totalcrank =0;
		while($classrank = mysqli_fetch_object($overallresult)){
			$classrankar[]=array('userid'=>$classrank->userid,'rank'=>$classrank->rank);
			$totalcrank++;
		}	
		
		
		$globalresult = $teacher->getglobalrankResult($gradeid,$schoolname,$city_id,$chapter_id,$subtopic);	
		$globalrankar =  array();
		$totalgrank =0;
		while($globalrank = mysqli_fetch_object($globalresult)){
			$globalrankar[]=array('userid'=>$globalrank->userid,'rank'=>$globalrank->rank);
			$totalgrank++;
		}
		
		while($userlist = mysqli_fetch_object($user)){
			$rowskill = mysqli_fetch_object($resultskill);
			$skillid=$rowskill->skillID;
			$resultlearningaverage = $teacher->getlearningResultAverage($chapter_id,$school_id,$classid,$subject_id,$state_id,$city_id,$subtopic,$userlist->userID);
							
			$rowlearningaveareg=mysqli_fetch_object($resultlearningaverage);
			$learningaverage =$rowlearningaveareg->learningavg;
			if (mysqli_num_rows($resultlearningaverage)>0) {
				if (($rowlearningaveareg->learningavg=="") || ($rowlearningaveareg->learningavg=="0") ) {
					$learningaverage =0;
					$showlearningavg ="E2";
					$coloravg ="red";
				} else {
					if ($learningaverage>=90){
						$showlearningavg ="A1";
						$coloravg ="greenr12";
					} else if (($learningaverage>=80) && ($learningaverage<90)){
						$showlearningavg ="A2";
						$coloravg ="greenr2";
					} else if (($learningaverage>=70) && ($learningaverage<80)){
						$showlearningavg ="B1";
						$coloravg ="yello1";
					} else if (($learningaverage>=60) && ($learningaverage<70)){
						$showlearningavg ="B2";
						$coloravg ="yello2";
					} else if (($learningaverage>=50) && ($learningaverage<60)){
						$showlearningavg ="C1";
						$coloravg ="oran1";
					} else if (($learningaverage>=40) && ($learningaverage<50)){
						$showlearningavg ="C2";
						$coloravg ="oran2";
					} else if (($learningaverage>=33) && ($learningaverage<40)){
						$showlearningavg ="D";
						$coloravg ="blue2";
					} else if (($learningaverage>=20) && ($learningaverage<30)){
						$showlearningavg ="E1";
						$coloravg ="redr";
					} else if (($learningaverage>=0) && ($learningaverage<20)){
						$showlearningavg ="E2";
						$coloravg ="redr";
					} 
					
					
				$overallavgcount++;
				}
				$overalllavgearning =$overalllavgearning+$learningaverage;
			} else {
				$showlearningavg ="E1";
				$coloravg ="red";
			}	
		$htmls='<td><span class='.$coloravg.'>'.$showlearningavg.'</span></td>';
			$learnincurrancy =0;
			$coloravg='';
			$img_grade = '';
			$overallgrade = $teacher->getoverallGrade($userlist->userID);	
			$allgrade = mysqli_fetch_object($overallgrade);	
			$learnincurrancy =$allgrade->currency;
			if (mysqli_num_rows($overallgrade)>0) {
				if (($allgrade->currency=="") || ($allgrade->currency=="0")) {
					$learnincurrancy =0;
					$showlearningavg ="E2";
					$coloravg ="redr";
                     $img_grade = 'images/red.png';									
                } else {
					if ($learnincurrancy>=90){
						$showlearningavg ="A1";
						$coloravg ="greenr";
						$img_grade = 'images/green.png';	
					} else if (($learnincurrancy>=80) && ($learnincurrancy<90)){
						$showlearningavg ="A2";
						$coloravg ="greenr1";
						$img_grade = 'images/green.png';	
					} else if (($learnincurrancy>=70) && ($learnincurrancy<80)){
						$showlearningavg ="B1";
						$coloravg ="yellowr";
						$img_grade = 'images/yellow.png';
					} else if (($learnincurrancy>=60) && ($learnincurrancy<70)){
						$showlearningavg ="B2";
						$coloravg ="yellowr1";
						$img_grade = 'images/yellow.png';
					} else if (($learnincurrancy>=50) && ($learnincurrancy<60)){
						$showlearningavg ="C1";
						$coloravg ="orng1";
						$img_grade = 'images/orng.png';
					} else if (($learnincurrancy>=40) && ($learnincurrancy<50)){
						$showlearningavg ="C2";
						$coloravg ="orng2";
						$img_grade = 'images/orng.png';
					} else if (($learnincurrancy>=30) && ($learnincurrancy<40)){
						$showlearningavg ="D";
						$coloravg ="blues1";
						$img_grade = 'images/blue.png';
					} else if (($learnincurrancy>=20) && ($learnincurrancy<30)){
						$showlearningavg ="E1";
						$coloravg ="redr";
						$img_grade = 'images/red.png';
					} else if (($learnincurrancy>=0) && ($learnincurrancy<20)){
						$showlearningavg ="E2";
						$coloravg ="redr";
						$img_grade = 'images/red.png';
					} 
				}
			}
			$globalrankskey = array_search($userlist->userID, array_column($globalrankar, 'userid'));
			$globalranks =$globalrankar[$globalrankskey]['rank'];
							
							
			$classrankkey = array_search($userlist->userID, array_column($classrankar, 'userid'));
			$classrankVAL =$classrankar[$classrankkey]['rank'];

		
			//if ($rank!=0){
				$html.= '<tr>
			    <td><span class="span_inline">&nbsp &nbsp '.$count++.' </span></td>
				<td><span class="span_inline"><img src="'.$img_grade.'" alt="icon" /> &nbsp &nbsp <a href="edufun.php?id='.$userlist->userID.'" target="_blank">'.$userlist->name.' </a></span></td>';
				if ($classrankVAL>0) {
					$html.='<td class="dark"><span>'.$classrankVAL.'/'.$totalcrank.'</span></td>';
				} else{
					$html.='<td class="orange"><span>N/A</span></td>';
				}	
				if ($globalranks>0) {
					$html.='<td class="dark"><span>'.$globalranks.'/'.$totalgrank.'</span></td>';
				} else {
					$html.='<td class="orange"><span>N/A</span></td>';
				}		
				$timeresult = $teacher->getrankTimestamp($userlist->userID);			    
				$Totaltime = mysqli_fetch_object($timeresult);
				$html.= '<td class="orange"><span><ul class="time-inline">'.$Totaltime->time.'</ul></span></td>';
				$html.=''.$htmls.'';
				$html.= '<td class='.$coloravg.'><span>'.$showlearningavg.'</span></td>
				</tr>';
			//}
		}
	} else {
		$html.='<tr>';
		$html.='<td style="color:white;">No user </td>';
		$html.='<td colspan="8" style="color:white;">No Record found</td>';
		$html.='</tr>';
	}
	$html.="</div></div></body>
			<script src='js/jquery.dataTables.min.js'></script>
			<script src='js/dataTables.bootstrap.min.js'></script>
			<script>
				$(function () {
					$('#example1').DataTable()
					$('#example2').DataTable({
						'paging'      : true,
						'lengthChange': false,
						'searching'   : false,
						'ordering'    : true,
						'info'        : true,
						'autoWidth'   : false
					})
				})
			</script></html>";
	$html.='</table>';
	echo $html;
	die();
}
?>