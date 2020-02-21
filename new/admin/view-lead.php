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

          <div class="box box-primary">
           <div class="box-header with-border">
					  <h3 class="box-title"> Lead List</h3>

					  <div class="box-tools pull-right">
						<a href="download-lead.php" class="btn btn-primary pull-right">Export Records</a> 
					  </div>
					</div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sno.</th>
                  <th>Email</th>
                  <th>Game</th>
                   <th>Mobile</th>
                   <th>Platform</th>
                   
     
                  
                </tr>
                </thead>
                <tbody>
					  <?php
						 $leadlist = $userdata->getlead();
						    if($leadlist){
							    $count=1;
						    } 									
						     while($obj= mysqli_fetch_object($leadlist))
						     {
						?>  
                       <tr>
							 <td><?php echo $count++ ?></td>
							 <td><?php echo $obj->email ?></td>
							 <td><?php echo $obj->game ?></td>
							 <td><?php echo $obj->mobile ?></td>
							 <td><?php echo $obj->platform ?></td>
				
							
							
							 
							 <!-- <a href="edufun.php?id=<?php //echo $obj->userID ?>"><i class="fa fa-user" style="font-size:18px;" title="View"></i></a>-->
							 <!-- <a href="view-userdetails.php?id=<?php //echo $obj->userID ?>"><i class="fa fa-eye" style="font-size:18px;" title="View"></i></a>-->
						
							
							
						
                  
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
	  </form>
    </section>
    <!-- /.content -->
	</div>
	
	
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

