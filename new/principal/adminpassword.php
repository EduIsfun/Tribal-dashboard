<?php
	session_start();
	include('db_config.php');
	if(empty($_SESSION['adminuids'])){
		header('Location:index.php');
	}
	
	 if(isset($_POST['submit'])){
		 
        $old_pass = isset($_POST['cpass'])?$_POST['cpass']:'';
        $new_pass = isset($_POST['newpass'])?$_POST['newpass']:'';
        $re_pass =  isset($_POST['repass'])?$_POST['repass']:'';
		
        $result = "select * from teacher_login where userID =  '".$_SESSION['adminuids']."'  and password='$old_pass'";
		$run=mysqli_query($conn,$result);
		$check_pass=mysqli_num_rows($run);
		if($check_pass==0){
			
		  header('location:adminpassword.php?id=try');
		   exit();
		}
		if($new_pass!=$re_pass)
		
		{
			header('location:adminpassword.php?id=notmatch');
		
		}else{
			$update= "update teacher_login set password='$new_pass' where  userID =  '".$_SESSION['adminuids']."'";
			$run_pass=mysqli_query($conn,$update);
			header('location:adminpassword.php?id=suc');
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

<body>

<div class="nav-top"> 
	<div class="container-fluid"> 
<?php 
include('header_admin.php');
?>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3"> 
			<div class="top_head"> 
<fieldset>

<!-- Form Name -->
<h3 class="text-center">Change password ?</h3>
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
   
  <div class="col-md-12">
 <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-user"> </i>
		<i id="pass-status" class="fa fa-eye" aria-hidden="true" onClick="viewPassword('cpass')"></i>
       </div>
       <input id="cpass" name="cpass" type="password" placeholder="Current Password" class="form-control input-md"  required="required">
      </div>
	
  </div>
</div>


<div class="form-group">
   
  <div class="col-md-12">
 <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-user"></i>
		<i id="pass-status" class="fa fa-eye" aria-hidden="true" onClick="viewPassword('newpass')"></i>
       </div>
       <input id="newpass" name="newpass" type="password" placeholder="New Password" class="form-control input-md"  required="required">
      </div>

  </div>
</div>


<div class="form-group">
   
<div class="col-md-12">
 <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-user"></i>
		<i id="pass-status" class="fa fa-eye" aria-hidden="true" onClick="viewPassword('repass')"></i>
       </div>
       <input id="repass" name="repass" type="password" placeholder="Confirm password" class="form-control input-md"  required="required">
	   
      </div>

  </div>
</div>



<div class="form-group">
   
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
<script>

function viewPassword(txtid) {
  var x = document.getElementById(txtid);
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
} 
</script>
</body>

</html>
