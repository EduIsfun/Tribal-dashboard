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
					  <h3 class="box-title">User Details List</h3>

					  <div class="box-tools pull-right">
					    
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						<a href="view-user.php" class="btn btn-primary button-loading pull-right" >Back</a>
					  </div>
					</div>
          
					<?php
						$uid=$_GET['id'];
						$userlist = $userdata->getviewuserDetails($uid);
											
						$obj= mysqli_fetch_object($userlist);

					?>  
			<div class="box-body">
				   <table class="table table-bordered table-striped">
				      <thead>
				         <tr>
				             <th>Name</th>
				             <td><?php echo $obj->name ?></td>
				         </tr>
				          <tr>
				             <th>Email</th>
				             <td><?php echo $obj->email ?></td>
				          </tr>
							<tr>
								<th>City</th>
								<td><?php echo $obj->city ?></td>
							</tr>
							<tr>
								<th>Total Time Stamp</th>
								<td></td>
							</tr>
							
							<tr>
								<th>Total Question Answer</th>
								<td></td>
							</tr>
							<tr>
								<th>Learning Class</th>
								<td></td>
							</tr>
							<tr>
								<th>Time Spent</th>
								<td></td>
							</tr>
							<tr>
								<th>Overall Accuracy</th>
								<td></td>
							</tr>
							<tr>
								<th>Overall Rank</th>
								<td></td>
							</tr>
					  </thead>
							
							
				     
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

<?php include("include/footer.php");?>

