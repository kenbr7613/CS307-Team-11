<?php
	include('session_login_check.php');
	include('DBaccess.php');
	
	$schedules = $_SESSION['setsOfSchedules'];
	
	$i = 0;
	foreach ($schedules as $schedule) {
		printf("schedule %d<br />", ++$i);
		foreach ($schedule as $section) {
			// $crn = CRN of section
			$crn = $section[0];
			
			// $friends = number of friends in that section
			$friends = $section[1];
			
			// $walking = 0 if user can make it to this class no problem
			// $walking = 1 if user can almost make it to this (<3 minutes to spare)
			// $walking = 2 if user can not make it to this class
			$walking = $section[2];
			
			printf("CRN: %s, %s number of friends in this section, ", $crn, $friends);
			if ($walking == 0) {
				printf("user can make this class");
			} else if ($walking == 1) {
				printf("user can almost make this class");
			} else if ($walking == 2) {
				printf("user can NOT make this class");
			}
			printf("<br />");
		}
		printf("<br />");
	}
?>
