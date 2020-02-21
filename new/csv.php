<?php
		
error_reporting(E_ALL);
include('db_config.php');
	
		$row = 0;
		$tempFileName = "file-uploads/school.csv";
		if (($handle = fopen($tempFileName, "r")) !== FALSE) {
			
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					
                    if ($row > 0){
							
						$userID=$data[0];
						$school=$data[1];
						// $board_id=$data[2];
						// $grade=$data[3];
						// $section=$data[4];
						// $current=$data[5];
						// $city_id=$data[6];
						// $grade_id=$data[7];
						// $cityname=$data[8];
						$sqlget="SELECT * FROM user_school_details WHERE userID='$userID'";
						$resultget = mysqli_query($conn,$sqlget);
						$count=$resultget->num_rows;
						if($count>0){
							 $sqlupdate="UPDATE user_school_details SET school='$school' WHERE userID='$userID'";
							$result = mysqli_query($conn,$sqlupdate);
							
						}
						else{
							$sql = "INSERT INTO user_school_details (school,userID) values ('$school', '$userID') ";
							$result = mysqli_query($conn,$sql);
							
						}
						echo 'Data import successfully';	
					}
					$row++;
				}
			}
		
	//}

 ?>
 
 
 