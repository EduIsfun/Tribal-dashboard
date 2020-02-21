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

          <div class="box box-primary">
           <div class="box-header with-border">
		         <?php
						$uid=$_GET['id'];
						$userlist = $userdata->getsessionDetails($uid);					
						$obj= mysqli_fetch_object($userlist);
						
                    ?>  
					<div>
					  <h3 class="box-title">Session ways name and userid :</h3>
					 <?php echo $obj->name?> /
					 <?php echo $obj->userID?>
                    </div>
					  <div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						<a href="view-user.php" class="btn btn-primary button-loading pull-right" >Back</a>
					  </div>
					</div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
			 
                <thead>
                <tr>
                  <th>Game</th>
                  <th>Version</th>
                  <th>Device ID</th>
                  <th>Modelnum</th>
                  <th>Time Stamp</th>
                </tr>
                </thead>
                <tbody>
					  <?php
						$uid=$_GET['id'];
						$userlist = $userdata->getsessionDetails($uid);
											
						while($obj= mysqli_fetch_object($userlist))
						{

					?>  
                       <tr>
							 <td><?php echo $obj->game?></td>
							 <td><?php echo $obj->version?></td>
							 <td><?php echo $obj->deviceID ?></td>
							 <td><?php echo $obj->modelnum ?></td>
							 <td><?php echo $obj->timestamp ?></td>
						
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
    </section>
    <!-- /.content -->
	</div>
	
		
		<div id="orderDetail" class="modal fade">
       <div class="modal-dialog">
         <div class="modal-content">
      <div class="modal-header" style="background:lightblue">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Balance Details</h4>
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
  
</script>
<!-- SlimScroll -->

<!-- AdminLTE App -->


<!-- page script -->
<?php include("include/footer.php");?>

