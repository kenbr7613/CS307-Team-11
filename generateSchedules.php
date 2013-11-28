<?php
	include('session_login_check.php');
	include('UserSchedule.php');
	
	$builtSched = 0;
	$scheds = array();
	
	function getLinkedClasses($courseID) {
		global $db;
		//query to start linked section search(start from Lectures, Individual Studies, and Labratory Preparation
		$query_for_primary_classes = sprintf("SELECT CRN, LinkID, LinkedSection, CourseType  FROM CourseOfferings Where CourseID=%d AND (CourseType=3 OR CourseType=5 OR CourseType=6);", $courseID);
		$main_classes = mysql_query($query_for_primary_classes, $db);
		$result_array = array();
		//loop through all main classes and look for linked sections
		for ($i = 0; $i < mysql_num_rows($main_classes); $i++) {
			$row = mysql_fetch_assoc($main_classes);
			$LinkID_primary = $row["LinkID"];
			$LinkedSection_primary = $row["LinkedSection"];
			$CRN_primary = $row["CRN"];
			//Contains ALL classes not a lecture, IS, or Lab Prep associated with our CourseID
			$query = sprintf("SELECT CRN, LinkID, LinkedSection, CourseType  FROM CourseOfferings Where LinkID='%s' AND CourseID=%d AND (CourseType!=3 OR CourseType!=5 OR CourseType!=6);", 					$LinkedSection_primary, $courseID);
			$secondary_classes = mysql_query($query, $db) or die($query."<br/><br/>".mysql_error());
			for ($j = 0; $j < mysql_num_rows($secondary_classes); $j++) {
				$row2 = mysql_fetch_assoc($secondary_classes);
				$class_array = array();
				//add our lecture to the array
				$class_array[] = $CRN_primary;
				//insert into class array
				$class_array[] = $row2["CRN"];
				$LinkedSection_n = $row2["LinkedSection"];
				while ($LinkedSection_n != $LinkID_primary) {
					//continue going through the sections
					$linked_query = sprintf("SELECT CRN, LinkID, LinkedSection, CourseType  FROM CourseOfferings Where LinkID='%s' AND CourseID=%d AND (CourseType!=3 OR CourseType!=5 OR CourseType!=6);", 					$LinkedSection_n, $courseID);
					$n_classes = mysql_query($linked_query, $db) or die($linked_query."<br/><br/>".mysql_error());
					$obj = mysql_fetch_assoc($n_classes);
					$class_array[] = $obj["CRN"];
					$LinkedSection_n = $obj["LinkedSection"];
				}
				$result_array[] = $class_array;
			}
		}
		
		return $result_array;
	}
	function lessThan($time1, $time2) {
		$time1 = str_replace(":", "", $time1);
		$time2 = str_replace(":", "", $time2);
		if (strlen($time1) == 6) {
			$time1 = substr($time1, 0, 4);
		}
		if (strlen($time2) == 6) {
			$time2 = substr($time2, 0, 4);
		}
		if (intval($time1) < intval($time2)) {
			return true;
		} else {
			return false;
		}
	}
	function greaterThan($time1, $time2) {
		$time1 = str_replace(":", "", $time1);
		$time2 = str_replace(":", "", $time2);
		if (strlen($time1) == 6) {
			$time1 = substr($time1, 0, 4);
		}
		if (strlen($time2) == 6) {
			$time2 = substr($time2, 0, 4);
		}
		if (intval($time1) > intval($time2)) {
			return true;
		} else {
			return false;
		}
	}
	function sameDay($days1, $days2) {
		$days1Array = str_split($days1);
		$days2Array = str_split($days2);		
		$commonDays = array_intersect($days1Array, $days2Array);
		if (count($commonDays) == 0) {
			return false;
		} else {
			return true;
		}
	}
	function noConflict($start, $end, $days, $times) {
		foreach ($times as $time) {
			$front = $time[0];
			$back = $time[1];
			$daysT = $time[2];
			if (sameDay($days, $daysT)) {
				if (!((lessThan($start, $front) && lessThan($end, $front)) || (greaterThan($start, $back) && greaterThan($end, $back)))) {
					// conflict
					return false;
				}
			}
		}
		return true;
	}
	function friendsIn($sections) {
		global $friendsSections;
		foreach ($sections as $section) {
			if (in_array($section, $friendsSections)) {
				return 1;
			}
		}
		return 0;
	}
	function buildSchedule ($courses, $sections, $times) {
		global $builtSched;
		global $scheds;
		global $sind;
		global $allSections;
		global $totalBuilt;
		if ($totalBuilt >= 10) {
			return;
		}
		$db = dbConnect();
		
		if (count($courses) == 0) {
			# built schedule, $sections contains all CRNs of sections
			$sql = sprintf("select StartTime,EndTime,Days from CourseOfferings where CRN in (%s);", implode(",", $sections));
			$result = mysql_query($sql);
			$isConflict = 0;
			while ($row = mysql_fetch_array($result)) {
				if (noConflict($row[0], $row[1], $row[2], $times)) {
					array_push($times, array($row[0], $row[1], $row[2]));
				} else {
					$isConflict = 1;
					break;
				}
			}
			if (!$isConflict) {
				$totalBuilt++;				
				$builtSched = 1;
				if (friendsIn($sections)) {
					array_unshift($scheds, $sections);
				} else {
					array_push($scheds, $sections);
				}
			}		
		} else {
			$course = array_shift($courses);
			$combinations = $allSections["$course"];
			$allTimes = array();
			foreach ($combinations as $comb) {
				buildSchedule($courses, array_merge($sections, $comb), $times);
			}
		}
	}
	
	$db = dbConnect();
	
	$userid = $_SESSION['login'];
	$friendsSections = array();
	$friends = array();
	
	$sql = sprintf("select FriendUserID from UserFriends where UserID=%s;", $userid);
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)) {
		array_push($friends, $row[0]);
	}
	
	$sql = sprintf("select CourseOfferingID from UserSchedule where UserID in (%s);", implode(",", $friends));
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)) {
		array_push($friendsSections, $row[0]);
	}
	
	$sets = $_SESSION['setsOfCourses'];
	
	$times = array();
	$prefs = $_SESSION['schedPrefs'];
	foreach ($prefs as $pref) {
		array_push($times, array($pref[0].":".$pref[1], $pref[2].":".$pref[3], $pref[4]));
	}
	
	$ind = 0;
	$totalBuilt = 0;
	// $sind = 0;
	$scheds = array();
	$allSections;
	foreach ($sets as $set) {
		$allSections = array();
		foreach ($set as $course) {
			$allSections["$course"] = getLinkedClasses($course);
		}
		$ind++;
		printf("set %d: ", $ind);
		print_r($set);
		print("<br />");
		
		buildSchedule($set, array(), $times);
		if ($builtSched == 1) {
			break;
		}
	}
	if ($builtSched == 1) {
		// atleast one schedule was built
		$_SESSION['setsOfSchedules'] = $scheds;
		header("location:pickSchedule.php");
	} else {
		// no schedule was built
		printf("no schedule could be built, show the user a button to go back to suggestSchedule.php");
	}
?>
