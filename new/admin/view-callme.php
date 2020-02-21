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
		
		$query= "SELECT c.UserID,c.name,c.mobile,c.time_stamp,u.email,u.dob,u.country,u.`created_timestamp`,u.`updated_timestamp`,u.city,u.type,u.countrycode FROM call_me c
        INNER JOIN `user` u ON c.userID=u.userID  where c.UserID='$uid'";
		$results=mysqli_query($conn,$query);
		$row=mysqli_fetch_object($results);
		
		$resultmsg =  "<table class='table table-striped table-bordered'>
                            <tbody>
								<tr>
									<th>user ID</th>
									<td>$uid</td>
								</tr>
								<tr>
									<th>Name</th>
									<td>".$row->name."</td>
								</tr>
								<tr>
									<th>Email</th>
									<td>".$row->email."</td>
								</tr>
								<tr>
									<th>City</th>
									<td>".$row->city."</td>
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
									<td>".$row->time_stamp."</td>
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
	 
      <div class="row">
        <div class="col-xs-12">
          
          <!-- /.box -->
          <form action="" method="post" name="export_excel">
          <div class="box box-primary" style="margin-top:20px;">
		  
           <div class="box-header with-border">
					  <h3 class="box-title">Call Me List</h3>
                     
					    <div class="box-tools pull-right">
						<a href="download-callme.php" class="btn btn-primary pull-right">Export Records</a>
					    </div>
					  
					
					</div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sno.</th>
                  <th>User ID.</th>
                  <th>Name</th>
                   <th>Mobile</th>
                   <th>Email</th>
                   <th>City</th>
				   <th>Timestamp</th>
                   <th>Action</th>
                   
                  
     
                  
                </tr>
                </thead>
                <tbody>
				        <?php
						 $calllist = $userdata->getcallMe();
						    if($calllist){
							    $count=1;
						    } 									
						     while($obj= mysqli_fetch_object($calllist))
						     {
						?>  
                       <tr>
							 <td><?php echo $count++ ?></td>
							 <td><?php echo $obj->UserID ?></td>
							 <td><?php echo $obj->name ?></td>
							 <td><?php echo $obj->mobile ?></td>
							 <td><?php echo $obj->email ?></td>
							 <td><?php echo $obj->city ?></td>
							 <td><?php echo $obj->time_stamp ?></td>
							
							
							
							<td >
							  <!--<a href="edufun.php?id=<?php echo $obj->userID ?>"><i class="fa fa-user" style="font-size:18px;" title="View"></i></a>-->
							  <a href="#orderDetail" role="button" data-toggle="modal" onclick="ActionViewuserDetails('<?=$obj->UserID ?>')"><i class="fa fa-eye" style="font-size:18px;" title="View"></i></a>

							</td> 
							
							
							
                  
                       </tr>
											<?php }  ?>
               
                </tbody>
              </table>
            </div>
			</form>
            <!-- /.box-body -->
          </div>
		 
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
	</div>
		<div id="orderDetail" class="modal fade">
       <div class="modal-dialog">
         <div class="modal-content">
      <div class="modal-header" style="background:lightblue">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">call Me Details</h4>
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
</div><!-- /.modal --
	
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
			//alert(dataString);		
			$.ajax({		
				type: "POST",		
				dataType: "text",		
				url: "view-callme.php",	
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

