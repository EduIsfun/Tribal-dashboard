<?php
	session_start();
	include('db_config.php');
	if(empty($_SESSION['currentusernew'])){
		header('Location:index.php');
	}
	function __autoload($classname){
      include("classes/$classname.class.php");
    }
	$userdata = new student();
	$currentUsers  = $_SESSION['currentusernew'];
	// echo '<pre>';
	// print_r($currentUsers);
	// echo '</pre>';
	if(isset($_POST['update']))
	{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$mobile = $_POST['mobile'];
		$city = $_POST['city'];
		$country = $_POST['country'];
		$dob = $_POST['dob'];
		$objusers=new stdclass();
			$userid=$currentUsers['userID'];
			$objusers->name= $name = isset($_POST['name'])?$_POST['name']:'';
			$objusers->email= $email = isset($_POST['email'])?$_POST['email']:'';
			$objusers->mobile= $mobile = isset($_POST['mobile'])?$_POST['mobile']:'';
			$objusers->city= $city = isset($_POST['city'])?$_POST['city']:'';
			$objusers->country= $country = isset($_POST['country'])?$_POST['country']:'';
			$objusers->dob= $dob = isset($_POST['dob'])?$_POST['dob']:'';
			
			$updatedata=$userdata->updateProfile($objusers,$userid);
			unset($_SESSION['currentinfo']);
			unset($_SESSION['currentuser']);
			$resultuser= $userdata->checkMobile($userid);
			$row =mysqli_fetch_array($resultuser);
			$_SESSION['currentusernew'] = $row;
			if($updatedata){
				exit("<script>window.location.href='profile.php?id=Updated';</script>");
			  }
			 else{
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
        <i class="fa fa-user">
        </i>
       </div>
       <input id="Name" name="name" type="text" placeholder="Name (Full name)" class="form-control input-md" value="<?php echo $currentUsers['name'];?>">
      </div>

    
  </div>

  
</div>


<!-- Text input-->
<!--<div class="form-group">
  <label class="col-md-4 control-label" for="Phone number ">Phone number </label>  
  <div class="col-md-4">
  <div class="input-group">
       <div class="input-group-addon">
     <i class="fa fa-phone"></i>
        
       </div>
    <input id="mobile" name="mobile" type="text" placeholder="" class="form-control input-md" value="<?php echo $currentUsers['mobile'];  ?>">
    
      </div>
  
  </div>
</div>
-->
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Email Address">Email Address</label>  
  <div class="col-md-4">
  <div class="input-group">
       <div class="input-group-addon">
     <i class="fa fa-envelope-o"></i>
        
       </div>
    <input id="email" name="email" type="text" placeholder="" class="form-control input-md" value="<?php echo $currentUsers['gmailID'];  ?>">
    
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
    <input id="city" name="city" type="text" placeholder="" class="form-control input-md" value="<?php echo $currentUsers['city'];  ?>">
    
      </div>
  
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="country">Country</label>  
  <div class="col-md-4">
  <div class="input-group">
       <div class="input-group-addon">
     <i class="fa fa-envelope-o"></i>
        
       </div>
    <input id="country" name="country" type="text" placeholder="" class="form-control input-md" value="<?php echo $currentUsers['country'];  ?>">
    
      </div>
  
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" ></label>  
  <div class="col-md-4">
   <div class="edit_btn">  
		 <input type="submit" name="update" value="Update">
	</div>
  </div>
</div>

</fieldset>
</form>
</div>
</div>
<div class="col-md-2 hidden-xs profile_img">
<img src="images/profile.png" class="img-responsive img-thumbnail ">
  </div>


</div>
   </div>
    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
