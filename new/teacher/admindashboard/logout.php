<?php
	ob_start();
	session_start();
	session_unset();
	unset($_SESSION['dbname']);
	header("location:index.php");
	ob_clean();
?>