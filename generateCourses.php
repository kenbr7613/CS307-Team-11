<html>
	<head>
	</head>
	<script>
		function setGroupsInputValue() {
			var selects = document.getElementsByTagName("select");
			var input = document.getElementById("groupsInput");
			for (var i = 0; i < selects.length; i++) {
				var val = selects[i].options[selects[i].selectedIndex].value;
				var id = selects[i].id;
				input.value = input.value + " " + id + ":" + val + " ";
			}
		}
	</script>
	<body>
		<?php
			include('session_login_check.php');
			include "DBaccess.php";
			$neededClasses = array();
			$neededGroups = array();
			$preferences = array();
			$classOptions = array();
			$groupOptions = array();
			$wantedClasses = array();
			$coursesTaken = array();
			$degreeCourses = array();
			for ($i = 0; $i < 9; $i++) {
				$degreeCourses[$i] = array();
			}
			
			$noAmbiguity = 1;
			
			function hasMetRequirements($GroupID, $courses, $Flag) {			
				//setup database connections
				mysql_connect('lore.cs.purdue.edu:11394', 'root', 'cs307team11');
				mysql_select_db("purduePlannerDB"); 
				$sql = "SELECT * FROM RequirementGroups WHERE GroupID = " . $GroupID;
				$result = mysql_query($sql);

				//setup requirement number with Flag
				if($Flag == 0)
					$countNeeded = mysql_num_rows($result);
				else
					$countNeeded = $Flag;
				$count = 0;

				//loop through each item in group
				while($row = mysql_fetch_array($result))
				{
					if($row['Flag'] == 9999 && isIn($row['CourseID'], $courses))
						$count++;
					else if($row['Flag'] != 9999 && hasMetRequirements($row['SubGroupID'], $courses, $row['Flag']))
						$count++;
				}

				//compare the number of completed subrequirements against the number needed
				if($count >= $countNeeded)
					return true;
				else 
					return false;
			}

			function isIn($courseID, $courseList) {
				foreach ($courseList as $takenCourse)
					if($takenCourse == $courseID)
						return true;
				return false;
			}
			function getCourseInfo($CourseID) {
				$sql = sprintf("select Department,Level,Title from Courses where CourseID=%s;", $CourseID);
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				return sprintf("%s%s - %s", $row[0], $row[1], $row[2]);
			}
			function getGroupInfo($GroupID) {
				$sql = sprintf("select SubGroupDesc from RequirementGroups where SubGroupId=%s;", $GroupID);
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				return $row[0];
			}
			function buildCourseList($GroupID, $UserID, $Flag, $DegreeInd) {
				global $neededClasses;
				global $neededGroups;
				global $noAmbiguity;
				global $classOptions;
				global $groupOptions;
				global $wantedClasses;
				global $coursesTaken;
				global $degreeCourses;
				
				if (hasMetRequirements($GroupID, array_merge($coursesTaken, $neededClasses), $Flag)) {
					return;
				}
				
				$sql = sprintf("select SubGroupDesc from RequirementGroups where SubGroupID=%s;", $GroupID);
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				$groupDesc = $row[0];
				
				$sql = sprintf("select CourseID from RequirementGroups where GroupID=%s and Flag=9999;", $GroupID);
				$result = mysql_query($sql);
				$numCourses = mysql_num_rows($result);
				$courseIds = array();
				while ($row = mysql_fetch_array($result)) {
					if (in_array($row[0], array_merge($neededClasses, $coursesTaken))) {
						if ($Flag != 0) {
							$Flag--;
						}
					} else {
						array_push($courseIds, $row[0]);
					}
				}
				
				$needGroups = 1;
				if ($numCourses > 0) {
					if ($Flag == 0) {
						# add all courses to array
						$neededClasses = array_merge($neededClasses, $courseIds);
						$degreeCourses[$DegreeInd] = array_merge($degreeCourses[$DegreeInd], $courseIds);
						$needGroups = 1;
					} else {
						if ($Flag <= $numCourses) {
							$sql = sprintf("select distinct Department from Courses where CourseID in (%s);", implode(",", $courseIds));
							$result = mysql_query($sql);
							$needGroups = 0;
							
							if (mysql_num_rows($result) > 1) {
								if (preg_match_all("/$GroupID:(\w*)/", $_POST['groupsInput'], $matches) != 0) {
									# add $Flag lowest level courses of selected dep
									$dep = $matches[1][0];
									$sql = sprintf("select CourseID from Courses where Department=\"%s\" and CourseID in (%s) order by Level;", $dep, implode(",", $courseIds));
									$result = mysql_query($sql);
									for ($i = 0; $i < $Flag; $i++) {
										$row = mysql_fetch_array($result);
										array_push($neededClasses, $row[0]);
										array_push($degreeCourses[$DegreeInd], $row[0]);
									}
								} else {
									# ask user which dep
									$noAmbiguity = 0;
									array_push($classOptions, sprintf("There are multiple classes that satisfy requirement group %s (id: %s). To help us match you with your preferred classes, please choose a department from which to take classes.<br />", $groupDesc, $GroupID));
									array_push($classOptions,  sprintf("<select id=\"%s\">", $GroupID));
									while ($row = mysql_fetch_array($result)) {
										array_push($classOptions, sprintf("<option value=\"%s\">%s</option>", $row[0], $row[0]));
									}
									array_push($classOptions, sprintf("</select><br />"));
								}
							} else {
								# add the $Flag lowest level courses to array
								$sql = sprintf("select CourseID from Courses where CourseID in (%s) order by Level;", implode(",", $courseIds));
								$result = mysql_query($sql);
								for ($i = 0; $i < $Flag; $i++) {
									$row = mysql_fetch_array($result);
									array_push($neededClasses, $row[0]);
									array_push($degreeCourses[$DegreeInd], $row[0]);
								}
							}
						} else {
							# add all courses to array and modify $Flag appropriately
							$neededClasses = array_merge($neededClasses, $courseIds);
							$degreeCourses[$DegreeInd] = array_merge($degreeCourses[$DegreeInd], $courseIds);
							$Flag = $Flag - $numCourses;
							$needGroups = 1;
						}
					}
				}
				
				if ($needGroups) {
					$sql = sprintf("select SubGroupID,Flag from RequirementGroups where GroupID=%s and Flag!=9999;", $GroupID);
					$result = mysql_query($sql);
					$numGroups = mysql_num_rows($result);
					
					if ($numGroups > 0) {
						if ($Flag == 0 || $Flag >= $numGroups) {
							# pick all subgroups to satisfy
							while ($row = mysql_fetch_array($result)) {
								buildCourseList($row[0],$UserID,$row[1],$DegreeInd);
							}
						} else {
							# pick $Flag subgroups to satisfy
							if (preg_match_all("/$GroupID:(\d*)/", $_POST['groupsInput'], $matches) != 0) {
								$preferedGroupId = $matches[1][0];
								$sql = sprintf("select Flag from RequirementGroups where GroupID=%s and SubGroupID=%s;", $GroupID, $preferedGroupId);
								$result = mysql_query($sql);
								$row = mysql_fetch_array($result);
								$prefFlag = $row[0];
								buildCourseList($preferedGroupId, $UserID, $prefFlag, $DegreeInd);
							} else {
								$noAmbiguity = 0;					
								array_push($groupOptions, sprintf("There are multiple options that satisfy requirements group %s (id: %s). Please pick %s option(s) among the following.<br />", $groupDesc, $GroupID, $Flag));
								array_push($groupOptions, sprintf("<select id=\"%s\">", $GroupID));
								while ($row = mysql_fetch_array($result)) {
									array_push($groupOptions, sprintf("<option value=\"%s\">%s</option>", $row[0], getGroupInfo($row[0])));
								}
								array_push($groupOptions, sprintf("</select><br />"));
							}
						}
					}
				}
				
			}
			$userid = $_SESSION['login'];
			
			$wantedClasses = array_filter( explode(" ", $_POST['wantedClasses']) );
			$neededClasses = array_merge($neededClasses, $wantedClasses);
			
			$db = dbConnect();
			
			$sql = sprintf("select CourseID from UserCoursesCompleted where UserID=%s;", $userid);
			$result = mysql_query($sql, $db);
			while ($row = mysql_fetch_array($result)) {
				array_push($coursesTaken, $row[0]);
			}
			
			$sql = sprintf("select College1,Major1,College2,Major2,College3,Major3,Minor1,Minor2,Minor3 from Users where UserID=\"%s\"", $userid);
			$result = mysql_query($sql, $db);
			$userinfo = mysql_fetch_array($result, MYSQL_BOTH);
			
			print("<form name=\"genSchedForm\" method=\"post\" action=\"generateCourses.php\" onsubmit=\"setGroupsInputValue()\">");
			for ($i = 0; $i < 3; $i++) {
				if ($userinfo[(2*$i)+1] != 0) {
					$college = $userinfo[2*$i];
					$major = $userinfo[(2*$i)+1];
					
					$sql = sprintf("select RequirementDesc,ParentGroupID from DegreeRequirements where DegreeID=%s;", $college);
					$result = mysql_query($sql);
					$row = mysql_fetch_array($result);
					$collegeDesc = $row[0];
					$collegeParentId = $row[1];
					
					$sql = sprintf("select RequirementDesc,ParentGroupID from DegreeRequirements where DegreeID=%s;", $major);
					$result = mysql_query($sql);
					$row = mysql_fetch_array($result);
					$majorDesc = $row[0];
					$majorParentId = $row[1];
					
					printf("Major #%d: %s in %s<br />", $i+1, $majorDesc, $collegeDesc);
					buildCourseList($collegeParentId, $userid, 0, 2*$i);
					buildCourseList($majorParentId, $userid, 0, (2*$i)+1);
				}
			}
			for ($i = 5; $i < 8; $i++) {
				if ($userinfo[$i] != 0) {
					$minor = $userinfo[$i];
					
					$sql = sprintf("select RequirementDesc,ParentGroupID from DegreeRequirements where DegreeID=%s;", $college);
					$result = mysql_query($sql);
					$row = mysql_fetch_array($result);
					$minorDesc = $row[0];
					$minorParentId = $row[1];
					
					printf("Minor #%d: %s<br />", $i-4, $minorDesc);
					buildCourseList($minorParentId, $userid, 0, $i);
				}
			}
			
			foreach ($groupOptions as $st) {
				echo $st;
			}
			foreach ($classOptions as $st) {
				echo $st;
			}
			
			printf("<input type=\"hidden\" name=\"groupsInput\" id=\"groupsInput\" value=\"%s\" />", $_POST['groupsInput']);
			printf("<input type=\"hidden\" name=\"wantedClasses\" id=\"wantedClasses\" value=\"%s\" />", $_POST['wantedClasses']);
			if (!$noAmbiguity) {
				printf("<input type=\"submit\" name=\"Submit\" value=\"Submit\" />");
			}
			print("</form>");
			if ($noAmbiguity) {
				printf("needed classes:<br />");
				foreach ($neededClasses as $cid) {
					if ($cid != "") {
						printf("%s<br />", getCourseInfo($cid));
					}
				}
			}
			printf("<br />choices = \"%s\"", $_POST['groupsInput']);
			
			if ($noAmbiguity) {
				print("<br />");
				for ($i = 0; $i < 9; $i++) {
					printf("%d: <br />", $i);
					for ($j = 0; $j < count($degreeCourses[$i]); $j++) {
						if ($degreeCourses[$i][$j] != "") {
							printf("%s<br />", getCourseInfo($degreeCourses[$i][$j]));
						}
					}
				}
			}
		?>
	</body>
</html>
