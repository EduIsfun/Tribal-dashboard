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
				$objchap=new stdclass();
				$objchap->chapter= $chapter = isset($_POST['chapter'])?$_POST['chapter']:'';
				$objchap->subjectID= $subjectID = isset($_POST['subjectID'])?$_POST['subjectID']:'';
				$objchap->grade= $grade = isset($_POST['grade'])?$_POST['grade']:'';
				$objchap->exam= $exam = isset($_POST['exam'])?$_POST['exam']:'';
				$userdata->Addchapter($objchap);
			 // if($userdata){
			       // exit("<script>window.location.href='view-chapter.php?add=Added';</script>");
			  // }else{
				   // echo "There is some problem in inserting record";
		// }
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
					  <h4 class="box-title">Manage Chapter</h4>

					  <div class="box-tools pull-right">
						<a href="view-subject.php" class="btn btn-primary">Chapter List</a>
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus" style="color:black"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove" style="color:red"></i></button>
					  </div>
					</div>
                    <form  Method="POST" action="">
                     <div class="box-body">
					 <div class="col-md-6">
					  <div class="form-group">
					<label>Subject</label>
						<select class="form-control select2" style="width: 100%;" id="subjectID" name="subjectID">
							<option value="" selected="selected">Select Menu</option>
							<?php
								$sqls = "SELECT * FROM subject ORDER BY subject ASC ";
								$results = mysqli_query($conn,$sqls);
								while($menulist = mysqli_fetch_assoc($results)){
							?>
									<option value="<?=$menulist['subjectID']?>"><?=$menulist['subject']?></option>
							<?   }   ?>	
						</select>
				  </div>
				  </div>
				
						<div class="col-md-6">
						   <div class="form-group">
						     <label  for="reporttitle">Chapter : </label>
						        <div class="input-group">
						            <div class="input-group-addon">
						                  <i class="fa fa-tasks"></i>
						             </div>
						      <input type='text' name='subject' value="<?=$subject?>" class='form-control reporttitle' placeholder="Enter Your subject" id="subject">
						      </div>
						   </div>
						</div>
						
						<div class="col-md-6">
						   <div class="form-group">
						     <label  for="reporttitle">Grade : </label>
						        <div class="input-group">
						            <div class="input-group-addon">
						                  <i class="fa fa-tasks"></i>
						             </div>
						      <input type='text' name='grade' value="<?=$subject?>" class='form-control reporttitle' placeholder="Enter Your Grade" id="grade">
						      </div>
						   </div>
						</div>
						
						<div class="col-md-6">
						   <div class="form-group">
						     <label  for="reporttitle">Exam : </label>
						        <div class="input-group">
						            <div class="input-group-addon">
						                  <i class="fa fa-tasks"></i>
						             </div>
						      <input type='text' name='exam' value="<?=$subject?>" class='form-control reporttitle' placeholder="Enter Your Exam" id="exam">
						      </div>
						   </div>
						</div>
              </div>
              <div class="box-footer">
                <input type="submit" name="submit" value="<? if(isset($_GET['id'])) echo 'Update'; else echo 'Add';  ?>" class="btn btn-primary">
              </div>
            </form>
          </div>
        
        </div>
		</div>
		</div>
		</section>
 
   <?php include("include/footer.php");?>
  

   
