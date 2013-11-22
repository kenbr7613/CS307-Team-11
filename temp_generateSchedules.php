<?php
	include('session_login_check.php');
	include('UserSchedule.php');
	
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
		//get user login
	$userid = $_SESSION['login'];
	//Create UserSchedule using loggin userid
	$schedule = new UserSchedule($userid);
	$schedule -> setMode(0);
	
	$db = dbConnect();
	
	$sets = $_SESSION['setsOfCourses'];
	//go through the sets
	foreach($sets as $courses) {
		$complete = false;
		$alist = array();
		//go through the courses
		foreach($courses as $courseID) {
			//get CRNS from courseID
			$sql = "select CRN from CourseOfferings where CourseID = $courseID";
			$result = mysql_query($sql, $db);
			if(!$result) {
				echo "db error\n";
				return -1;
			}
			$crns = array();
			while($row = mysql_fetch_array($result, MYSQL_ASSOC) ) {
				$crns[] = $row['CRN'];
			}
			
			$alist[] = $crns;		
		}
		
		//now try to add crns to schedule
		//go through a list
		foreach($alist as $cand){
			echo " M";
			//go through crns
			$suc = false;
			foreach($cand as $one){
				//sucessfull in adding a course
				echo $one;
				if($schedule->addCourse($one) != -1) {
					$suc = true;
					break;
				}
			}
			
			//course could not be added. Try different set
			if(!$suc) {
				$complete = false;
				//break;
			}
			if($suc) {
				$complete = true;
			}
		}
		if(!$complete) {
			//create new schedule if failed
			$schedule = new UserSchedule($userid);
			$schedule -> setMode(0);
		}
		else if($complete) {
			echo "<table border = '1'>";
			$schedule->listSchedule();
			echo "</table>";
		
			$schedule -> drawWeeklySchedule();
			return;
		}
	}
	
	//no schedule found
	print "\nNo schedule can be made\n";
	
	
?>
