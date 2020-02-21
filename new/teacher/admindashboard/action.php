<?php

	include('db_config.php');
	include('model/Teacher.class.php');
	global $conn;
	$teacher = new Teacher();

		$res = "";
		if(isset($_POST['id'])){
				$cityid = $_POST['id'];
				$sql = "select * from tbl_cities where state_id = $cityid";
				$result = mysqli_query($conn,$sql);
			while($row = mysqli_fetch_object($result))
			{
				$res .= "<option value='".$row->id."'>".$row->name."</option>";  
			}
				echo $res;
				die();
		}

		$ress = "";
		if(isset($_POST['schoolname'])){
				$school_id = $_POST['schoolname'];
				$sqls = "select * from user_school_details where states_id = $school_id";
				$results = mysqli_query($conn,$sqls);
			while($rows = mysqli_fetch_object($results))
			{
				$ress .= "<option value='".$rows->school."'>".$rows->school."</option>";  
			}
				echo $ress;
				die();
		}
		
		$output = "";
		if(isset($_POST['subjectname'])){
			 $subject_id = $_POST['subjectname'];
			 $classid   = $_POST['classid'];
			 echo $sql = "SELECT * FROM chapter WHERE subjectID = $subject_id AND grade = $classid  GROUP BY chapter ";
			 $result = mysqli_query($conn,$sql);
			 $output.="<option value=>Choose Chapter</option>";
			 while($obj = mysqli_fetch_object($result))
			 {
				$output .= "<option value='".$obj->chapterID."'>".ucfirst($obj->chapter)."</option>";
			 }
				echo $output;
				die();
		}
		
		$outputs = "";
		if(isset($_POST['boardname'])){
			$city = $_POST['city'];
			$board_id = $_POST['boardname'];
			$outputs .='<option value="">School</option>';
			if ($board_id == 'All' ){
				echo $sql ="SELECT * FROM user_school_details WHERE city_id = '$city' GROUP BY school ";
				
			}else if(!empty($city) && $board_id==''){
				$sql ="SELECT * FROM user_school_details WHERE  city_id ='$city' OR board_id='' GROUP BY school";
		
			}else{
				 $sql ="SELECT * FROM user_school_details WHERE  city_id ='$city' AND board_id='$board_id' GROUP BY school ";
			}
			
					$result = mysqli_query($conn,$sql);
					while($obj = mysqli_fetch_object($result))
					{
							$outputs .="<option value='".$obj->school."'>".ucfirst($obj->school)."</option>";
					}
							echo $outputs;
							die();
		}
		
		$subout = "";
		
		if(isset($_POST['subchapterid'])){
			
			$subchapterid = $_POST['subchapterid'];
			
			echo $sql = "SELECT * FROM knowledgekombat_skill WHERE chapterID = '$subchapterid'";
			$subresult = mysqli_query($conn,$sql);
			$subout.="<option value=>Choose Topic</option>";
			while($subobj = mysqli_fetch_object($subresult))
			{
				$subout .="<option value='".$subobj->chapterID."'>".ucfirst($subobj->skill)."</option>";
			}
				echo $subout;
				die();
			
		}
	
?>