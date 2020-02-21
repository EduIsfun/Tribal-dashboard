<?php
session_start();
include('db_config.php');
if(empty($_SESSION['adminuids'])){
	header('Location:index.php');
}
$currentUser  = $_SESSION['currentuser'];
include('model/Teacher.class.php');
$teacher = new Teacher();
if(isset($_POST['update'])){
	$username = $_POST['username'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['mobile'];
	$city_id = $_POST['city'];
	$state_id = $_POST['state'];
	$usertype = $_POST['usertype'];
	//$usernameid =$userID; 		
	$classstd= 	implode(",",$_POST['classstd']);
	$school= 	implode("|",$_POST['school']);
	$mainschool = $_POST['mainschool'];
	$spermission = $_POST['spermission'];
	$board = $_POST['board'];
	$password = $_POST['password'];
	$alias = $_POST['alias'];
	if ($_FILES["logo"]["name"]!=''){
		$target_dir = "images/";
		$filename = time()."_".basename($_FILES["logo"]["name"]);
		$target_file = $target_dir.$filename;
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["logo"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$error= "File is not an image.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["logo"]["size"] > 50000000) {
				$error= "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
			$error="Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		
		if ($uploadOk == 0) {
			$error="Sorry, your file was not uploaded.";
		} else {
			move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);
		}	
	
	} else {
		$filename ='';
	}
	$chkquery="SELECT * FROM teacher_login WHERE userID='$username' and id !='".$_GET['id']."'"; 
	$resultchk=mysqli_query($conn,$chkquery);
	$anycount=$resultchk->num_rows ;
	if($anycount>0){
		$rowchk=mysqli_fetch_object($resultchk);
		$getuserID=$rowchk->userID;
		if ($username==$getuserID){
			$error="Username already exists !!";
			
		} 
	} else {
	$query="UPDATE teacher_login set userID='".$username."', name='".$name."',email='".$email."',phone='".$phone."',password='".$password."',
	usertype='".$usertype."',board_id='".$board."',classstd='".$classstd."',city_id='".$city_id."',state_id='".$state_id."',school='".$school."' ,
	main_school='".$mainschool."' ,switch_permission='".$spermission."' ,alias='".$alias."' ";
	if ($filename!=""){
		$query.=",schoollogo='".$filename."' ";	
	}
	$query.=" WHERE id ='".$_GET['id']."'"; 
	//echo $query;
	$result = mysqli_query($conn,$query);
	if($result){
		exit("<script>window.location.href='user_profile.php?id=".$_GET['id']."&msg=updated';</script>");
	}else{
	   echo "There is some problem in inserting record";
	}
	}	
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>Dashboard</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/form.css">
</head>
<body onload="ChangeCity();changeBoardName();">
<?php include('header_admin.php');?>

<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3"> 
			<div class="top_head">
				<fieldset>
				<!-- Form Name -->
				<?php if (isset($error) && ($error!='')){ ?>
				<div class="alert alert-danger">
					<strong><?php echo $error;?></strong> 
				</div>
				<?php } ?>
				<h3 class="text-center">Modify Your profile ?</h3>
				
				<!-- Text input-->
				<form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data"> 
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>Username:</strong></div>
							<input id="username" name="username" type="text"  value="<?php echo $currentUser->userID?>" class="form-control input-md" required>
						</div>
					</div>
				</div>
					<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>Name:</strong></div>
							<input id="Name" name="name" type="text"  value="<?php echo $currentUser->name?>" class="form-control input-md" required>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>Email:</strong></div>
							<input id="email" name="email" type="text" value="<?php echo $currentUser->email?>" class="form-control input-md" required>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>Phone:</strong></div>
							<input id="mobile" name="mobile" type="text" value="<?php echo $currentUser->phone?>" class="form-control input-md"  required>
						</div>
					</div>
				</div>
					<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>Password:</strong>	<i id="pass-status" class="fa fa-eye" aria-hidden="true" onClick="viewPassword()"></i></div>
							<input id="password" name="password" type="password" value="<?php echo $currentUser->password?>" class="form-control input-md"  required>
						</div>
						
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>Board:</strong></div>
							<select name="board" id="board" onchange="changeBoardName();" class="form-control input-md">
							<option value="">Board</option>
							<?php 
								$result = $teacher->getBoardResult();
								while($obj=mysqli_fetch_object($result)):		
							?>
								<option value="<?php echo $obj->id?>" <?php if($currentUser->board_id==$obj->id) {echo "selected";}?> ><?php echo $obj->board?></option>
							<?php endwhile; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>Class:</strong></div>
							<select name="classstd[]" id="classstd" required multiple class="form-control input-md">
							<option value="">Class</option>
							<?php 
								$classarray=explode(",",$currentUser->classstd);
								$classresult = $teacher->getclassResult();
								while($objclass = mysqli_fetch_object($classresult)){
									//$clsID = ConverToRoman($objclass->id);		
								?>
								<option value="<?php echo $objclass->id?>" <?php if (in_array($objclass->id, $classarray)) {echo "selected";}?>><?php echo $objclass->id?></option>
							<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>User Type:</strong></div>
							<select name='usertype' id='usertype' class="form-control input-md">
							<option value="principal" <?php if($currentUser->usertype=="principal") {echo "selected";}?> >Principal</option>
							<option value="teacher" <?php if($currentUser->usertype=="teacher") {echo "selected";}?>>Teacher</option>
							<option value="staff" <?php if($currentUser->usertype=="staff") {echo "selected";}?>>Staff</option>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>State:</strong></div>
							<select name="state" id="state" onchange="ChangeCity();" class="form-control input-md">
							<option value="">State</option>
							<?php 
								$countryid =101;	
								$result = $teacher->getStateResult($countryid);
								while($obj=mysqli_fetch_object($result)):			
							?>
								<option value="<?php echo $obj->id?>" <?php if($currentUser->state_id==$obj->id){ echo 'selected';} ?>><?php echo $obj->name?></option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>City:</strong></div>
							<select name= "city" id="city" onchange="changeBoardName();" class="form-control input-md">
							<option value=""> Choose City</option>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>Main School:</strong></div>
							<select name="mainschool" id="mainschool" class="form-control input-md">
								<option value="">School</option>
								</select>
						</div>
					</div>
				</div>		
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>Grouped School:</strong><br>(Exclude Main school)</div>
							<select name="school[]" id="school" multiple required class="form-control input-md">
								<option value="">School</option>
								</select>
						</div>
					</div>
				</div>		
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>Alias:</strong></div>
							<input id="alias" name="alias" type="text" value="<?php echo $currentUser->alias?>" class="form-control input-md"  required>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group" >
							<div class="input-group-addon"><strong>Switch Permission:</strong></div>
							<div class="form-control input-md"><input type ="checkbox" name ="spermission" 							<?php if ($currentUser->switch_permission=="on"){ echo "checked";}?>></div>
						</div>
					</div>
				</div>		
				
				<div class="form-group">
					<div class="col-md-12">
						<div class="input-group">
							<div class="input-group-addon"><strong>Logo:</strong></div>
							<input id="logo" name="logo" type="file" class="form-control">
						</div>
					</div>
				</div>
				<?php if ($currentUser->schoollogo!=''){	?>
				<div class="form-group">
					<div class="col-md-12">
								<img src="images/<?php echo $currentUser->schoollogo;?>" width="100" height="100" >
					
					</div>
				</div>
				<?php } ?>
				<div class="form-group">
					<div class="col-md-4">
						<div class="edit_btn">  
							<input onclick="return formsubmit()" type="submit"  class="btn btn-primary" name="update" value="Update">
						</div>
					</div>
				</div>
				</form>
				</fieldset>
			</div>
		</div>
	</div>
</div>
<script>

function ChangeCity(){	 
	var state_id= $("#state").val();	
	var	cityid	= <?php echo $currentUser->city_id;?>;	
	$('#school').find('option').remove().end().append('<option value="">School</option>');
	$.ajax({
		type:"POST",
		cache:false,
		url:"action.php",
		data: "action=getCity&state_id="+ state_id+"&cityid="+cityid,
		success:function(response){
			$("#city").html(response);
			//console.log('response'+ response);
		}
	});
}

function changeBoardName(){
	$('#school').find('option').remove().end().append('<option value="">School</option>');
	var newcity = $("#city option:selected").val();
	var oldcity ="<?php echo $currentUser->city_id;?>";
	var city = newcity;
	if (newcity==""){
		var city = oldcity;
	}	
	var board_id=$("#board").val();	
	var selectedschool="<?php echo $currentUser->school;?>";
	$.ajax({
		type:"POST",
		cache:false,
		url:"action.php",
		data: "action=getBoard&board_id="+board_id+"&city="+city+"&selectedvalue="+selectedschool,
		success:function(response){
			$("#school").html(response);
			$("#mainschool").html(response);
			console.log('response'+ response);
			
		}
	});
	
}

function formsubmit(){
    var userID = $('#userID').val();
	var name = $('#name').val();
	var email= $('#email').val();
	var password = $('#password').val();
	var phone = $('#mobile').val();
	var usertype = $('#usertype').val();
	var classstd = $("#classstd").val();
	var schoolname = $("#school").val();
	var mainschoolname = $("#mainschool").val();
	var board = $("#board").val();
	
	if(userID ==""){
		alert("Enter Your User Name");
		$('#userID').focus();
		return false;
	}
	if(name ==""){
		alert("Enter Your Full Name");
		$('#name').focus();
		return false;
	}
	if (!/^[a-zA-Z ]*$/g.test(name)) {
		alert("Only character allowed in name");
		$('#name').focus();
		return false;
	}
	if(email == ""){
		alert("Enter Your Email ");
		$('#email').focus();
		return false;
	}
	atpos = email.indexOf("@");
	dotpos = email.lastIndexOf(".");
	if (atpos < 1 || ( dotpos - atpos < 2 ))  {
		alert("Please enter correct email ID like xyz@zyz.com ")
		$('#email').focus();
		return false;
	}
		if(password == ""){
		alert("Enter Your Password");
		return false;
	}
	if (password.length < 6) {
        alert("Password length must at least 6 character");
        $('#password').focus();
		return false;
    }
	if (password.search(/\d/) == -1) {
        alert("Password contain should be one numeric");
        $('#passwords').focus();
		return false;
    }
	if (password.search(/[a-zA-Z]/) == -1) {
        alert("Password contain should be one alphabet");
        $('#passwords').focus();
		return false;
    }
	if(phone == ""){
		alert("Enter Your Phone Number");
		return false;
	}
	if(isNaN(phone)) {
		alert( "Mobile Number must be only digit" );
		$('#phone').focus();
		return false;
	}
	if(phone.length !=10) {
		alert( "Mobile Number must be 10 digit" );
		$('#phone').focus();
		return false;
	}
	if(board ==""){
		alert("Select Board");
		$('#board').focus();
		return false;
	}
	if(!(classstd)){
		alert("Please select class");
		$('#classstd').focus();
		return false;
	}
	if(usertype ==""){
		alert("Select user type");
		$('#usertype').focus();
		return false;
	}
	if(!(schoolname)){
		alert("Please select school");
		$('#schoolname').focus();
		return false;
	}
	
	/*if(!(mainschoolname)){
		alert("Please select main school");
		$('#schoolname').focus();
		return false;
	}*/
	

	
}
function viewPassword() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
} 
</script>
<!-- jQuery Version 1.11.1 -->
<script src="js/jquery.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>