<?php
	session_start();
	include('db_config.php');
	include('configs.php');
	
	global $config;
	global $conn;
	require("sendgrid-php/sendgrid-php.php");
	$sendgrid = new SendGrid("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");
	include('model/Teacher.class.php');
	$teacher = new Teacher();
	// $sql="SELECT * FROM  user_school_details";
	// $query = mysqli_query($conn,$sql);
	// $count='0';
	// while($obj = mysqli_fetch_object($query)){
		// $cityname=$obj->cityname;
		// $sqlcity="SELECT * FROM  tbl_cities WHERE name='$cityname'";
		// $querycity = mysqli_query($conn,$sqlcity);
		// $objcity = mysqli_fetch_object($querycity);
		// $city_id=$objcity ->id;
		// $sqlcityupdatwe="UPDATE user_school_details SET city_id='$city_id'  WHERE cityname='$cityname'";
		// $querycityup = mysqli_query($conn,$sqlcityupdatwe);
		
	// }
	
	
   if(isset($_POST['submit']))
	{
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			$select = "select * from teacher_login WHERE userID='$username' and password='$password'";
			$query = mysqli_query($conn,$select);
			$obj = mysqli_fetch_object($query);
			$usernameget = isset($obj->userID)?$obj->userID:'';
			$passwordget = isset($obj->password)?$obj->password:'';
			
		if($username == $usernameget && $password == $passwordget){
			 $_SESSION['uid']=$username;
			echo "<script>window.open('index.php','_self')</script>";
		}else{
			$error="<div class='alert alert-danger' style='text-align: center;
			font-size: 18px;    width: 525px;
			margin-left: 50px;'>Invalid username or password !!</div>";
		}

	}
	if(isset($_POST['submitt']))
	{
		$userID = $_POST['userID'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$phone = $_POST['phone'];
		$city_id = $_POST['city'];
		$school = $_POST['school'];
		$state_id = $_POST['state'];
		
		$usertype = $_POST['usertype'];
		$usernameid =$userID; 		
		$query="SELECT * FROM teacher_login WHERE userID='$usernameid' "; 
		$result=mysqli_query($conn,$query);
		$anycount=$result->num_rows ;
		if($anycount>0){
			$row=mysqli_fetch_object($result);
			$getuserID=$row->userID;
			if ($usernameid==$getuserID){
				echo '0';
			} 
	}else {
	
	$sql = "INSERT INTO teacher_login(userID,name,email,password,phone,usertype,status,city_id,state_id,school) VALUES ('$userID','$name','$email','$password','$phone','$usertype','0','$city_id','$state_id','$school')";
		$result = mysqli_query($conn,$sql);
	
		if($result==0){
			$success = "<div class='alert alert-success'>Reistration has been complete please check registered email and phone</div>";
		}else{
			$success = "<div class='alert alert-warning'>Can not register right now, Please try after some time<div>";
		}
	//Code for send SMS
	 $mobilenumbers=$phone;
	 $mess='Dear '.$name.', You registration for Eduisfun has been complete for more details plese check your reistered email.';
	
					sendSms($mobilenumbers,$mess);
		
		            $message=$config['EMAIL_HEADER'];
					$message.='<div style="color:#fff;padding:10px;background: linear-gradient(to right, #12183d, #2d638e, #12183d);">';
					$message.='Dear '.$name.', <br>'; 
					$message.='<p>Thank you for choosing us, Your details are mentioned below:</p>';
					$message.='<p>User Name: '.$userID.'</p>';
					$message.='<p>Password: '.$password.'</p>';
					$message.='</div>';
					$message.=$config['EMAIL_FOOTER'];
					$api_key=$config['APIKEY'];
					$form_name='Eduisfun';
					$form_email=$config['FROMEMAIL'];
					$text =$message;
					$emailID=$email;
					$subject = 'ENQUIRY';
					$adminemail=$config['ADMIN'];
					$sent=sendMail($emailID, $subject, $text);
		
		if($sent){
			$success = "<div class='alert alert-success'>Registration has been complete. Please check email. Thank you</div>";
			
		}
		else{
			$success = "<div class='alert alert-warning'>Can not register right now, Please try after some time.</div>";
		}
	}
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title> Dashboard</title>
	<meta charset="UTF-8">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Favicon -->   
	<link href="img/favicon.ico" rel="shortcut icon"/>

	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/font-awesome.css" /> 
	
	<link rel="stylesheet" href="css/style.css"/>  
	 
  
</head>
<body>
<!-- header -->
<div class="header-top">
	<div class="container-fluid"> 
			 <div class="row">
				<div class="col-md-2 logo">
					<a href="#"><img src="images/logo.png"></a> 
				</div> 
			 
			 </div>
	</div>
</div>
<!-- header -->

<!-- Dashboard -->
	  
<div class="container-fluid"> 

    <div class="row"> 
		<div class="col-md-6 col-md-offset-3"> 
		<?php
			  if(!empty($success)){
				  echo $success;
			  }
			?>

			<div class="login-container">
			  <div class="card"></div>
			  <div class="card">
				<h1 class="title">Login</h1>
				<?php
					  if(!empty($error)){
						  echo $error;
						}
				?>
				<form method="POST">
				  <div class="input-container">
					<input type="text" name="username" required="required"/>
					<label>Username</label>
					<div class="bar"></div>
				  </div>
				  <div class="input-container">
					<input type="password" name="password" required="required"/>
					<label>Password</label>
					<div class="bar"></div>
				  </div>
				  <div class="button-container">
					<input type="submit" name="submit" value="Login" >
				  </div>
				 <!-- <div class="footer"><a href="forgotpassword.php">Forgot your password?</a></div>-->
				</form>
			  </div>
			  <div class="card alt">
				<div class="toggle"></div>
				<h1 class="title">Register
				  <div class="close"></div>
				</h1>
				<form method="POST">
				  <div class="input-container">
					<input type="text" name="userID" id="userID" required="required"/>
					<label>Username</label>
					<div class="bar"></div>
				  </div>
				  <div class="input-container">
					<input type="text" name="name" id="name" required="required"/>
					<label>Name</label>
					<div class="bar"></div>
				  </div>
				  <div class="input-container">
					<input type="text" name="email" id="email" required="required"/>
					<label>Email</label>
					<div class="bar"></div>
				  </div>
				   
				  <div class="input-container">
					<input type="password" name="password" id="password" required="required"/>
					<label>Password</label>
					<div class="bar"></div>
				  </div>
				  <div class="input-container">
					<input type="text" name="phone" id="phone" required="required"/>
					<label>Phone</label>
					<div class="bar"></div>
				  </div>
				  <div class="input-container">
					<select name='usertype' id='usertype'>
					  <option value="" hidden="">User Type</option>
					  <option value="principle">Principal</option>
					  <option value="teacher">Teacher</option>
					  <option value="staff">Staff</option>
					</select>
					
					
					<div class="bar"></div>
				  </div>
				  <div class="input-container">
					
									<select name="state" id="state" onchange="ChangeCity(this.value);">
									<option value="">State</option>
									<?php 
									$countryid =101;	
									$result = $teacher->getStateResult($countryid);
									while($obj=mysqli_fetch_object($result)):			
									?>
									<option value="<?php echo $obj->id?>" <?php if(!empty($_POST['state'])){ echo 'selected';} ?>><?php echo $obj->name?></option>
									<?php endwhile; ?>
									</select>
								
					
					<div class="bar"></div>
				  </div>
				  <div class="input-container">

									<select name= "city" id="city" onchange="changeBoardName(this.value);">
									<option value=""> Choose City</option>
									</select>
								
				 <div class="bar"></div>
				  </div>
				  <div class="input-container">
				  <select name="board" id="board" onchange="changeBoard(this.value);">
									<option value="">Board</option>
									
									<?php 
										$result = $teacher->getBoardResult();
										while($obj=mysqli_fetch_object($result)):		
									?>
									<option value="<?php echo $obj->id?>"><?php echo $obj->board?></option>
									<?php endwhile; ?>
									 <option value="All">Other</option>
									</select>
				   <div class="bar"></div>
				  </div>
				  <div class="input-container">
				  <div class="select top_input">
									<select name="school" id="school" onchange = "changeSchool(this.value);">
									<option value="">School</option>
									</select>
								</div>
				  <div class="bar"></div>
				  </div>
				  
				  <div class="button-container">
					<input onclick="return formsubmit()" type="submit" name="submitt" id="submitt" value="Register" >
				  </div>
				</form>
			  </div>
			</div>

		</div>
	</div>
</div>
 
<!-- end -->
<script>


function ChangeCity(city_name)
	{	 
		$("#board").val('');	
		$('#school').find('option').remove().end().append('<option value="">School</option>');

		$.ajax({
			type:"POST",
			cache:false,
			url:"action.php",
			data: "action=getCity&city_id="+ city_name,
			success:function(response){
				$("#city").html(response);
				//console.log('response'+ response);
			}
		});
	
	
	}

 

function changeBoardName()
	{	 
		 $("#board").val('');	
		 $('#school').find('option').remove().end().append('<option value="">School</option>');

	}


function changeBoard(board){
	
	$('#school').find('option').remove().end().append('<option value="">School</option>');
	var city = $("#city option:selected").val();
	
	$.ajax({
		type:"POST",
		cache:false,
		url:"action.php",
		data: "action=getBoard&board_id="+board+"&city="+city,
		success:function(response){
			$("#school").html(response);
			//console.log('response'+ response);
			
		}
	});
	
}



function formsubmit(){
        var userID = $('#userID').val();
		var name = $('#name').val();
		var email= $('#email').val();
		var password = $('#password').val();
		var phone = $('#phone').val();
		var usertype = $('#usertype').val();

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
		
	if(usertype ==""){
			alert("Select user type");
			$('#usertype').focus();
			return false;
		}
}
</script>

<!-- jQuery library -->
<script src="js/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>