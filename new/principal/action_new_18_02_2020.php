<?php
session_start();
include('db_config.php');
include('model/Teacher.class.php');
include('model/Dashboard.class.php');
include('model/Download.class.php');
include('functions.php');
error_reporting(0);
	
$teacher = new Teacher();
$dashboard = new Dashboard();

//$sql = "SELECT * FROM teacher_login WHERE userID = '".$_SESSION['uids']."'";
$sql = "SELECT tl.*,uas.class_rank,uas.global_rank,uas.alias_rank FROM `teacher_login` tl INNER JOIN `user_admin_settings` uas ON tl.`id`=uas.`user_id` WHERE tl.`userID`= '".$_SESSION['uids']."'";
$result = mysqli_query($conn,$sql);
$obj = mysqli_fetch_object($result);
$mainschool = $obj->main_school;
if ($mainschool==''){ 
	$schoollist = $obj->school;
} else {
	$schoollist = $mainschool."|".$obj->school;
}	
$schoolarray = str_replace("|", "','",  $schoollist );
$schoolselectedarray=explode("|",$_SESSION['schoolname']);

$alias = $obj->alias;
$class_rank = $obj->class_rank;
$global_rank = $obj->global_rank;
$alias_rank = $obj->alias_rank;



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
	// echo $sql;	
	// exit;
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
					<input type="button" id="subtopic" onclick="changeTopic(this.name,this.value);" name="'.$subobj->skillID.'" value="'.$subobj->skill.'" class="menu-collapsed">
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
	//$schoolname = isset($_POST['school_id'])?$_POST['school_id']:'';
	$schoollist =isset($_POST['school_id'])?$_POST['school_id']:'';
	$schoolname = str_replace("|", "','",  $schoollist );
	

	$subQuery = " select ks.`chapterID`, GROUP_CONCAT(
		DISTINCT CONCAT(ks.skillID,',',ks.skill) 
		ORDER BY ks.skillID 
		SEPARATOR ';'
	  ) as skills from `knowledgekombat_skill` ks use index (chapterID) INNER JOIN `knowledgekombat_skill_attempt` ksa use index (skillID_2)  ON ksa.`skillID`=ks.`skillID`  and ksa.`school` in ('".$schoolname."') ";

	if ($classid!="all" && !empty($classid)){
		//$classid =  Romannumeraltonumber($classid)?Romannumeraltonumber($classid  ): 1;
		$subQuery.= " AND ksa.grade = '".$classid."'";
	} 

	$subQueryGroup = " group by ks.chapterID " ; 
	$subQuery = $subQuery.$subQueryGroup;

	$sql = " select c.*, t.skills from  chapter c  ";

	$join = " INNER JOIN ( $subQuery ) t ON t.`chapterID`=c.`chapterID` ";

	if ($boardid!=''){
		$join .= " INNER JOIN board b on LOWER(b.board)=LOWER(c.exam) ";
	}	

	$where = " where 1=1 "; 

	if ($subject_id!='' && is_numeric($subject_id) ){
		$where.= " and c.subjectID = $subject_id";
	}
	if ($boardid!=''){
		$where .= "  and b.id = $boardid";
	}

	$group .= "  GROUP BY c.`chapterID` Order BY c.chapter asc ";
	$query = $sql . $join . $where. $group ;

	// old query - start
	// $sql = "SELECT * FROM chapter c";
	// if ($boardid!=''){
	// 	$sql.= " INNER JOIN board b on LOWER(b.board)=LOWER(c.exam) ";
	// }	
	
	// $sql.= " INNER JOIN `knowledgekombat_skill` ks ON ks.`chapterID`=c.`chapterID`
	// INNER JOIN `knowledgekombat_skill_attempt` ksa ON ksa.`skillID`=ks.`skillID`
	// INNER JOIN `user_school_details` usd ON usd.`userID`=ksa.`userID` ";

	// $sql.= " where 1=1 AND usd.`school` in ('".$schoolname."') ";
	
	// if ($classid!="all" && !empty($classid)){
	// 	//$classid =  Romannumeraltonumber($classid)?Romannumeraltonumber($classid  ): 1;
	// 	$sql.= " AND usd.grade = '".$classid."'";
	// } 
	// if ($subject_id!=''){
	// 	$sql.= " AND c.subjectID = $subject_id";
	// }
	// if ($boardid!=''){
	// 	$sql.= " AND b.id = $boardid";
	// }
	// $sql.= "  GROUP BY c.`chapterID` Order BY chapter asc ";
	// echo " dddd ";
	// //$sql.= " GROUP BY chapter";
	// echo $sql;

	// old query -end 
	
	$result = mysqli_query($conn,$query);

	// print_r($result);
	// exit;
	$output.='<ul class="list-group list-scroll">
				<li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed"><img src="images/key.png" style="opacity: .6;" alt="" /> &nbsp CHAPTER </li>';
	$countchapter='1';

	//if ($subject_id<=0) {
	//	$output .= "<li style='text-align: center; padding: 15px; color: white; font-size: 24px;'>Please Select Subject</li>"; 
	//} else {
		while($obj = mysqli_fetch_object($result)) {
			$skillsArr = explode(';', $obj->skills);
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
			
			if(!empty($skillsArr)){
				foreach($skillsArr as $skillKey => $skillValue)
				{
					$skill = explode(',', $skillValue);
					$skill_id = $skill[0];
					$output.='<a href="#" id="subtopic" class="list-group-item list-group-item-action bg-dark text-white">
					<input type="button" id="subtopic" onclick="changeTopic(this.name,this.value);" name="'.$skill_id.'" value="'.$skill[1].'" class="menu-collapsed">
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
				<input type="button" id="subtopic" onclick="changeTopic(this.name,this.value);" name="'.$subobj->skillID.'" value="'.$subobj->skill.'" class="menu-collapsed">
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
	$schoollist =isset($_POST['school_id'])?$_POST['school_id']:'';
	$schoolname = str_replace("|", "','",  $schoollist );
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
	$is = 1;
	$globalresult = $teacher->getglobalrankResultNew($classid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id);	
	//$ids = $teacher->getuserResultIDs($classid,$subject_id,$schoolname,$state_id,$city_id,$subtopic);
	$user = $teacher->getuserResult($classid,$subject_id,$schoolname,$state_id,$city_id,$chapter_id,$subtopic);

	$userRecordArr = array();
	while($row = $user->fetch_assoc())
	{
		$set[] = $row['userID'];
		$userRecordArr[$row['userID']] = $userRecord;
	}
	//error_reporting(E_ALL);
	$user = $teacher->getuserResult($classid,$subject_id,$schoolname,$state_id,$city_id,$chapter_id,$subtopic);
	//$user_school_ids = $teacher->getUserFromSchools($schoolarray);

	
	// for ($userIdArr = array (); $userRow = $user_school_ids->fetch_assoc(); $userIdArr[] = array_shift($userRow));
	// $implodedAllSchoolUserIds = implode("','", $userIdArr);

	// print_r($implodedAllSchoolUserIds);
	// 	exit;
	$user->data_seek(0); 
	$imploded = implode("','", $set);

	$overallgradeArr = $teacher->getoverallGradeNew($subtopic,$subject_id, $imploded, $classid, $schoolname);

	// print_r($overallgradeArr);
	// exit;
	$overallgradeDataArr = array();
	while($overallgradeRow = mysqli_fetch_object($overallgradeArr) ){
		$overallgradeDataArr[$overallgradeRow->userid] = $overallgradeRow->currency;
	}

	// echo "<pre>";
	// print_r($overallgradeDataArr);

	// exit;

	if(mysqli_num_rows($user)>0){
		$i=1;
		$totalcrank =0;
		$totalgrank =0;
		$totalarank =0;
		
		$gradeid = Romannumeraltonumber($classid);
		$overallresult = $teacher->getrankuserResultNew($gradeid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id, $imploded);

		$classrankar =  array();
		$classrankar_new = array();
		$totalcrank =0;
		while($classrank = mysqli_fetch_object($overallresult)){
			$classrankar[]=array('userid'=>$classrank->userid,'rank'=>$classrank->rank);
			$classrankar_new[$classrank->userid] = $classrank->rank;
			$totalcrank++;
		}

		// print_r($classrankar_new);
		// exit;
		
		$globalrankar =  array();
		$globalrankar_new = array();
		$totalgrank =0;
		while($globalrank = mysqli_fetch_object($globalresult)){
			$globalrankar[]=array('userid'=>$globalrank->userid,'rank'=>$globalrank->rank);
			$globalrankar_new[$globalrank->userid] = $globalrank->rank;
			$totalgrank++;
		}

		// print_r($globalrankar_new);
		// exit;
		//print_r($user_school_ids); exit;
		//$aliasresult = $teacher->getaliasrankResult($gradeid,$schoolarray,$city_id,$chapter_id,$subtopic,$subject_id, $implodedAllSchoolUserIds);	
		$aliasresult = $teacher->getaliasrankResultNew($gradeid,$schoolarray,$city_id,$chapter_id,$subtopic,$subject_id, $implodedAllSchoolUserIds);
		// print_r($aliasresult);
		//exit;
		$aliasrankar =  array();
		$aliasrankar_new = array();
		$totalarank =0;
		while($aliasrank = mysqli_fetch_object($aliasresult)){
			$aliasrankar[]=array('userid'=>$aliasrank->userid,'rank'=>$aliasrank->rank);
			$aliasrankar_new[$aliasrank->userid] = $aliasrank->rank;
			$totalarank++;
		}
		
		$classenabled = $teacher->getuserclassenabled($classid,$subject_id,$schoolname,$state_id,$city_id,$chapter_id,$subtopic);
		//echo $classenabled->grpclsenabled;
		
		if ($classenabled->grpclsenabled!=""){
			$classesarray = explode(",",$classenabled->grpclsenabled);
		} else {
			$classesarray = $user->grade;	
		}	
		$classesarray=array_unique($classesarray);
		sort($classesarray);
		$html.='<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6" ><div style="display:none" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<label>	Select Class : - </label>
						<select id="gradeenabled" name="gradeenabled" class="form-control input-sm" onchange="changeClass(this.value)">
						<option value="all">All</option>'; 
						foreach ($classesarray as $key=>$value) { 
							if ($selectgrade==$value) { $selected ="selected";} else { $selected='';}
							$grdq= "SELECT count(*) as countgrd FROM knowledgekombat_skill_attempt ksa INNER JOIN
							knowledgekombat_skill ks ON ksa.skillID=ks.skillID INNER JOIN chapter c ON
							c.chapterID=ks.chapterID  WHERE (c.grade='".$value."' or 
							c.grade='".Romannumeraltonumber($value)."')";

							// echo $grdq;
							// exit;
							$grdres = mysqli_query($conn,$grdq);
							$grdrow = mysqli_fetch_object($grdres);
							if ($grdrow->countgrd>0){
								$html.= '<option value='.ConverToRoman($value).' '.$selected.'>'.$value.'</option>';
							}
						}		
						$html.= '</select>
						</div></div>
						<div id="export_section" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<span id="userresults"></span>
							<select name="downloadtype" id="downloadtype" class="form-control input-sm">
								<option value="csv">CSV</option>
								<option value="pdf">PDF</option>
								<option value="xls">XLS</option>
							</select>
						<input type="button" onclick="getdownload();"  id="btn" class="btn btn-success name="PDF" value="Export" style="margin-left:20px;">
						</div>	
				</div>';
		$html.= '<table id="example1" class="table table-bordered table-striped">';
		$html.= '<thead><tr>
					<th>Sr.No.</th>
					<th>Name</th>';
		if 	(count($schoolselectedarray)>1) { 		
			$html.= '<th>School</th>';
		}
		$html.= '<th>Class</th>';
					if (($class_rank=="on") && ($classid!='')){
					$html.= '<th>Class Rank</th>';
					}
					if ($global_rank=="on"){
					$html.= '<th>Global Rank </th>';
					}
					if ($alias_rank=="on"){
					$html.= '<th>'.$alias.' Rank</th>';
					}
					
					$html.= '<th>Time Spent</th> 
					<th>Overall Grade</th>
				</tr>
				</thead>';

		while($userlist = mysqli_fetch_object($user)){
			$learnincurrancy =0;
			$coloravg='';
			$img_grade = '';
			$subtopicid='';
			//$overallgrade = $overallgradeDataArr[$userlist->userID] ; //$teacher->getoverallGrade($userlist->userID,$subtopicid,$subject_id);
			//$allgrade = mysqli_fetch_object($overallgrade);	

			// print_r($overallgradeDataArr);
			// exit;
			// $allgrade = $overallgradeDataArr[$userlist->userID] ;
			$learnincurrancy = isset($overallgradeDataArr[$userlist->userID])? $overallgradeDataArr[$userlist->userID] : '';

			// echo $userlist->userID;
			// print_r($overallgradeDataArr[$userlist->userID]);
			if (isset($overallgradeDataArr[$userlist->userID]) && $overallgradeDataArr[$userlist->userID]>0) {
				if (($learnincurrancy=="") || ($learnincurrancy=="0")) {
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
			else
			{
				$learnincurrancy =0;
				$showlearningavg ="E2";
				$coloravg ="redr";
				$img_grade = 'images/red.png';
			}
			
			/*$classrankVAL  = array_search($userlist->userID, $classrankar); 
			$globalranks  = array_search($userlist->userID, $globalrankar);	*/
					
			//$globalrankskey = array_search($userlist->userID, array_column($globalrankar, 'userid'));
			$globalranks = isset($globalrankar_new[$userlist->userID])? $globalrankar_new[$userlist->userID]: 'N/A';
							
							
			//$aliasrankkey = array_search($userlist->userID, array_column($aliasrankar, 'userid'));
			//$aliasranks =$aliasrankar[$aliasrankkey]['rank'];
			$aliasranks = isset($aliasrankar_new[$userlist->userID])? $aliasrankar_new[$userlist->userID] : 'N/A';

			//$classrankkey = array_search($userlist->userID, array_column($classrankar, 'userid'));
			//$classrankVAL = $classrankar[$classrankkey]['rank'];
			$classrankVAL = isset($classrankar_new[$userlist->userID])? $classrankar_new[$userlist->userID]: 'N/A';

			
			//if ($rank!=0){					
			$html.= '<tr> 
					<td style="color:white;">'.$is.'</td>
					<td><span class="span_inline" style="color:#333;font-size:14px;"> <img src="'.$img_grade.'" alt="icon" /> &nbsp &nbsp <a href="edufun.php?id='.$userlist->userID.'" target="_blank">'.ucfirst($userlist->name).'  </a></span></td>';			
			if 	(count($schoolselectedarray)>1) { 		
					$html.= '<td><span>'.$userlist->school.'</span></td>';
				}	
			$html.= '
			
			<td><span>'.$userlist->grade.'</span></td>';
			if (($class_rank=="on") && ($classid!='')){
				if($classrankVAL == 'N/A')
				{
					$html.='<td class="orange"><span>N/A</span></td>';
				}
				else
				{
					$html.='<td class="dark" data-order='.$classrankVAL.'><span>'.$classrankVAL.'/'.$totalcrank.'</span></td>';
				}
			}	
			if ($global_rank=="on"){	
				if ($globalranks == 'N/A' ) {
					$html.='<td class="orange"><span>N/A</span></td>';
				} else {
					$html.='<td class="dark" data-order='.$globalranks.'><span>'.$globalranks.'/'.$totalgrank.'</span></td>';
				}			
			}	
			if ($alias_rank=="on"){
				if ($aliasranks == 'N/A') {
					$html.='<td class="orange"><span>N/A</span></td>';
				} else {
					$html.='<td class="dark" data-order='.$aliasranks.'><span>'.$aliasranks.'/'.$totalarank.'</span></td>';
				}	
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
	$html.= '</table>';	
	} else {
		$html.= '<table id="example1" class="table table-bordered table-striped">';
		$html.= '<thead><tr>';
		$html.= '<td style="color:red;" colspan="9">No Record Found</td>';
		$html.= '</tr>';
		$html.= '</table>';
	}
	$html.="</div></div></body>

			
		<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/buttons/1.6.1/css/buttons.bootstrap.min.css'>
			<script  src='https://code.jquery.com/jquery-3.3.1.js'></script>
			<script src='js/bootstrap.min.js'></script>	

			<script type='text/javascript' language='javascript' src='js/export/jquery.dataTables.min.js'></script>
			<script src='js/dataTables.bootstrap.min.js'></script>
			<script type='text/javascript' language='javascript' src='js/export/dataTables.buttons.min.js'></script>
			<script type='text/javascript' language='javascript' src='js/export/buttons.bootstrap.min.js'></script>
			<script type='text/javascript' language='javascript' src='js/export/jszip.min.js'></script>
			<script type='text/javascript' language='javascript' src='js/export/pdfmake.min.js'></script>
			<script type='text/javascript' language='javascript' src='js/export/vfs_fonts.js'></script>
			<script type='text/javascript' language='javascript' src='js/export/buttons.html5.min.js'></script>
			<script type='text/javascript' language='javascript' src='js/export/buttons.print.min.js'></script>
			<script type='text/javascript' language='javascript' src='js/export/buttons.colVis.min.js'></script>

			<script>
				$(function () {
					

					function getImageData() {
						var canvas = document.getElementsByClassName('canvasjs-chart-canvas');
						return dataURL = canvas[0].toDataURL();
					}

					var table = $('#example1').DataTable({
						select: true,
						'paging'      : true,
						'lengthChange': true,
						'searching'   : true,
						'ordering'    : true,
						'info'        : true,
						'autoWidth'   : true,
						buttons: [ 
							{
								extend: 'pdfHtml5',
								customize: function ( doc ) {
									doc.content.splice( 0, 0, {
										margin: [ 12, 12, 12, 12 ],
										alignment: 'center',
										image: getImageData() ,
										width: 550,
										height: 250
									} );
								},

								exportOptions: {
									columns: 'th:not(:first-child)'
								}
							}
						]
					} );
					datatableExample = table;
					table.buttons().container().appendTo( '#export_section' ).css({'display':'none', 'margin-right': '10px'});
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
	//$schoolname =isset($_POST['school_id'])?$_POST['school_id']:'';
	$schoollist =isset($_POST['school_id'])?$_POST['school_id']:'';
	//$schoolarray=explode("|",$schoollist);
	$schoolname = str_replace("|", "','",  $schoollist );
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
			<th> Name</th>';
		if 	(count($schoolselectedarray)>1) { 		
			$html.= '<th>School</th>';
		}	
			/*if ($class_rank=="on"){
				$html.= '<th>Class Rank</th>';
			}
			if ($global_rank=="on"){
				$html.= '<th>Global Rank </th>';
			}
			if ($alias_rank=="on"){
				$html.= '<th>'.$alias.' Rank</th>';
			}*/
			//$html.= '<th>Time Spent</th>';
			$html.= '<th>Subtopic Grade</th>	
			<th>Overall Grade</th>
		</tr>';
			
	$ids = $teacher->getuserResultIDs($classid,$subject_id,$schoolname,$state_id,$city_id,$subtopic);
	$user = $teacher->getuserResult($classid,$subject_id,$schoolname,$state_id,$city_id,$chapter_id,$subtopic);
	if($user){
		$count = 1;
	}
					 
	$html2.='<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6"></div>
				<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<span id="userresults"></span>
					<select name="downloadtype" id="downloadtype" class="form-control input-sm">
						<option value="csv">CSV</option>
						<option value="pdf">PDF</option>
						<option value="xls">XLS</option>
					</select>
					<input type="button" onclick="getdownload();"  id="btn" class="btn btn-success name="PDF" value="Export2" style="margin-left:20px;">
				</div>	
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
		$coloravgs='';
		$gradeid = Romannumeraltonumber($classid);
		/*$overallresult = $teacher->getrankuserResult($gradeid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id);
		$classrankar =  array();
		$totalcrank =0;
		while($classrank = mysqli_fetch_object($overallresult)){
			$classrankar[]=array('userid'=>$classrank->userid,'rank'=>$classrank->rank);
			$totalcrank++;
		}	
		
		
		$globalresult = $teacher->getglobalrankResult($gradeid,$schoolname,$city_id,$chapter_id,$subtopic,$subject_id);	
		$globalrankar =  array();
		$totalgrank =0;
		while($globalrank = mysqli_fetch_object($globalresult)){
			$globalrankar[]=array('userid'=>$globalrank->userid,'rank'=>$globalrank->rank);
			$totalgrank++;
		}
		
		$aliasresult = $teacher->getaliasrankResult($gradeid,$schoolarray,$city_id,$chapter_id,$subtopic,$subject_id);	
		$aliasrankar =  array();
		$totalarank =0;
		while($aliasrank = mysqli_fetch_object($aliasresult)){
			$aliasrankar[]=array('userid'=>$aliasrank->userid,'rank'=>$aliasrank->rank);
			$totalarank++;
		}
		*/
		
		while($userlist = mysqli_fetch_object($user)){
			$rowskill = mysqli_fetch_object($resultskill);
			$skillid=$rowskill->skillID;
			$resultlearningaverage = $teacher->getlearningResultAverage($chapter_id,$school_id,$classid,$subject_id,$state_id,$city_id,$subtopic,$userlist->userID);
						
			$rowlearningaveareg=mysqli_fetch_object($resultlearningaverage);
			$learningaverage =$rowlearningaveareg->learningavg;
			if (mysqli_num_rows($resultlearningaverage)>0) {
				if (($rowlearningaveareg->learningavg=="") || ($rowlearningaveareg->learningavg=="0") ) {
					$learningaverage =0;
					$showlearningavgs ="E2";
					$coloravgs ="red";
				} else {
					if ($learningaverage>=90){
						$showlearningavgs ="A1";
						$coloravgs ="greenr12";
					} else if (($learningaverage>=80) && ($learningaverage<90)){
						$showlearningavgs ="A2";
						$coloravgs ="greenr2";
					} else if (($learningaverage>=70) && ($learningaverage<80)){
						$showlearningavgs ="B1";
						$coloravgs ="yello1";
					} else if (($learningaverage>=60) && ($learningaverage<70)){
						$showlearningavgs ="B2";
						$coloravgs ="yello2";
					} else if (($learningaverage>=50) && ($learningaverage<60)){
						$showlearningavgs ="C1";
						$coloravgs ="oran1";
					} else if (($learningaverage>=40) && ($learningaverage<50)){
						$showlearningavgs ="C2";
						$coloravgs ="oran2";
					} else if (($learningaverage>=30) && ($learningaverage<40)){
						$showlearningavgs ="D";
						$coloravgs ="blue2";
					} else if (($learningaverage>=20) && ($learningaverage<30)){
						$showlearningavgs ="E1";
						$coloravgs ="red";
					} else if (($learningaverage>=0) && ($learningaverage<20)){
						$showlearningavgs ="E2";
						$coloravgs ="red";
					} 
					
					
				$overallavgcount++;
				}
				$overalllavgearning =$overalllavgearning+$learningaverage;
			} else {
				$learningaverage =0;
				$showlearningavgs ="E2";
				$coloravgs ="red";
			}	
		$htmls='<td><span class='.$coloravgs.'>'.$showlearningavgs.'</span></td>';
			$learnincurrancy =0;
			$coloravg='';
			$img_grade = '';
			$subtopicid='';
			$overallgrade = $teacher->getoverallGrade($userlist->userID,$subtopicid,$subject_id);	
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
			/*$globalrankskey = array_search($userlist->userID, array_column($globalrankar, 'userid'));
			$globalranks =$globalrankar[$globalrankskey]['rank'];
							
							
			$classrankkey = array_search($userlist->userID, array_column($classrankar, 'userid'));
			$classrankVAL =$classrankar[$classrankkey]['rank'];

									
			$aliasrankkey = array_search($userlist->userID, array_column($aliasrankar, 'userid'));
			$aliasranks =$aliasrankar[$aliasrankkey]['rank'];	*/
		
			//if ($rank!=0){
				$html.= '<tr>
			    <td><span class="span_inline">&nbsp &nbsp '.$count++.' </span></td>
				<td><span class="span_inline"><img src="'.$img_grade.'" alt="icon" /> &nbsp &nbsp <a href="edufun.php?id='.$userlist->userID.'" target="_blank">'.$userlist->name.' </a></span></td>';
				if 	(count($schoolselectedarray)>1) { 		
					$html.= '<td>'.$userlist->school.'</td>';
				}	
				/*if ($class_rank=="on"){
					if ($classrankVAL>0) {
						$html.='<td class="dark"><span>'.$classrankVAL.'/'.$totalcrank.'</span></td>';
					} else{
						$html.='<td class="orange"><span>N/A</span></td>';
					}	
				}
				if ($global_rank=="on"){	
					if ($globalranks>0) {
						$html.='<td class="dark"><span>'.$globalranks.'/'.$totalgrank.'</span></td>';
					} else {
						$html.='<td class="orange"><span>N/A</span></td>';
					}		
				}
				if ($alias_rank=="on"){
					if ($aliasranks>0) {
						$html.='<td class="dark"><span>'.$aliasranks.'/'.$totalarank.'</span></td>';
					} else {
						$html.='<td class="orange"><span>N/A</span></td>';
					}		
				}	
				$timeresult = $teacher->getrankTimestamp($userlist->userID);			    
				$Totaltime = mysqli_fetch_object($timeresult);
				$html.= '<td class="orange"><span><ul class="time-inline">'.$Totaltime->time.'</ul></span></td>';*/
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