<?php
	include('session_login_check.php');
	include('DBaccess.php');
	
	$scheds = $_SESSION['setsOfSchedules'];
	
	$i = 0;
	foreach ($scheds as $sched) {
		printf("sched %d: ", $i++);
		print_r($sched);
		print("<br />");
	}
?>
