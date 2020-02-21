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
	   <form action="" method="post" name="export_excel">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box box-primary" style="margin-top:20px;">
           <div class="box-header with-border">
					  <h3 class="box-title">Force Pressure List</h3>
                        <div class="box-tools pull-right">
						 <a href="download-pressure.php" class="btn btn-primary button-loading pull-right" >Export Record</a>
					  </div>
					</div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sno.</th>
                  <th>Name</th>
                  <th>Grade</th>
                  
                </tr>
                </thead>
                <tbody>
					  <?php
					  $stuinfo = $userdata->getForcePressure();
						    if($stuinfo){
							    $count=1;
						    } 									
						     while($obj= mysqli_fetch_object($stuinfo))
						     {
							
								 
						?>  
                       <tr>
							 <td><?php echo $count++ ?></td>
							 <td><?php echo $obj->name ?></td>
							 <td><?php echo $obj->grade?></td>
						
                  
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
  
</script>
<!-- SlimScroll -->
 
<!-- AdminLTE App -->


<!-- page script -->
<?php include("include/footer.php");?>

