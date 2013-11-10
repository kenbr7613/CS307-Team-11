<?php
	include('session_logout.php');
	session_unset();
	header("location:index.php");	

?>
