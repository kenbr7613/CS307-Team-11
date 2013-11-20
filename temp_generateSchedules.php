<?php
	include('session_login_check.php');
	include "DBaccess.php";
	
	$sets = $_SESSION['setsOfCourses'];
	printf("<table border=\"0\"");
	for ($i = 0; $i < count($sets); $i++) {
		print("<tr>");
		printf("<td>");
		printf("set %d: ", $i+1);
		printf("</td><td>");
		print_r($sets[$i]);
		print("<td></tr>");
	}
?>
