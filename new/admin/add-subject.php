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
            global $conn;
        if(isset($_POST['submit']) AND $_POST['submit'] == 'Add'){
				$objsub=new stdclass();
				$objsub->subject= $subject = isset($_POST['subject'])?$_POST['subject']:'';
				$userdata->Addsubject($objsub);
			 if($userdata){
			       exit("<script>window.location.href='view-subject.php?add=Added';</script>");
			  }else{
				   echo "There is some problem in inserting record";
		}
		       }
		 
		 if(isset($_POST['submit']) AND $_POST['submit'] == 'Update'){
				$id=$_GET['id'];
				$subject = $_POST['subject'];
				$userdata->Editsubject($subject,$id);
		
			   echo "<script>window.location='view-subject.php?id=Updated';</script>";
		 }
		 
		 if(isset($_GET['id']))
		 {
		    $subjectinfo = $userdata->getsubjectinfo($_GET['id']);
			$subject = $subjectinfo->subject;
		 }else{
			$subject = '';
		 }
		 
 

?>
<?php   
require 'include/header.php'; 
require 'include/side-bar.php';
?>
<style>
.errorMSG{
	color:red;
	}
	.modal-dialog{
    width: 1000px;
    margin: 30px auto;
}
	.modal-info .modal-body {
    background-color: rgba(255, 0, 0, 0.02) !important;
		
}
</style>
  <div class="content-wrapper">
    
	   <section class="content">
           <div class="row">
			  <div class="col-md-12">
        
                <div class="box box-primary">
                   <div class="box-header with-border">
					  <h4 class="box-title">Manage Subject</h4>

					  <div class="box-tools pull-right">
						<a href="view-subject.php" class="btn btn-primary">Subject List</a>
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus" style="color:black"></i></button>
						<!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove" style="color:red"></i></button>-->
					  </div>
					</div>
                    <form name="addcouponform"  Method="POST" id="addcouponform" onsubmit="return ActionAddCoupon();" action="">
                     <div class="box-body">
						<div class="col-md-6">
						   <div class="form-group">
						     <label  for="reporttitle">Subject : </label>
						        <div class="input-group">
						            <div class="input-group-addon">
						                  <i class="fa fa-tasks"></i>
						             </div>
						      <input type='text' name='subject' value="<?=$subject?>" class='form-control reporttitle' placeholder="Enter Your subject" required/>
						      </div>
						   </div>
						</div>
              </div>
              <div class="box-footer">
                <input type="submit" name="submit" value="<?php if(isset($_GET['id'])) echo 'Update'; else echo 'Add';  ?>" class="btn btn-primary">
              </div>
            </form>
          </div>
        
        </div>
		</div>
		</div>
		</section>
 
   <?php include("include/footer.php");?>
  

   
