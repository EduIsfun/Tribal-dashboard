<?php
    require_once('controller/pages/config.php');// Selecting Database from Server
 if(($_POST['submit']) && isset($_FILES['my_file'])){
$firstname = $_POST['fld_teachername'];
$lastname = $_POST['lastname'];
$gender = $_POST['fld_teachersex'];
$dob = $_POST['dob'];
$from_email = $_POST['email'];
$phone1 = $_POST['fld_contactno'];
$matrial_status = $_POST['matrial_status'];
$country = $_POST['country'];
$state = $_POST['state'];
$phone2 = $_POST['phone'];
$salary = $_POST['salary'];
$percentage = $_POST['fld_presentable'];
$communication = $_POST['fld_communication'];
$knowledge = $_POST['fld_knowledge'];
$qualification = $_POST['fld_qualification'];
$working_field = $_POST['fld_workingfield'];
$class = $_POST['class'];
$subject = $_POST['subject'];
$permanantaddress = $_POST['fld_teacheradd'];
$mailingaddress = $_POST['mailing_address'];

    $recipient_email = 'uattamk92@gmail.com,cross.atlanta@gmail.com'; //recipient email
    $subject = 'Query from S P Arora Teachers Placement'; //subject of email
    
	$message = 'You got an enquiry at S P Arora Teachers Placement with following details:.
  

			 Name: '.$firstname.' '.$lastname.' 
			 Gender: '.$gender.'
			 Mobile: '.$phone1.'  
			 Salary : '.$salary.'
			 Email : '.$from_email.' 
			 Experience Field: '.$percentage.'
			 Interest For Work Area: '.$communication.' 
			 Years of Experience: '.$knowledge.'  
			 Professional Qualification: '.$qualification.'   
			 Postal Address: '.$mailingaddress.'		
			';
	
    //get file details we need

    $file_tmp_name    = $_FILES['my_file']['tmp_name'];
    $file_name        = $_FILES['my_file']['name'];
    $file_size        = $_FILES['my_file']['size'];
    $file_type        = $_FILES['my_file']['type'];
    $file_error       = $_FILES['my_file']['error'];

    

    $user_email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if($file_error>0)
    {
        die('upload error');

    }

    //read from the uploaded file & base64_encode content for the mail

      $handle = fopen($file_tmp_name, "r");
      $content = fread($handle, $file_size);
      fclose($handle);
      $encoded_content = chunk_split(base64_encode($content));
        $boundary = md5("sanwebe"); 

        //header

        $headers = "MIME-Version: 1.0\r\n"; 

        $headers .= "From:".$from_email."\r\n"; 

        $headers .= "Reply-To: ".$user_email."" . "\r\n";

        $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n"; 

        

        //plain text 

        $body = "--$boundary\r\n";

        $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";

        $body .= "Content-Transfer-Encoding: base64\r\n\r\n"; 

        $body .= chunk_split(base64_encode($message)); 

        

        //attachment

        $body .= "--$boundary\r\n";

        $body .="Content-Type: $file_type; name=\"$file_name\"\r\n";

        $body .="Content-Disposition: attachment; filename=\"$file_name\"\r\n";

        $body .="Content-Transfer-Encoding: base64\r\n";

        $body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n"; 

        $body .= $encoded_content; 

    

    $sentMail =mail($recipient_email, $subject, $body, $headers);

    if($sentMail) //output success or failure messages
{
    ?>
     <script>
        alert('Resume Sent successfully');
        window.location.href='applyvacancy.php?success';
        </script>
  <?php
 }
 else
 {
  ?>
  <script>
  alert('error while uploading file');
        window.location.href='applyvacancy.php?fail';
        </script>
  <?php
 }
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Online placement</title>

<link href="css/stylesheet.css" rel="stylesheet" type="text/css" />
<link href="css/menus.css" rel="stylesheet" type="text/css" />

<script language="javascript" type="text/javascript" src="js/slideshow.js"></script>
<script language="javascript" type="text/javascript" src="Scripts/AC_RunActiveContent.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
        <link href='custom.css' rel='stylesheet' type='text/css'>
       <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
       <script src="//code.jquery.com/jquery-1.10.2.js"></script>
       <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
       <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
     $(function() {
     $( "#datepicker" ).datepicker();
     });
  </script>
<style>
label {
	font-size:12px;
}
</style>
<style>
		input::-webkit-input-placeholder 
		{
			color: blue !important;
			font-weight: normal;
			
			}
			input:-moz-placeholder {
				color: blue !important;
				font-weight:normal;
			}
			input:-ms-input-placeholder {
				color: blue !important;
				font-weight: normal !important;
		}
		textarea::-webkit-input-placeholder 
		{
			color: blue !important;
			font-weight: normal;
			}
			textarea:-moz-placeholder {
				color: blue !important;
				font-weight: normal;
			}
			textarea:-ms-input-placeholder {
				color: blue !important;
				font-weight: normal;
		}
		label{color:black;}
		
		
	</style>
</head>

<body onload="runSlideShow();">
<center>
<div id="main-div">

<div id="mainnav" align="left">
<div class="rhm1">
    <div class="rhm1-bg">
    <ul>
          <li class="current"><a href="index.php"><span>Home</span></a></li>
        <li><a href="aboutus.php"><span>About Us</span></a></li>
        <li><a href="forteacher.php"><span> For Teachers</span></a></li>
         <li><a href="applyvacancy.php"><span>Apply</span></a></li>
        <li><a href="contactus.php"><span>Contact Us</span></a></li>
    </ul>
    </div>
</div>
<div class="clear"></div>
<div id="flashArea" style="margin-top:5px;">
	<div id="fladsh-right">	
	<img src="gifs/slide1.jpg" alt=name="SlideShow" width="996" height="314" id="SlideShow" /></div>
	 <div class="line"></div>
</div>
<div class="clear2"></div>
<div id="txt2" style="margin-top:0px;">
<div class="entry-wrap clearfix">

<h1 style="margin-left:50px;"><b>Teachers' Placement For Public Schools &#8211; Online Application Form</b></h1>
<hr />
<h5 style="margin-left:50px;">Please download the Sample Resume Word Document linked below which you will need to complete and then upload to the on-line form.</h5>
<h4 style="text-align: center;link-decoration:none;"><a href="resume_sample/SampleTPG_Resume.docx">Sample Resume Word Document<br />
&nbsp;<br />
<img src="resume_sample/word_doc_icon.png" class="aligncenter" /><br />
</a></h4>
<hr />	
	<h3>Personal Information</h3><h3><?php  echo $output; ?></h3>
       <form  method="post" enctype="multipart/form-data" id="contact-form">		
                        <div class="messages"></div>

                        <div class="controls">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_name">First Name<font color="#FF0000">*</font></label>
                                        <input id="form_name" type="text" name="fld_teachername" style="border: 1px solid black;border-radius:10px" class="form-control" placeholder="Please enter your firstname" required="required" data-error="Firstname is required.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_lastname">Last Name <font color="#FF0000">*</font></label>
                                        <input id="form_lastname" type="text" name="lastname" class="form-control" style="border: 1px solid black;border-radius:10px" placeholder="Please enter your lastname " required="required" data-error="Lastname is required.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>															
                            </div>
							
							 <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
									<div style="margin-right:200px;">
                                        <label for="form_name" >Gender</label>
										<select class="form-control"  style="border: 1px solid black;"name="fld_teachersex" required>
										<option>-Select Gender-</option>
										<option value="Male">Male</option><option value="Female">Female</option></select>                                       
                                        <div class="help-block with-errors"></div>
                                    </div>
								   </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_lastname">Date Of Birth<font color="#FF0000">*</font></label>
                                        <input   id="datepicker"  type="text" name="dob" class="form-control" style="border: 1px solid black;border-radius:10px"placeholder="Please enter your Date of Birth " required="required" data-error="Date of Birth is required.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>															
                            </div>
							
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_email">email <font color="#FF0000">*</font></label>
                                        <input id="form_email" type="email" name="email" class="form-control" style="border: 1px solid black;border-radius:10px"placeholder="Please enter your email *" required="required" data-error="Valid email is required.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_phone">Mobile Number1 <font color="#FF0000">*</font></label>
                                        <input id="form_phone" type="tel" name="fld_contactno" style="border: 1px solid black;border-radius:10px"class="form-control" placeholder="Please enter your phone">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
							
							
							 <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_email">Mobile Number2</label>
                                        <input  type="phone" name="phone" style="border: 1px solid black;border-radius:10px"class="form-control" placeholder="Please enter your Mobile *">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_phone">Expected Salary</label>
                                        <input  type="tel" name="salary" class="form-control" style="border: 1px solid black;border-radius:10px" placeholder="Please enter Expected Salary">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
							
							<hr/>
							
							<div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="form_email">Marital Status*</label>									 
									    <select class="form-control" style="border: 1px solid black;"name="matrial_status">
										    <option value="married">--Select Marital--</option>
											<option value="married">Married</option>
										    <option value="unmarried">Un-Married</option>
										</select>                                     									
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
							   
							   
                               <div class="col-md-4">
                                 <div class="form-group">
                                        <label for="form_phone">Country *</label>										
                                       <select class="form-control" style="border: 1px solid black;"name="country">
									   <option value="">-Select Country-</option>
									   <option value="Afghanistan">Afghanistan</option>
									   <option value="Albania">Albania</option>
									   <option value="Algeria">Algeria</option>
									   <option value="American Samoa">American Samoa</option>
									   <option value="Andorra">Andorra</option>
									   <option value="Angola">Angola</option>
									   <option value="Anguilla">Anguilla</option>
									   <option value="Antarctica">Antarctica</option>
									   <option value="Antigua and Barbuda">Antigua and Barbuda</option>
									   <option value="Argentina">Argentina</option>
									   <option value="Armenia">Armenia</option>
									   <option value="Arctic Ocean">Arctic Ocean</option>
									   <option value="India" selected>India</option>
									   <option value="Ashmore and Cartier Islands">Ashmore and Cartier Islands</option>
									   <option value="Atlantic Ocean">Atlantic Ocean</option>
									   <option value="Australia">Australia</option>
									   <option value="Austria">Austria</option>
									   <option value="Azerbaijan">Azerbaijan</option>
									   <option value="Bahamas">Bahamas</option>
									  
									   </select>                                   
                                     <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								
								 <div class="col-md-4">								
                                    <div class="form-group">
                                        <label for="form_phone">State*</label>								
                                       <select class="form-control"style="border: 1px solid black;" name="state">
									   <option value="">-Select State*-</option>									  
									   <option value="Albania">Delhi</option>
									   <option value="Algeria">Bihar</option>
									   <option value="American Samoa">Panjab</option>
									   <option value="Andorra">Gujrat</option>
									   <option value="Angola">Jharakhand</option>
									   <option value="Anguilla">Kolkatta</option>
									   <option value="Antarctica">Hariyana</option>									   
									   </select>                                          
                                        <div class="help-block with-errors"></div>
                                    </div>                              
								</div>
							 </div><!-- For State END-->
								
								
							
						   
						       <div class="row">
								 <div class="col-md-4">		<!-- For Percentage-->						
                                    <div class="form-group">
                                       <label for="form_phone">Experience Field*</label>										
                                       <select class="form-control" style="border: 1px solid black;"name="fld_presentable">
									    <option value="">-Select Experience-</option>
									    <option value="coaching">coaching</option>
										<option value="College">College</option>	
				                    	<option value="School">School</option>
					                   															  
									  </select>
                                            </div>
                                        <div class="help-block with-errors"></div>
                                    </div>                              
								
								
								 <div class="col-md-4">		<!-- For Communication-->						
                                    <div class="form-group">
                                        <label for="form_phone">Years of Experience*</label>										
                                       <select class="form-control"style="border: 1px solid black;" name="fld_communication">
									       <option value="">-Select Years-</option>
                                           <option value="1-year">1-year</option>
					                       <option value="2-years">2-years</option>
					                       <option value="3-5year">3-5 years</option>
                                           <option value="5-7years">5-7 years</option>	
                                           <option value="7-10 years">7-10 years</option>	
                                          <option value="More Than 10 years">More Than 10 years</option>	
									  </select>
                                     </div>
                                    <div class="help-block with-errors"></div>                                                               
								</div>
								
								
								<div class="col-md-4">		<!-- For Knowledge-->						
                                    <div class="form-group">
                                        <label for="form_phone">Interest For Work Area*</label>										
                                       <select class="form-control" style="border: 1px solid black;"name="fld_knowledge">
									       <option value="">-Select Location-</option>
									       <option value="Chander Bihar">Delhi and NCR</option>			                      
                                           <option value="Noida">Noida</option>	
                                           <option value="Greter Noida">Greter Noida</option>										   
									  </select>                                         
                                        <div class="help-block with-errors"></div>
                                    </div>                              
								</div>							
							 </div>
							 
							 
							 <hr/>
							 
							 	
							<div class="row">
								 <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_name">Professional Qualification<font color="#FF0000">*</font></label>
                                        <input type="text" name="fld_qualification" class="form-control" style="border: 1px solid black;border-radius:10px"placeholder="Please enter your Qualification " required="required" data-error="Firstname is required.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_name">Working Field<font color="#FF0000">*</font></label>
                                        <input  type="text" name="fld_workingfield" class="form-control" style="border: 1px solid black;border-radius:10px"placeholder="Please enter Working Field" required="required" data-error="Firstname is required.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>								
						   </div>
							 
							  <div class="row">
								<div class="col-md-6">		<!-- For Class-->						
                                    <div class="form-group">
                                        <label for="form_phone">Class*</label>
										<div style="margin-right:200px;">
                                        <select  type="text" name="class" id="class"   style="border: 1px solid black;"class="form-control">
                                        <option value="">Select Class</option>
					                      <?php
                                         $query = "SELECT * FROM `tbl_class`";
                                         $result = mysql_query($query);
                                         while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                                         echo "<option value='".$row['id']."'>".$row['class']."</option>";
                                             }
                                           ?>
				                         </select>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>                              
								</div>
								
								
								
								<div class="col-md-6">		<!-- For Class-->						
                                    <div class="form-group">
                                        <label for="form_phone">Subject*</label>
										<div style="margin-right:200px;">
                                     <select  type="text" name="subject" id="subject" style="border: 1px solid black;" class="form-control">
                                     <option value="">Select Subject</option>
					                   <?php
                                         $query = "SELECT * FROM `tbl_subject`";
                                         $result = mysql_query($query);
                                         while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                                         echo "<option value='".$row['id']."'>".$row['subject']."</option>";
                                             }
                                           ?>
				                      </select>
                                            </div>
                                        <div class="help-block with-errors"></div>
                                    </div>                              
								</div>
								
                            </div>
							
							
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
									
                                        <label for="form_message">Permanant Address*</label>
                                        <textarea  name="fld_teacheradd" class="form-control" style="border: 1px solid black;border-radius:10px"placeholder="Please Enter Permanant Address" rows="4" required="required" data-error="Please,leave us a message."></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>							
                                </div>
								
								 <div class="col-md-6">
                                    <div class="form-group">							
                                        <label for="form_message">Postal Address <font color="#FF0000">*</font></label>
                                        <textarea id="form_message" name="mailing_address" class="form-control" style="border: 1px solid black;border-radius:10px"placeholder="Message for me *" rows="4" required="required" data-error="Please,leave us a message."></textarea>
                                        <div class="help-block with-errors"></div>							
                                    </div>
                                </div>

                            </div>
                           
						   
						   	<td colspan="2">
                                <h4>Resume (In TPG/jpg/jpeg/.doc/txt Format) <span class="required">*</span></h4>
                                <p><span class="wpcf7-form-control-wrap resume"><input type="file" name="my_file" size="40" class="wpcf7-form-control wpcf7-file wpcf7-validates-as-required" aria-required="true" aria-invalid="false" /></span><br />
                                <h5><em>Please download the file at the top of this application page and complete.  After completing the resume please upload the completed form labeled  LastName_FirstName_SUBJECT_TPGResume.pdf</em></h5>
                                </p>
                            </td>
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-success btn-send" name="submit" value="Send ">
                                </div>
                        </div>

                    </form>
		
</div><!-- eend  /.entry-content -->
<?php include("includes/footer.php")?>
</div>
</div>

</div>
</center>
 <script src="validator.js"></script>
 <script src="contact.js"></script>
 <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-79157708-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
