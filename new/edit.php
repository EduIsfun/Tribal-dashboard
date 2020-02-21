<?php
	session_start();
	include('db_config.php');
	$currentUser  = $_SESSION['currentuser'];
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

	if(isset($_POST['update']))
	{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$mobile = $_POST['mobile'];
		$country = $_POST['country'];
		$dob = $_POST['dob'];
		$query = "UPDATE user SET name='$name',email='$email',mobile='$mobile',country ='$country',dob ='$dob' where userID='".$_SESSION['uid']."'";                           
		$query=mysqli_query($conn, $query);
          
	    $sql = "select * from user where userID = '".$_SESSION['uid']."'";
		$result = mysqli_query($conn,$sql) or die($conn . "Error");
		$row = mysqli_fetch_array($result);
		$_SESSION['currentuser'] = $row;
		
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';	 
	}

?>
			<!-- edit-profile -->
			<div id="edit-profile" class="tab-pane">
				<section class="panel">                                          
					<div class="panel-body bio-graph-info">
						<h1> Profile Info</h1>
						<form class="form-horizontal" role="form" action="" method="post" 
					  enctype="multipart/form-data">                                                  
							<div class="form-group">
								<label class="col-lg-2 control-label">Name</label>
								<div class="col-lg-6">
									<input type="text" class="form-control"  name="name"
									value="<?php echo $currentUser['name'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Email</label>
								<div class="col-lg-6">
									<input type="text" class="form-control"  name="email"
								  value="<?php echo $currentUser['email'];  ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Mobile</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="mobile"
								   value="<?php echo $currentUser['mobile'];  ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Country</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="country"
									value="<?php echo $currentUser['country'];  ?>">
								</div>
							</div>
							<div class="form-group">
							  <label class="col-lg-2 control-label">Birthday</label>
							  <div class="col-lg-6">
								  <input type="text" class="form-control" id="b-day" name="dob"
								   value="<?php echo $currentUser['dob'];  ?>">
							  </div>
							</div>
						
							<div class="form-group">
							  <div class="col-lg-offset-2 col-lg-10">
								  <button type="submit" name="update" class="btn btn-primary">Update</button>
								  <button type="button" class="btn btn-danger">Cancel</button>
							  </div>
							</div>
					  </form>
				  </div>
			  </section>
		  </div>

</body>
</html>