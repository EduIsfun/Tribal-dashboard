<?php
	session_start();
	include('db_config.php');
	if(empty($_SESSION['uid'])){
		header('Location:index.php');
	}
	  $currentUser  = $_SESSION['currentuser'];
// echo '<pre>';
// print_r($currentUser);
// echo '</pre>';

	if(isset($_POST['update']))
	{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$mobile = $_POST['mobile'];
		$state = isset($_POST['state'])?$_POST['state']:'';
		$city = $_POST['city'];
        $zipcode = isset($_POST['zipcode'])?$_POST['zipcode']:'';
		$address = isset($_POST['address'])?$_POST['address']:'';
		
		$query = "UPDATE teacher_password SET name='$name',email='$email',mobile='$mobile',state='$state',city='$city',zipcode =$zipcode,address ='$address' where email ='".$_SESSION['uid']."'";                           
		$query=mysqli_query($conn, $query);
		if($query){
		exit("<script>window.location.href='profile.php?id=Updated';</script>");
			  }else{
				   echo "There is some problem in inserting record";
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

        <nav class="navbar navbar-default nav_height">
            
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
					
                </div>
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
            
        </nav>
		</div>
</div>

<div class="container">
<div class="row">
<div class="col-md-10 ">
<div class="form-horizontal">
<fieldset>

<!-- Form Name -->
<h2 class="form_title">Modify Your profile ?</h2>

<!-- Text input-->


	<form class="form-horizontal" role="form" action="" method="post"> 

<div class="form-group">
	<label class="col-md-4 control-label" for="Name (Full name)">Name (Full name)</label>  
	<div class="col-md-4">
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-user"></i>
			</div>
			<input id="Name" name="name" type="text"  value="<?php echo $currentUser->name?>" class="form-control input-md" >
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-md-4 control-label" for="Email Address">Email Address</label>  
	<div class="col-md-4">
		<div class="input-group">
			<div class="input-group-addon">
			<i class="fa fa-envelope-o"></i> 
			</div>
			<input id="email" name="email" type="text" placeholder="" readonly class="form-control input-md" value="<?php echo $currentUser->email?>">
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-md-4 control-label" for="Phone number ">Phone number </label>  
	<div class="col-md-4">
		<div class="input-group">
			<div class="input-group-addon">
			<i class="fa fa-phone"></i>
			</div>
			<input id="mobile" name="mobile" type="text" placeholder="" class="form-control input-md" value="<?php echo $currentUser->mobile?>">
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-md-4 control-label" for="city">State</label>  
	<div class="col-md-4">
		<div class="input-group">
			<div class="input-group-addon">
			<i class="fa fa-envelope-o"></i>
			</div>
			<input id="state" name="state" type="text" placeholder="" class="form-control input-md" value="<?php echo $currentUser->state?>">
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-md-4 control-label" for="city">City</label>  
	<div class="col-md-4">
		<div class="input-group">
			<div class="input-group-addon">
			<i class="fa fa-envelope-o"></i>
			</div>
			<input id="city" name="city" type="text" placeholder="" class="form-control input-md" value="<?php echo $currentUser->city?>">
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-md-4 control-label" for="city">Zipcode</label>  
	<div class="col-md-4">
		<div class="input-group">
			<div class="input-group-addon">
			<i class="fa fa-envelope-o"></i>
			</div>
			<input id="zipcode" name="zipcode" type="text" placeholder="" class="form-control input-md" value="<?php echo $currentUser->zipcode?>">
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-md-4 control-label" for="city">Address</label>  
	<div class="col-md-4">
		<div class="input-group">
			<div class="input-group-addon">
			<i class="fa fa-envelope-o"></i>
			</div>
			<input id="address" name="address" type="text" placeholder="" class="form-control input-md" value="<?php echo $currentUser->address?>">
		</div>
	</div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" ></label>  
  <div class="col-md-4">
   <div class="edit_btn">  
		 <input type="submit"  class="btn btn-primary" name="update" value="Update">
	</div>
  </div>
</div>

</fieldset>
</form>
</div>
</div>
<div class="col-md-2 hidden-xs profile_img">

  </div>

</div>
   </div>
    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
