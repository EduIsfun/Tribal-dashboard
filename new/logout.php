<?php
	ob_start();
	session_start();
	session_unset();
	unset($_SESSION['currentinfo']);
	unset($_SESSION['currentuser']);
	unset($_SESSION['currentusernew']);
	header("location:index.php");
	ob_clean();
?>