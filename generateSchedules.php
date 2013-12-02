<?php
	include('session_login_check.php');
	include('linked_sections.php');
	include('getWalkTime.php');
	
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
	function after($sectionAfter, $sectionPrev) {
		$sql = sprintf("select StartTime,EndTime from CourseOfferings where CRN=%s;", $sectionPrev);
		$result = mysql_query($sql);
		$row = mysql_fetch_row($result);
		$prevStart = $row[0];
		$prevEnd = $row[1];
		
		$sql = sprintf("select StartTime,EndTime from CourseOfferings where CRN=%s;", $sectionAfter);
		$result = mysql_query($sql);
		$row = mysql_fetch_row($result);
		$nextStart = $row[0];
		$nextEnd = $row[1];
		
		$nextStart = str_replace(":", "", $nextStart);
		$prevEnd = str_replace(":", "", $prevEnd);
		
		if (intval($nextStart) > intval($prevEnd)) {
			return 1;
		} else {
			return 0;
		}
	}
	function buildSchedule ($courses, $sections, $times) {
		global $scheds;
		global $allSections;
		global $totalBuilt;
		global $totalTimes;
		if ($totalBuilt >= 10) {
			return;
		}
		
		if (count($courses) == 0) {
			# built schedule, $sections contains all CRNs of sections
			
			# find all sections whose times aren't already known and put them in $neededSections, put sections whose times are known in $allTimes
			// $allTimes = array();
			// $neededSections = array();
			// foreach ($sections as $section) {
				// if (isset($totalTimes["$section"])) {
					// array_push($allTimes, $totalTimes["$section"]);
				// } else {
					// array_push($neededSections, $section);
				// }
			// }
			
			// # find all times of neededSections and put them in $allTimes
			// if (count($neededSections) > 0) {
				// $sql = sprintf("select StartTime,EndTime,Days from CourseOfferings where CRN in (%s);", implode(",", $neededSections));
				// $result = mysql_query($sql);
				// while ($row = mysql_fetch_array($result)) {
					// $tmp = array($row[0], $row[1], $row[2]);
					// $totalTimes["$section"] = $tmp;
					// array_push($allTimes, $tmp);
				// }
			// }
			
			// # make sure there's no conflict in times among everything in $allTimes as well as those in $times
			// $isConflict = 0;
			// foreach ($allTimes as $time) {
				// if (noConflict($time[0], $time[1], $time[2], $times)) {
					// array_push($times, $time);
				// } else {
					// $isConflict = 1;
					// break;
				// }
			// }
			
			$isConflict = 0;
			if (!$isConflict) {
				$totalBuilt++;				
				$builtSched = 1;
				$prevSection = "";
				$scheds[$totalBuilt-1] = array();
				foreach ($sections as $section) {
					$friends = numberOfFriends($section);
					$walking = 0;
					foreach ($sections as $prevSection) {
						if ($prevSection != $section) {
							$walking = getWalkTime($prevSection, $section);
							if ($walking != 0 && after($section, $prevSection)) {
								break;
							}
						}
					}
					if ($walking == 2) {
						$walking = 1;
					}
					array_push($scheds[$totalBuilt-1], array($section, $friends, $walking));
					$prevSection = $section;
				}
			}		
		} else {
			$course = array_shift($courses);
			$combinations = $allSections["$course"];	
			foreach ($combinations as $comb) {
				#buildSchedule($courses, array_merge($sections, $comb), $times);
			
			
				# find all sections whose times aren't already known and put them in $neededSections, put sections whose times are known in $allTimes
				$allTimes = array();
				$neededSections = array();
				foreach ($comb as $section) {
					if (isset($totalTimes["$section"])) {
						array_push($allTimes, $totalTimes["$section"]);
					} else {
						array_push($neededSections, $section);
					}
				}
				
				# find all times of neededSections and put them in $allTimes
				if (count($neededSections) > 0) {
					$sql = sprintf("select StartTime,EndTime,Days,CRN from CourseOfferings where CRN in (%s);", implode(",", $neededSections));
					$result = mysql_query($sql);
					while ($row = mysql_fetch_array($result)) {
						$crn = $row[3];
						$tmp = array($row[0], $row[1], $row[2]);
						$totalTimes["$crn"] = $tmp;
						array_push($allTimes, $tmp);
					}
				}
				
				# make sure there's no conflict in times among everything in $allTimes as well as those in $times
				$isConflict = 0;
				foreach ($allTimes as $time) {
					if (noConflict($time[0], $time[1], $time[2], $times) == 0) {
						$isConflict = 1;
						break;
					}
				}
				if (!$isConflict) {
					buildSchedule($courses, array_merge($sections, $comb), array_merge($times, $allTimes));
				}
			}
		}
	}
	
	$db = dbConnect();
	$begin = time();
	
	$userid = $_SESSION['login'];
	$friendsSections = array();
	$friends = array();
	$scheds = array();
	
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
	$scheds = array();
	$allSections = array();
	$totalTimes = array();
	
	foreach ($sets as $set) {
		foreach ($set as $course) {
			if (!isset($allSections["$course"])) {
				$linkedSections = getLinkedClasses($course);
				if (count($linkedSections) == 0) {
					$sql = sprintf("select CRN from CourseOfferings where CourseID=%s;", $course);
					$result = mysql_query($sql);
					while ($row = mysql_fetch_array($result)) {
						array_push($linkedSections, array($row[0]));
					}
				}
				$allSections["$course"] = $linkedSections;
			}
		}
		buildSchedule($set, array(), $times);
		if ($totalBuilt > 0) {
			break;
		}
	}
	
	if ($totalBuilt > 0) {
		// atleast one schedule was built
		$_SESSION['setsOfSchedules'] = $scheds;
		$_SESSION['timeSpent'] = time() - $begin;
		header("location:pickSchedule.php");
	} else {
		// no schedule was built
		printf("no schedule could be built, show the user a button to go back to suggestSchedule.php<br />");
	}
?>
