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
	
	if(isset($_GET['del'])){
			$id=$_GET['del'];
			$userdata->deletesubject($id);
			if($userdata){
		    $msg = "<p style='color:green';>Record has been deleted successfully</p>";
	 }else {
		    $msg =  "There is some problem in delete record";
       }
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
	<?PHP if(!empty($msg)){
		    echo $msg;
		 } 
		 if(isset($_GET['add']) && $_GET['add']=='Added'){
			 echo '<p style="color:green;">Record has been added successfully!</p>';
		}
		if(isset($_GET['id']) && $_GET['id']=='Updated'){
			echo '<p style="color:green;"> Record has been updated successfully!</p>';
		}
		?>
	 
      <div class="row">
        <div class="col-xs-12">
          
          <!-- /.box -->

          <div class="box box-primary">
           <div class="box-header with-border">
					  <h3 class="box-title">Subject List</h3>
					  <a href="add-subject.php" class="btn btn-primary pull-right">Add Subject</a>
					</div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sno.</th>
                  <th>Subject</th>
                  <th>Action</th>
                  
     
                  
                </tr>
                </thead>
                <tbody>
					  <?php
						 $calllist = $userdata->getsubject();
						    if($calllist){
							    $count=1;
						    } 									
						     while($obj= mysqli_fetch_object($calllist))
						     {
						?>  
                       <tr>
							 <td><?php echo $count++ ?></td>
							 <td><?php echo $obj->subject ?></td>
							 
							
							
							 <td >
							 <a  href="add-subject.php?id=<?php echo $obj->subjectID ?>"><i class="fa fa-pencil" style="font-size:24px;" title="Edit"></i></a>
							  <a href="view-subject.php?del=<?php echo $obj->subjectID ?>" onclick="return confirm('Are you sure want to delete record ?');"><i class="fa fa-times" style="font-size:24px;" title="Delete"></i></a></td> 
							 
						
							
							
							
                  
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

