<?php
$sql = "SELECT * FROM teacher_login WHERE userID = '".$_SESSION['adminuids']."'";
$result = mysqli_query($conn,$sql);
$obj = mysqli_fetch_object($result);

$state_id = $obj->state_id;
$city_id = $obj->city_id;
$board_id = $obj->board_id;
$schoollogo = $obj->schoollogo;
$classstdarray = explode(",",$obj->classstd);   
if (isset($_SESSION['schoolname']) && ($_SESSION['schoolname']!='')){
    $schoolname =$_SESSION['schoolname'];
	
} else {
    $schoolarray=array();
    $schoolarray = explode("|",$obj->school);
    if (count($schoolarray)==1){
        $schoolname =$obj->school;
    }   
}   

$sqlquery="SELECT MAX(ksa.timestamp) as maxtime FROM knowledgekombat_skill_attempt ksa INNER 
JOIN user_school_details usd ON ksa.userID=usd.userID WHERE 
LOWER(usd.school)= '".strtolower($schoolname)."'";
$resultmax = mysqli_query($conn,$sqlquery);
$objmax = mysqli_fetch_object($resultmax);	

$lastupdatedtime = $objmax->maxtime;
?>
<div class="header-top">
    <div class="container-fluid"> 
        <div class="row">
            <div class="col-md-4" style="padding: 10px;">
                <a href="principaldashboard.php"><img src="images/logo.png"></a> 
            </div> 
            <div class="col-md-4 logo">
           
                <input type="hidden" name="school_id" id="school_id" value="<?php echo $schoolname; ?>">
                <input type="hidden" name="state_id" id="state_id" value="<?php echo $state_id; ?>">
                <input type="hidden" name="city_id" id="city_id" value="<?php echo $city_id; ?>">
            </div> 
            <div class="col-md-4">
				
                <div class="right-text">
				<?php if ($_SESSION['adminuids']!=""){?>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
                    <ul style="list-style-type: none; text-align: center; color: #428bca;"> 
                        <li><a href="admindashboard.php" class='cmenu'>Change user</a>  <span style="color:#18294f;"> | </span><a href="adminpassword.php" class='cmenu'>Change Password</a><span style="color:#18294f;"> | </span> <a href="logout.php" class='cmenu'>Sign out</a></li>
                    </ul>
				<?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- header -->