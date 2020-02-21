<?php 

	session_start();
	include('db_config.php');
	  function __autoload($classname){
      include("classes/$classname.class.php");
    }
	$userdata = new student();

	
	if(empty($_SESSION)){
		header('Location:index.php');
	}
	
	
	//$currentUsers  =$_SESSION['currentinfo'];
	 if(isset($_POST['submit']) AND $_POST['submit'] == 'GO'){
			$userId=$_POST['username'];
			$resultuser= $userdata->checkMobile($userId);
			$row =mysqli_fetch_array($resultuser);
			session_unset();
			unset($_SESSION['currentinfo']);
			unset($_SESSION['currentuser']);
			unset($_SESSION['currentinfouserid']);
			$_SESSION['currentusernew'] = $row;
			
			 echo "<script>window.open('edufun.php','_self')</script>";
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

<div class="container">


	<div class="row">
	<div class="col-md-8 col-md-offset-2">

		<div class="top_head">
		<h3>User Account List</h3>
		</div>
		<div class="table-responsive sb_border-shadow">		
			<table class="table inner_tabel">
				<form method="post" >
				
				<tr> 
					<td>SELECT ACCOUNT</td><td>NAME</td><td>USERID</td><td>MOBILE</td>
				</tr>
				<?php
				if(!empty($_SESSION['currentinfo'])){
					$userId=$_SESSION['currentinfo']['mobile'];
					$userinfo=$userdata->checkUsermobile($userId); 
				}
				else{
					$userId=$_SESSION['currentinfouserid'];
					$userinfo=$userdata->checkMobile($userId); 
				}
			
				
				while($row=mysqli_fetch_object($userinfo)){
					// echo '<pre>';
				// print_r($row);
				// echo '</pre>';
				?>
				<tr> 
					<td><input type="radio" name="username" value="<?php echo $row->userID; ?>" checked></td><td><?php echo $row->name; ?></td><td><?php echo $row->userID; ?></td><td><?php echo $row->mobile; ?></td>
				</tr>
				<?php } ?>
				
			</table>
	
                     <input type="submit" name="submit" value="GO" class="btn btn-primary sb_login-btn">
                
		</div>
</form>

	</div>
	</div>

</div>


  <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
