<?php
	session_start();
	include('db_config.php');
	if(empty($_SESSION['uid'])){
		header('Location:index.php');
	}
	
	 if(isset($_POST['submit'])){
		 
        $old_pass = isset($_POST['cpass'])?$_POST['cpass']:'';
        $new_pass = isset($_POST['newpass'])?$_POST['newpass']:'';
        $re_pass =  isset($_POST['repass'])?$_POST['repass']:'';
		
        $result = "select * from teacher_password where password='$old_pass'";
		$run=mysqli_query($conn,$result);
		$check_pass=mysqli_num_rows($run);
		if($check_pass==0){
			
		  header('location:change.php?id=try');
		   exit();
		}
		if($new_pass!=$re_pass)
		
		{
			header('location:change.php?id=notmatch');
		
		}else{
			$update= "update teacher_password set password='$new_pass' where password='$old_pass'";
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

<body>
<div class="top_background">
<div class="container-fluid">

        <nav class="navbar navbar-default">
				<a href="teacherdashboard.php"><h2>Dashboard</h2></a>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
					<a href="#"><img src="images/logo.png"></a> 
                </div>
                <div id="navbar" class="navbar-collapse collapse navbar-right">
                    <ul class="nav navbar-nav">
                       <li><a href="profile.php">Profile</a></li> 
                        <li><a href="change.php">Change Password</a></li>
						<li><a href="logout.php">Logout</a></li>
                     
                    </ul>
                </div>
            
        </nav>
</div>
 
</div>

<div class="container">
<div class="row">
<div class="col-md-10 ">
<div class="form-horizontal">
<fieldset>

<!-- Form Name -->
<h2 class="form_title">Change password ?</h2>
        <?php
				if(isset($_GET['id']) && $_GET['id']=='try'){
				    echo '<p style="color:red;text-align:center;font-size:20px;margin-left: 85px;">Your current password is not exist</p>';
				}
				if(isset($_GET['id']) && $_GET['id']=='notmatch'){
				   echo '<p style="color:red;text-align:center;font-size:20px;margin-left: 85px;">Your confirm password is not match,try again</p>';
				}	
				if(isset($_GET['id']) && $_GET['id']=='suc'){
				   echo '<p style="color:green;text-align:center;font-size:20px;margin-left: 85px;">Your password has been change successfully</p>';
				}	
													 
		  ?>				

	<form class="form-horizontal" role="form" action="" method="post"> 

<div class="form-group">
  <label class="col-md-4 control-label" for="Name">Current Password</label>  
  <div class="col-md-6">
 <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-user">
        </i>
       </div>
       <input id="Name" name="cpass" type="password" placeholder="Current Password" class="form-control input-md"  required="required">
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
       <input id="Name" name="newpass" type="password" placeholder="New Password" class="form-control input-md"  required="required">
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
       <input id="repass" name="repass" type="password" placeholder="Confirm password" class="form-control input-md"  required="required">
      </div>

  </div>
</div>



<div class="form-group">
  <label class="col-md-4 control-label" ></label>  
  <div class="col-md-4">
   <div class="edit_btn">  
		 <input type="submit" class="btn btn-primary" name="submit" value="Send">
	</div>
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
