<?php
session_start();
  if(empty($_SESSION['uid'])){
		header('Location:index.php');
  } 
     // if(isset($_POST['action']) && $_POST['action']=="dbproduct")
			
	    // {
			//echo 'hello';die();
			// $product = isset($_POST['eduisfun'])?$_POST['eduisfun']:'';
			// $eduisfun = isset($_POST['edu_products'])?$_POST['edu_products']:'';
			 // die();
		// }
		include('include/db_config.php');
		require 'include/header.php';
		require 'include/side-bar.php';
 ?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> Home</li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
	

    <!-- Main content -->
    <section class="content">
	<form method="POST">
     
	   <div class="row">
	  <div class="col-md-6">
				<div class="form-group">
                <label  for="reporttitle">Choose Database :<?php //print_r($_SESSION['dbname']); ?></label>
				<div class="input-group">
                 <div class="input-group-addon">
                  <i class="fa fa-tasks"></i>
                   </div>
				   
				<select class="form-control" name="db" id="db">
					<option value=""> Select</option>
					<option value="edu_products" <?php if ($_SESSION['dbname']=="edu_products") echo "selected";?>  >Products</option>
					<option value="eduisfun" <?php if ($_SESSION['dbname']=="eduisfun") echo "selected";?>>Eduisfun</option>
					
				</select>
			    
			   </div>
			   </div>
			  </div>
			  </div>
			    <input type="submit" name="submit" class="btn btn-primary">
	
		 <div class="row" style="margin-top:20px;">
		 <div class="col-lg-3 col-xs-6">
			<?php 
			
				$query  = "SELECT COUNT(*) AS count FROM user";
				$result = mysqli_query($conn, $query);
				$rows = mysqli_fetch_object($result);	
			?>
			<?php if($dashboardbox{'User'}==true){ ?>
          <div class="small-box bg-green">
            <div class="inner">
              <h4>User</h4>

               <div class="count"><h4>Record : <?=$rows->count?> </h4></div>
            </div>
            <a href="view-user.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
			<?php } ?>
		<?php if($dashboardbox{'Lead'}==true){ ?>
		<div class="col-lg-3 col-xs-6">
			<?php
				$query  = "SELECT COUNT(*) AS count FROM lead";
				$result = mysqli_query($conn, $query);
				$rows = mysqli_fetch_object($result);
		
         	  
			?>
          <div class="small-box bg-green">
            <div class="inner">
              <h4>Lead</h4>

               <div class="count"><h4>Record : <?=$rows->count?> </h4></div>
            </div>
            <a href="view-lead.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		
			<?php } ?>
			
		<?php if($dashboardbox{'Invite_Friends'}==true){ ?>
		<div class="col-lg-3 col-xs-6">
			<?php 
				$query  = "SELECT count(*) AS count,i.userID,i.name,i.mobile,i.emailid,i.time_stamp,u.dob,u.country,u.`created_timestamp`,u.`updated_timestamp`,u.city FROM invite_friends i
                INNER JOIN `user` u ON i.userID=u.userID ";
				$result = mysqli_query($conn, $query);
				$rows = mysqli_fetch_object($result);
			?>
          <div class="small-box bg-green">
            <div class="inner">
              <h4>Invite Friends</h4>

               <div class="count"><h4>Record : <?=$rows->count?> </h4></div>
            </div>
            <a href="view-invite.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		
		<?php  } ?>
		
		<?php if($dashboardbox{'Call_Me'}==true){ ?>
		<div class="col-lg-3 col-xs-6">
			<?php 
				$query  = "SELECT COUNT(*) AS count FROM call_me";
				$result = mysqli_query($conn, $query);
				$rows = mysqli_fetch_object($result);
			?>
          <div class="small-box bg-green">
            <div class="inner">
              <h4>Call Me</h4>

               <div class="count"><h4>Record : <?=$rows->count?> </h4></div>
            </div>
            <a href="view-callme.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		
		<?php } ?>
		
			<?php //if($dashboardbox{'User_Password'}==true){ ?>
		<!--<div class="col-lg-3 col-xs-6">
			<?php 
				// $query  = "SELECT  count(*) AS count,p.userID,p.password,u.name,u.email,u.city,u.mobile,u.dob,u.timestamp FROM user_password p
                // INNER JOIN `user` u ON u.userID=p.userID";
				// $result = mysqli_query($conn, $query);
				// $rows = mysqli_fetch_object($result);
			?>
          <div class="small-box bg-green">
            <div class="inner">
              <h4>User Password</h4>

               <div class="count"><h4>Record : <?//=$rows->count?> </h4></div>
            </div>
            <a href="view-userpassword.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>-->
		
			<?php// } ?>
			
		<?php if($dashboardbox{'Paid_User'}==true){ ?>
		<div class="col-lg-3 col-xs-6">
			<?php 
			$query ="SELECT se.`userID`,se.`deviceID`,se.created_timestamp,se.game,se.timestamp,u.name,us.grade,s.`platform`,s.`serial_key`,us.`school` FROM `session` se 
            INNER JOIN `serial_key` s ON se.deviceID=s.deviceID
            INNER JOIN `user` u ON se.`userID`=u.userID
            LEFT JOIN `user_school_details` us ON se.`userID`=us.userID
            WHERE `serial_key` IS NOT NULL 
            GROUP BY deviceID ORDER BY se.timestamp DESC";
           
           
				$result = mysqli_query($conn,$query);
				$rows = mysqli_fetch_object($result);
				$rowss = mysqli_num_rows($result);
			?>
          <div class="small-box bg-green">
            <div class="inner">
              <h4>Paid User</h4>

               <div class="count"><h4>Record : <?=$rowss?> </h4></div>
            </div>
            <a href="view-paiduser.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		<?php } ?>
		
		<?php if($dashboardbox{'free_User'}==true){ ?>
		<div class="col-lg-3 col-xs-6">
			<?php 
					$query ="SELECT * FROM `session` AS a WHERE NOT EXISTS(SELECT * FROM `serial_key` AS b WHERE b.`deviceID` = a.`deviceID`) 
                    GROUP BY a.userID";
					$result = mysqli_query($conn,$query);
					$rows = mysqli_fetch_object($result);
					$srows = mysqli_num_rows($result);
			?>
          <div class="small-box bg-green">
            <div class="inner">
              <h4>Free User</h4>

               <div class="count"><h4>Record : <?=$srows?> </h4></div>
            </div>
            <a href="view-freeuser.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		<?php } ?>
      </div>
  </form>
        </section>
       
  </div>
  
   <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>

  // function ActionViewproduct(){
  //alert(dataString);		  
	// var product = $('#product').val();
	// var eduisfun = $('#eduisfun').val();
	// var dataString = "action=dbproduct&product="+ product +"&eduisfun="+eduisfun;	
	// alert(dataString);		
	// $.ajax({		
		// type: "POST",		
		// dataType: "text",		
		// url: "dashboard.php",	
		// data: dataString,		
		// success: function(result){		
			alert('Results'+result);
			
		// }						
	// });
// }
</script>

  <!-- /.content-wrapper -->
  <?php require 'include/footer.php';?>