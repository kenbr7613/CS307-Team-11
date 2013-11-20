<?php
	include('session_login_check.php');
	include "DBaccess.php";
	
	$picked = $_POST['picked'];
	$pickedList = array_filter(explode(" ", $picked));
	
	$wantedClasses = array_filter(explode(" ", $_POST['wantedClasses']));
	
	$grouplist = $_SESSION['list'];
	
	$extraCourses = $_POST['numCourses'];
	$extraCourses = $extraCourses - count($pickedList);
	
	$pickedList = array_merge($wantedClasses, $pickedList);
	
	foreach (array_keys($grouplist) as $key1) {
		foreach (array_keys($grouplist[$key1]) as $key2) {
			if (in_array($grouplist[$key1][$key2], $pickedList)) {
				unset($grouplist[$key1][$key2]);
			}
		}
		if (count($grouplist[$key1]) == 0) {
			unset($grouplist[$key1]);
		}
	}
	
	
	$sets = array();
	if ($extraCourses > 0) {
		$list = array();
		while (1) {
			$isEmpty = 1;
			foreach (array_keys($grouplist) as $key) {
				$isEmpty = 0;
				array_push($list, array_shift($grouplist[$key]));
				if (count($grouplist[$key]) == 0) {
					unset($grouplist[$key]);
				}
			}
			if ($isEmpty) {
				break;
			}
		}
		
		
		$num = count($list);
		for ($a = 0; $a < $num-$extraCourses+1; $a++) {
			if ($extraCourses == 1) {
				array_push($sets, array_merge($pickedList, array($list[$a])));
			} else {
				for ($b = $a+1; $b < $num-$extraCourses+2; $b++) {
					if ($extraCourses == 2) {
						array_push($sets, array_merge($pickedList, array($list[$a], $list[$b])));
					} else {
						for ($c = $b+1; $c < $num-$extraCourses+3; $c++) {
							if ($extraCourses == 3) {
								array_push($sets, array_merge($pickedList, array($list[$a], $list[$b], $list[$c])));
							} else {
								for ($d = $c+1; $d < $num-$extraCourses+4; $d++) {
									if ($extraCourses == 4) {
										array_push($sets, array_merge($pickedList, array($list[$a], $list[$b], $list[$c], $list[$d])));
									} else {
										for ($e = $d+1; $e < $num-$extraCourses+5; $e++) {
											if ($extraCourses == 5) {
												array_push($sets, array_merge($pickedList, array($list[$a], $list[$b], $list[$c], $list[$d], $list[$e])));
											} else {
												for ($f = $e+1; $f < $num-$extraCourses+6; $f++) {
													if ($extraCourses == 6) {
														array_push($sets, array_merge($pickedList, array($list[$a], $list[$b], $list[$c], $list[$d], $list[$e], $list[$f])));
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	} else {
		array_push($sets, $pickedList);
	}
	
	$_SESSION['setsOfCourses'] = $sets;
	header("location:temp_generateSchedules.php");
	
	// for ($i = 0; $i < count($sets); $i++) {
		// print_r($sets[$i]);
		// print("<br />");
	// }
?>
