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
		
		$query= "SELECT * FROM user WHERE userID='$uid' ";
		$results=mysqli_query($conn,$query);
		$row=mysqli_fetch_object($results);
		
		$resultmsg =  "<table class='table table-striped table-bordered'>
                            <tbody>
								<tr>
									<th>user ID:</th>
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
	   <form action="" method="post" name="export_excel">
      <div class="row">
        <div class="col-xs-12">
		<!--<div class="row">
		 <div class="col-md-3">
					<div class="form-group">
								    <label  for="reporttitle">Game</label>
								      <div class="input-group">
								        <div class="input-group-addon">
								          <i class="fa fa-tasks"></i>
								       </div>
									   
									  <?php //$game= isset($_POST['game'])?$_POST['game']:'';   ?>  
									 
									 
										<input type='text' id="game" name='game'  class='form-control reporttitle'  placeholder="Search Your game" value="<?php echo $game;?>">
								    </div><br>
									     <input type="submit" name="submit" class="btn btn-primary form-control" value="Search Now">
								</div>
								</div>
                         </div>-->
						<!--  <div class="row">
								<div class="col-md-3 ">
								<input type="submit" name="submit" class="btn btn-primary form-control" value="Search Now">
								</div>
								<div class="col-md-3 ">
								<input type="reset" name="reset" class="btn btn-primary form-control" value="Reset Now">
								</div>
								
								</div>-->
          <!-- /.box -->

          <div class="box box-primary" style="margin-top:20px;">
           <div class="box-header with-border">
					  <h3 class="box-title">User List</h3>

					  <div class="box-tools pull-right">
						 <a href="download-user.php" class="btn btn-primary button-loading pull-right" >Export Record</a>
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
                  <th>Email</th>
                  <th>City</th>
                  <th>TimeStamp</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
					  <?php
					  // $query=" SELECT DISTINCT u.`userID`,s.game,u.email,u.name,u.city,u.`country`,u.`countrycode`,u.`dob`,u.`timestamp`,u.`mobile`,u.`type`,u.`created_timestamp`,u.`updated_timestamp` FROM  `session` s
                      // INNER JOIN `user` u ON s.userID=u.`userID`"; 
					  // if(isset($_POST['submit']))
					  // {
												
					  // if(isset($_POST['game']) AND ($_POST['game']!=''))
					  // {
					  // $query.=" AND (s.game  like '%".$_POST['game']."%')";
					  // }
					  // }
                               
					  $userlist = $userdata->getviewUser();
						    if($userlist){
							    $count=1;
						    } 									
						     while($obj= mysqli_fetch_object($userlist))
						     {
							
								 
						?>  
                       <tr>
							 <td><?php echo $count++ ?></td>
							 <td><?php echo $obj->userID ?></td>
							 <td><?php echo $obj->name ?></td>
							 <td><?php echo $obj->email?></td>
							 <td><?php echo $obj->city?></td>
							 <td><?php echo $obj->timestamp ?></td>
							 <td >
							  <a href="edufun.php?id=<?php echo $obj->userID ?>" target="_blank" ><i class="fa fa-user" style="font-size:18px;" title="View"></i></a>
							  <a href="#orderDetail" role="button" data-toggle="modal" onclick="ActionViewuserDetails('<?=$obj->userID ?>')"><i class="fa fa-eye" style="font-size:18px;" title="View"></i></a>
							  <a href="session-details.php?id=<?php echo $obj->userID ?>"><i class="fa  fa-hourglass-start" style="font-size:18px;" title="View session"></i></a>
							
							
							</td> 
                  
                       </tr>
					   <?php }  ?>
               
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
		 
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
	  </form>
      <!-- /.row -->
    </section>
    <!-- /.content -->
	</div>
	
		
		<div id="orderDetail" class="modal fade">
       <div class="modal-dialog">
         <div class="modal-content">
      <div class="modal-header" style="background:lightblue">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">User Details</h4>
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
			//alert(dataString);		
			$.ajax({		
				type: "POST",		
				dataType: "text",		
				url: "view-user.php",	
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

