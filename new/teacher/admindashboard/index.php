<?php
	session_start();
	include('db_config.php');
   // $sql="SELECT * FROM user_school_details ";
	 // echo '<br>';
	// $query = mysqli_query($conn,$sql);
	// $count='0';
	// while($obj = mysqli_fetch_object($query)){
		
	   // $user_school_city_id=$obj->city_id;
	   // $user_school_name=$obj->school;
	   
	    // echo $sqlcity="SELECT * FROM  school_details WHERE city_id='$user_school_city_id' AND school='$user_school_name'";
		// $querycity = mysqli_query($conn,$sqlcity);
		 // echo '<br>';
		// $objcity = mysqli_fetch_object($querycity);
		// $school_id=$objcity->id;
		// echo $sqlcityupdatwe="UPDATE user_school_details SET school_id='$school_id'  WHERE city_id='$user_school_city_id' AND school='$user_school_name'";
		// $querycityup = mysqli_query($conn,$sqlcityupdatwe);
		 // echo '<br>';
		
	// }
	 // $sql="SELECT * FROM user_school_details LIMIT 10 ";
	
	// $query = mysqli_query($conn,$sql);
	// $count='0';
	// $city='';
	// $city_id='';
	// $querycity='';
	// while($obj = mysqli_fetch_object($query)){
	// $school=$obj->school;
	// $city_id=$obj->city_id;
	
	    // echo $sqlcity="SELECT * FROM  school_details WHERE city_id='2707' AND school='DAV International School'";
		// $querycity = mysqli_query($conn,$sqlcity);
		// $objcity = mysqli_fetch_object($querycity);
		// echo $school_id=$objcity->id;
		 // echo $sqlcityupdatwe="UPDATE user_school_details SET school_id='$school_id'  WHERE city_id='2707' AND school='DAV International School'";
		// $querycityup = mysqli_query($conn,$sqlcityupdatwe);
		// echo '<br>';
		
	// }
	
   if(isset($_POST['submit']))
	{
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			$select = "select * from teacher_password WHERE email='$username' and password='$password'";
			$query = mysqli_query($conn,$select);
			$obj = mysqli_fetch_object($query);
			$usernameget = isset($obj->email)?$obj->email:'';
			$passwordget = isset($obj->password)?$obj->password:'';
			
		if($username == $usernameget && $password == $passwordget){
			 $_SESSION['uid']=$username;
			echo "<script>window.open('teacherdashboard.php','_self')</script>";
		}else{
			$error='<p style="color:red;font-size:18px;">Invalid username or password</p>';
		}

	}	
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>EduFun</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="css/style1.css" rel="stylesheet">
</head>
<body>
<div class="sb_login">
	<div class="container">
		<div class="row">
			<div class="col-md-5 col-md-offset-4">
				<div class="login text-center">
				<?php if(!empty($error)){
					   echo $error;
				}  ?>
				<img src="images/logo.png" alt="">
					<form method="post">
						<input type="text" name="username"  class="sb_input-form" placeholder="Username" required="required" />
						<input type="password" name="password" class="sb_input-form"placeholder="Password" required="required" />
						
					
						<!--<div class="Sb_left">
							<label class="checkbox">
							<input type="checkbox" value="remember-me">Remember Me
							</label>
						</div>-->
							<div class="Sb_right">
							<p class="omb_forgotPwd">
							<!--<a href="forgotpassword.php">Forgot password?</a>-->
							</p>
							</div>
						<input type="submit" name="submit" value="Login" class="btn btn-primary sb_login-btn">
						<!--<a href="register.php">Register.php</a>-->
								<!--<button class="btn btn-custom btn-lg" data-toggle="modal" data-target="#dispatchModal" style="height: 50px;">Register </button>-->
				</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- popup-->
   <div id="dispatchModal" class="modal fade in" role="dialog">
		<div class="modal-dialog">
			
			<!-- Modal content-->
			<!--<div class="modal-content row">
				<div class="modal-header custom-modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<span id="seleteresult"></span>
					<h4 class="modal-title">Enquire Now</h4>
				</div>
				<div class="modal-body">
					<form  class="form-inline"" method="post">
						<div class="form-group col-sm-12">
							<input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
						</div>
						<div class="form-group col-sm-12">
							<input type="email" class="form-control" name="email" id="email" placeholder="Enter Email">
						</div>
						 
						 
						<div class="form-group col-sm-12">
							<input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter mobile">
						</div>
						
												 
						<div class="form-group col-md-12">
							<input type="password" class="form-control" name="pass" id="pass" placeholder="Enter password">
						</div>
						
						<div class="form-group col-sm-12">
						
							<button type="button"  class="btn btn-custom btn-lg" onclick="Register()" >Submit</button>
						</div>
					</form>
				</div>
				
			</div>-->
			
		</div>
	</div>
   <!-- end -->
<script>
// function  Register(){
// alert("hello");
// var name = $('#name').val();
// var email = $('#email').val();
// var mobile = $('#mobile').val();
// var pass = $('#pass').val();
	// $.ajax({
	// type: "POST",
	// cache:false,
	// url: "action.php",
	// data: "action=getscheme&name="+ name+"&email="+ email+"&mobile="+ mobile+"&pass="+ pass ,
	// success: function(result){

		// if(result=='0'){
				// alert("There is an error send quote this time ");
		// }else{
				// $("#seleteresult").html('Request has been successfully sent, Support team will contact you sortly');
		// }

	// }

// });
// }
   
</script>
</body>
</html>


