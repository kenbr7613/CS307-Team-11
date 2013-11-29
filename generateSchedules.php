<?php
	include('session_login_check.php');
	include('linked_sections.php');
	include('getWalkTime.php');
	
	$builtSched = 0;
	$scheds = array();
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
	function sameDays($days1, $days2) {
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
			if (sameDays($days, $daysT)) {
				if (!((lessThan($start, $front) && lessThan($end, $front)) || (greaterThan($start, $back) && greaterThan($end, $back)))) {
					// conflict
					return false;
				}
			}
		}
		return true;
	}
	function numberOfFriends($section) {
		global $friendsSections;
		$friends = 0;
		if (isset($friendsSections[$section])) {
			$friends = $friendsSections[$section];
		}
		return $friends;
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
				$prevSection = "";
				$scheds[$totalBuilt-1] = array();
				foreach ($sections as $section) {
					$friends = numberOfFriends($section);
					if ($prevSection = "") {
						$walking = 0;
					} else {
						$walking = getWalkTime($prevSection, $section);
					}
					array_push($scheds[$totalBuilt-1], array($section, $friends, $walking));
					$prevSection = $section;
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
	
	$sql = sprintf("select a.CRN from CourseOfferings a, UserSchedule b where b.UserID in (%s) and a.CourseOfferingID=b.CourseOfferingID;", implode(",", $friends));
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)) {
		if (!isset($friendsSections[$row[0]])) {
			$friendsSections[$row[0]] = 1;
		} else {
			$friendsSections[$row[0]]++;
		}
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
