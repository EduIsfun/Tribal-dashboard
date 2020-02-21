<?php
	session_start();
	include('db_config.php');
	if(empty($_SESSION['currentusernew'])){
		header('Location:index.php');
	}
	$currentUser  =$_SESSION['currentusernew'];	
	
	
	 if(isset($_POST['submit'])){
	    $c = $_SESSION['userID'];
		$old_pass = $_POST['cpass'];
        $new_pass = $_POST['newpass'];
        $re_pass =  $_POST['repass'];
		$result = "select * from user_password where password='$old_pass'";
		
		$run=mysqli_query($conn,$result);
		
		$check_pass=mysqli_num_rows($run);
		
		if($check_pass==0){
			
		  header('location:change.php?id=try');
		   exit();
		}
		if($new_pass!=$re_pass)
		
	   {
			header('location:change.php?id=notmatch');
		
		}
		
		else
		{
			$update= "update user_password set password='$new_pass' where password='$old_pass'";
			//$update= "update login set password='$pass' where username='$c'";
			
			$run_pass=mysqli_query($conn,$update);
			header('location:change.php?id=suc');
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

    <title>EduisFun</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/form.css">

</head>
<script>   
	function validateForm() {    
	
		
		if (document.registerform.cpass.value == "") {
			alert("Current password should not blank");
				document.registerform.cpass.focus();
				return false;
		}
		if (document.registerform.newpass.value == "") {
			alert("New password should not blank");
				document.registerform.newpass.focus();
				return false;
		}
		if (document.registerform.repass.value == "") {
			alert("Confrim password should not blank");
				document.registerform.repass.focus();
				return false;
		}
		if (document.registerform.newpass.value !== document.registerform.repass.value) {
			alert("Password and Confrim password  not match");
				document.registerform.repass.focus();
				return false;
		}
		
			
				
	}
		</script>
<body>
<div class="top_background">
<div class="container-fluid">

        <nav class="navbar navbar-default nav_height">
            
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
					<a href="edufun.php"><img src="images/logo.png"></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse Sb-navbar_padd ">
                    <ul class="nav navbar-nav sb_navbar_top" id="navPrincipal">
                       <!-- <li><a href="dashboard.php"><span><img src="images/left_arrow.png"></span>go back</a></li>-->
                      
                       <li><a href="edufun.php"><span><img src="images/home.png"></span>Dashboard</a></li>
                       
                       <li><a href="profile.php"><span><img src="images/profile.png" ></span>PROFILE</a></li>
                       
                        <li><a href="change.php"><span><img src="images/setting.png"></span>Change Password</a></li>	<li><a href="logout.php"><i class="fa fa-power-off" aria-hidden="true" style="margin-right:20px;"></i>Logout</a></li>
                     
                    </ul>
                </div>
            
        </nav>
		</div>
</div>

<div class="container">
<div class="row">
<div class="col-md-6 col-md-offset-3 ">
<div class="form-horizontal">
<fieldset>

<!-- Form Name -->
 
<div class="top_head">
		<h3>Change password ?</h3>
		</div>
        <?php
				if(isset($_GET['id']) && $_GET['id']=='try'){
				    echo '<p style="color:red;text-align:center;font-size:20px;margin-left: 85px;">Incorrect existing password</p>';
				}
				if(isset($_GET['id']) && $_GET['id']=='notmatch'){
				   echo '<p style="color:red;text-align:center;font-size:20px;margin-left: 85px;">New password and confirm password not match.</p>';
				}	
				if(isset($_GET['id']) && $_GET['id']=='suc'){
				   echo '<p style="color:green;text-align:center;font-size:20px;margin-left: 85px;">Your password has been changed successfully</p>';
				}	
													 
		  ?>				

	<form class="form-horizontal sb_border-shadow" role="form"  method="post" onSubmit="return validateForm()" name="registerform"> 

<div class="form-group">
  <label class="col-md-4 control-label" for="Name">Current Password</label>  
  <div class="col-md-6">
 <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-user">
        </i>
       </div>
       <input id="cpass" name="cpass" type="password" placeholder="Current Password" class="form-control input-md" >
      </div>

  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label">New Password</label>  
  <div class="col-md-6">
 <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-user">
        </i>
       </div>
       <input id="newpass" name="newpass" type="password" placeholder="New Password" class="form-control input-md" >
      </div>

  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label">Confirm password</label>  
  <div class="col-md-6">
 <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-user">
        </i>
       </div>
       <input id="repass" name="repass" type="password" placeholder="Confirm password" class="form-control input-md" >
      </div>

  </div>
</div>



<div class="form-group">
  <label class="col-md-4 control-label" ></label>  
  <div class="col-md-4">
		 <input class="sb_login-btn" type="submit" name="submit" value="Send">

  </div>
</div>

</fieldset>
</form>
</div>
</div>
<div class="col-md-2 hidden-xs profile_img">
<!--<img src="http://websamplenow.com/30/userprofile/images/avatar.jpg" class="img-responsive img-thumbnail ">-->
  </div>


</div>
   </div>
    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
