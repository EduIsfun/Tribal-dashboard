<?php 
 session_start();
	if(empty($_SESSION['uid'])){
	   header('Location:index.php');
	}

	   include('include/db_config.php');
  function __autoload($classname){
	   include("classes/$classname.class.php");
	}
	
    $userdata = new products();	
	
	if(isset($_POST['action']) && $_POST['action']=="ViewDetails")
	{
		$uid = $_POST['uid'];
		
		$query= "SELECT p.userID,p.password,u.name,u.email,u.city,u.mobile,u.dob,u.timestamp,u.country,u.countrycode,u.created_timestamp,u.updated_timestamp,u.type FROM user_password p
           INNER JOIN `user` u ON u.userID=p.userID where p.userID='$uid'";
			$results=mysqli_query($conn,$query);
			$row=mysqli_fetch_object($results);
		
		$resultmsg =  "<table class='table table-striped table-bordered'>
                            <tbody>
								<tr>
									<th>User ID</th>
									<td>$uid</td>
								</tr>
								<tr>
									<th>Name</th>
									<td>".$row->name."</td>
								</tr>
								
								<tr>
									<th>Password</th>
									<td>".$row->password."</td>
								</tr>

								<tr>
									<th>Email</th>
									<td>".$row->email."</td>
								</tr>
								<tr>
									<th>Mobile</th>
									<td>".$row->mobile."</td>
								</tr>
								<tr>
									<th>Dob</th>
									<td>".$row->dob."</td>
								</tr>
								<tr>
									<th>Type</th>
									<td>".$row->type."</td>
								</tr>
								<tr>
									<th>Country</th>
									<td>".$row->country."</td>
								</tr>
								<tr>
									<th>Timestamp</th>
									<td>".$row->timestamp."</td>
								</tr>
								<tr>
									<th>Created Timestamp</th>
									<td>".$row->created_timestamp."</td>
								</tr>
								<tr>
									<th>Updated Timestamp</th>
									<td>".$row->updated_timestamp."</td>
								</tr>
								<tr>
									<th>Country Code</th>
									<td>".$row->countrycode."</td>     
								</tr>
                            </tbody>
                        </table>";

		echo $resultmsg;
		die();
	}
	
   	
?>
<?php 
require 'include/header.php'; 
require 'include/side-bar.php';
?>
<style>
#example1_filter,
.dataTables_paginate {
    text-align: right;
}
</style>

	
	<div class="content-wrapper">
    <section class="content">
	   <form method="post">
      <div class="row">
        <div class="col-xs-12">
          
          <!-- /.box -->

          <div class="box box-primary">
           <div class="box-header with-border">
					  <h3 class="box-title">User Password List</h3>

					  <div class="box-tools pull-right">
						<a href="download-password.php" class="btn btn-primary pull-right">Export Records</a>
					  </div>
					</div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sno.</th>
                  <th>User ID.</th>
                  <th>Password</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Mobile</th>
                  <th>timestamp</th>
                  <th>Action</th>
                 
                </tr>
                </thead>
                <tbody>
					  <?php
						 $userlist = $userdata->getuserpassword();
						    if($userlist){
							    $count=1;
						    } 									
						     while($obj= mysqli_fetch_object($userlist))
						     {
						?>  
                       <tr>
							 <td><?php echo $count++ ?></td>
							 <td><?php echo $obj->userID ?></td>
							 <td><?php echo $obj->password ?></td>
							 <td><?php echo $obj->name ?></td>
							 <td><?php echo $obj->email ?></td>
							 <td><?php echo $obj->mobile ?></td>
							 <td><?php echo $obj->timestamp ?></td>
                        <td><a href="#orderDetail" role="button" data-toggle="modal" onclick="ActionViewuserDetails('<?=$obj->userID?>')"><i class="fa fa-eye" style="font-size:18px;" title="View"></i></a> </td>
                       </tr>
							 <?php } ?>
               
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
		 
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
	  <form>
    </section>
    <!-- /.content -->
	</div>
	
		
		<div id="orderDetail" class="modal fade">
       <div class="modal-dialog">
         <div class="modal-content">
      <div class="modal-header" style="background:lightblue">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">User Password</h4>
        </div>
        <div class="modal-body">
        <div id="orderdetailpopup"></div>
        </div>
        <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       <!-- <button type="button" class="btn btn-primary">Save changes</button>-->
         </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    
	
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
  
   function ActionViewuserDetails(uid){		
			var dataString = "action=ViewDetails&uid="+uid;	
			// alert(dataString);		
			$.ajax({		
				type: "POST",		
				dataType: "text",		
				url: "view-userpassword.php",	
				data: dataString,		
				success: function(result){	
               // alert('result'+result);				
					$('#orderdetailpopup').empty().html(result);
				}						
			});
		}
  
</script>
<!-- SlimScroll -->
 
<!-- AdminLTE App -->


<!-- page script -->
<?php include("include/footer.php");?>

