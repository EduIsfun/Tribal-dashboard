<?php
session_start();
  if(empty($_SESSION['uid'])){
		header('Location:index.php');
	function __autoload($classname){
		include("classes/$classname.class.php");
	}
	
    $userdata = new products();	
  } 
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
					<option value="products" <?php if ($_SESSION['dbname']=="products") echo "selected";?> >Products</option>
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
		
		<?php if($dashboardbox{'Manage_Aop_User'}==true){ ?>
		<div class="col-lg-3 col-xs-6">
			<?php 
			$query ="select * from aop_user";
           
           
				$result = mysqli_query($conn,$query);
				$rows = mysqli_fetch_object($result);
				$rowssss = mysqli_num_rows($result);
			?>
          <div class="small-box bg-green">
            <div class="inner">
              <h4>Ace of pace</h4>

               <div class="count"><h4>Record : <?=$rowssss?> </h4></div>
            </div>
            <a href="view-aopuser.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		<?php } ?>
		<div class="row">
			 <div class="col-md-4">
				<div class="scroll">
					<table id="example1" class="table table-bordered table-striped sb_table0">
						<thead>
						<tr>
						   <th>Game Name</th>
						   <th>Total Session</th>
						</tr>
						</thead>
						<tbody>
							  <?php
								 $query = "SELECT game,COUNT(*) AS total FROM `session` GROUP BY game";
								  $result = mysqli_query($conn,$query);
								   while($obj=mysqli_fetch_object($result))
									   
								   {
							
								?>  
							   <tr>
									 <td><?php echo $obj->game ?></td>
									 <td><?php echo $obj->total ?></td>
									
							   </tr>
								   <?php }  ?>
					   
						</tbody>
					</table>
				  </div>
			
			</div>
		
			 <div class="col-md-4">
				<div class="scroll">
					<table id="example1" class="table table-bordered table-striped sb_table0">
						<thead>
						<tr>
						   <th>Game Name</th>
						   <th>Total User</th>
						</tr>
						</thead>
						<tbody>
							  <?php
								 $query = "SELECT COUNT(DISTINCT(s.`userID`)) AS total,s.game FROM `session` s 
                                 INNER JOIN `user` u ON s.userID=u.`userID`
                                 GROUP BY game";
								  $result = mysqli_query($conn,$query);
								   while($obj=mysqli_fetch_object($result))
									   
								   {
							
								?>  
							   <tr>
									 <td><?php echo $obj->game ?></td>
									 <td><?php echo $obj->total ?></td>
									
							   </tr>
								   <?php }  ?>
					   
						</tbody>
					</table>
				  </div>
			
			</div>
		</div>
    
    
    
    
    
	  
  </form>
        </section>
       
  </div>
  
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

  <!-- /.content-wrapper -->
  <?php require 'include/footer.php';?>