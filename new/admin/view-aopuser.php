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
		  <div class="row">
		   <div class="col-md-3">
					<div class="form-group">
								    <label  for="reporttitle">City</label>
								      <div class="input-group">
								        <div class="input-group-addon">
								          <i class="fa fa-tasks"></i>
								       </div>
									   <?php $city= isset($_POST['city'])?$_POST['city']:''; ?>
										<input type='text' id="city" name='city'  class='form-control reporttitle'  placeholder="Search Your City" value="<?php echo $city;?>">
								    </div><br>
									    <!-- <input type="submit" name="submit" class="btn btn-primary form-control" value="Search Now">-->
								</div>
								</div>
								
								<div class="col-md-3">
					 
					                <div class="form-group">
								    <label  for="reporttitle">Time Stamp</label>
								      <div class="input-group">
								        <div class="input-group-addon">
								          <i class="fa fa-tasks"></i>
								       </div>
										<input type='Date' id="created_timestamp" name='created_timestamp'  class='form-control reporttitle' value="<?php echo $_POST['created_timestamp']; ?>">
								    </div><br>
									    <!-- <input type="submit" name="submit" class="btn btn-primary form-control" value="Search Now">-->
									</div>
								</div>
									<?php $month= isset($_POST['month'])?$_POST['month']:''; ?>
								<div class="col-md-3">
		
					             <div class="form-group">
								    <label  for="reporttitle">Month</label>
								      <div class="input-group">
								        <div class="input-group-addon">
								          <i class="fa fa-tasks"></i>
								       </div>

										<select class='form-control' name='month' id='month'>
											<option value="">Choose Month</option>
											<option value="1"<?php if($month=="1"){ echo 'selected';}?>>January</option>
											<option value="2" <?php if($month=="2"){ echo 'selected';}?>>February</option>
											<option value="3" <?php if($month=="3"){ echo 'selected';}?>>March</option>
											<option value="4" <?php if($month=="4"){ echo 'selected';}?>>April</option>
											<option value="5" <?php if($month=="5"){ echo 'selected';}?>>May</option>
											<option value="6" <?php if($month=="6"){ echo 'selected';}?>>June</option>
											<option value="7" <?php if($month=="7"){ echo 'selected';}?>>July</option>
											<option value="8" <?php if($month=="8"){ echo 'selected';}?>>August</option>
											<option value="9"<?php if($month=="9"){ echo 'selected';}?>>Septemper</option>
											<option value="10" <?php if($month=="10"){ echo 'selected';}?>>October</option>
											<option value="11" <?php if($month=="11"){ echo 'selected';}?>>November</option>
											<option value="12"<?php if($month=="12"){ echo 'selected';}?>>December</option>
									</select>
										</div><br>
	
									</div>
								</div>
								
							<?php  $ye=isset($_POST['year'])?$_POST['year']:''; 
								//print_r($_POST); ?>
								
								<div class="col-md-3">
		
					             <div class="form-group">
								    <label  for="reporttitle">Year</label>
								      <div class="input-group">
								        <div class="input-group-addon">
								          <i class="fa fa-tasks"></i>
								       </div>
									
					<select class='form-control' name='year' id='year'>


					
					<option value="">Choose Year </option>
					      <?php 

									$year = date('Y');
									$add = $year - 2016;
									$min = 2016;
									$max = $min +$add;
									for($i=$min;$i<=$max;$i++)
									{
							?>
										<option value=<?=$i?> <?php if($ye==$i){  echo 'selected'; }?> ><?=$i?></option>
										<?php	
									}		
										?>

					<!--<option value="2015" <?php //if($ye=="2015"){ echo 'selected';}?>>2015</option>
					<option value="2016" <?php //if($ye=="2016"){ echo 'selected';}?>>2016</option>
					<option value="2017" <?php //if($ye=="2017"){ echo 'selected';}?>>2017</option>-->

					</select>
					</div><br>
	
						</div>
							</div>
		  
		  </div>
		  <div class="row">
								<div class="col-md-3 ">
								<input type="submit" name="submit" class="btn btn-primary form-control" value="Search Now">
								</div>
								<!--<div class="col-md-3 ">
								<input type="reset" name="reset" class="btn btn-primary form-control" value="Reset Now">
								</div>-->
								
								</div>
								
          <div class="box box-primary" style="margin-top:20px;">
		  
           <div class="box-header with-border">
					  <h3 class="box-title">Ace Of Pace</h3>
                     
					  <div class="box-tools pull-right">
					 <!--<a href="download-callme.php" class="btn btn-primary button-loading pull-right" >Export Record</a>-->
					  <a href="download-aopuser.php?id=<?php echo isset($_POST['month'])?$_POST['month']:'';?>&year=<?php echo isset($_POST['year'])?$_POST['year']:'';?>&created_timestamp=<?php echo isset($_POST['created_timestamp'])?$_POST['created_timestamp']:'';?>&city=<?php echo isset($_POST['city'])?$_POST['city']:'';?>" class="btn btn-primary button-loading pull-right" >Export Record</a>
					  </div>
					</div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sno.</th>
                  <th>Name</th>
                   <th>Mobile</th>
                   <th>Email</th>
                   <th>City</th>
                   <th>Score1</th>
                   <th>Score2</th>
                   <th>Score3</th>
                   <th>Score4</th>
				   <th>Created Timestamp</th>
                   
                   
                  
     
                  
                </tr>
                </thead>
                <tbody>
				<?php
						$query = "SELECT name,mobile,email,city,created_timestamp,score1,score2,score3,score4 FROM aop_user WHERE 1=1";
						 
						 
							 if(isset($_POST['submit']))
								{
								
								if(isset($_POST['city']) AND ($_POST['city']!=''))
								{
									$query.=" AND (city  like '%".$_POST['city']."%')";
								}
								if(isset($_POST['created_timestamp'])  AND ($_POST['created_timestamp']!=''))
								{
									$query.=" AND (DATE(created_timestamp)  = '".$_POST['created_timestamp']."')";
									
								}
								if(isset($_POST['month'])  AND ($_POST['month']!=''))
								{
									$query.=" AND (MONTH(created_timestamp)  = '".$_POST['month']."')";
									
								}
							  if(isset($_POST['year'])  AND ($_POST['year']!=''))
								{
									$query.=" AND (YEAR(created_timestamp)  = '".$_POST['year']."')";
									
								}	
                                 $query.=" ORDER BY created_timestamp DESC ";								
								
							}
			
								if ($result=mysqli_query($conn,$query))
								{
										$count=1;
									while ($obj=mysqli_fetch_object($result))
									{
				?>  
                       <tr>
							 <td><?php echo $count++ ?></td>
							 <td><?php echo $obj->name ?></td>
							 <td><?php echo $obj->mobile ?></td>
							 <td><?php echo $obj->email ?></td>
							 <td><?php echo $obj->city ?></td>
							 <td><?php echo $obj->score1 ?></td>
							 <td><?php echo $obj->score2 ?></td>
							 <td><?php echo $obj->score3 ?></td>
							 <td><?php echo $obj->score4 ?></td>
							 <td><?php echo $obj->created_timestamp ?></td>
						
                  
                       </tr>
											<?php } } ?>
               
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

