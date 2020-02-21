<?php
	ob_start();
	session_start();
	session_unset();
	unset($_SESSION['username']);
	header("location:index.php");
	ob_clean();
?>