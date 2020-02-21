<?php 
error_reporting(0);
include('db_config.php');
include('model/Teacher.class.php');
global $conn;
$teacher = new Teacher();

		$id = isset($_GET['id'])?$_GET['id']:'';

		$result = $teacher->getStudentresult($id);
		$studentobj = mysqli_fetch_object($result);
	
	
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

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i" rel="stylesheet">

	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" /> 
	
	<link rel="stylesheet" href="css/style.css"/>  
	 
  
</head>
<body>

<div class="container">
 <h2><a href ='teacherdashboard.php'>Go Back </a></h2>
  <h4>Student Details:</h4>   
   <table class="table table-bordered">
   <form method="post">  
    <thead>
	<tr class="info">        
		<th>Userid </th> <td> :   <?php echo $studentobj->userID?></td>
	</tr>
	<tr class="info">
		<th>Name</th> <td> :  <?php echo ucfirst($studentobj->name)?></td>
	</tr>
	<tr class="info">	
		<th>Email</th> <td> :  <?php echo $studentobj->gmailID?></td>
	</tr>

	<tr class="info">
		<th>City</th> <td> :  <?php echo $studentobj->city?></td>
	</tr>
	<tr class="info">
		<th>Mobile</th> <td> :  <?php echo $studentobj->mobile?></td>
	</tr>
	<tr class="info">
		<th>Dob</th> <td> : <?php echo $studentobj->dob?></td>
	</tr>

    </thead>
	</form>
  </table>
</div>
</body>
</html>
