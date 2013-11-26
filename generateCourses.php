<html>
	<head>
		<title>Purdue Planner</title>
		<style type="text/css">
			body 
			{
	background-image: url();
			}
			<!--
			Backgd {
				background-image: url(images/backgd.jpg);
			}
			.STYLE7 {
				font-family: Arial, Helvetica, sans-serif;
				color: #CC9900;
				font-size: 12px;
			}
			.STYLE11 {
				color: #000000;
				font-family: Georgia, "Times New Roman", Times, serif;
				font-size: 10px;
			}
			.STYLE16 {color: #FFFF00; font-style: italic; font-family: Georgia, "Times New Roman", Times, serif;}
			.STYLE17 {
				font-size: 12px;
				font-family: "Times New Roman", Times, serif;
			}
			.STYLE18 {
				font-size: 9px;
				font-family: Geneva, Arial, Helvetica, sans-serif;
				font-weight: bold;
				color: #666666;
			}

			.button {
			   border-top: 1px solid #96d1f8;
			   background: #65a9d7;
			   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
			   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
			   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
			   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
			   background: -o-linear-gradient(top, #3e779d, #65a9d7);
			   padding: 3.5px 7px;
			   -webkit-border-radius: 5px;
			   -moz-border-radius: 5px;
			   border-radius: 5px;
			   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
			   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
			   box-shadow: rgba(0,0,0,1) 0 1px 0;
			   text-shadow: rgba(0,0,0,.4) 0 1px 0;
			   color: white;
			   font-size: 10px;
			   font-family: Georgia, serif;
			   text-decoration: none;
			   vertical-align: middle;
			   }
			.button:hover {
			   border-top-color: #28597a;
			   background: #28597a;
			   color: #ccc;
			   }
			.button:active {
			   border-top-color: #1b435e;
			   background: #1b435e;
			   }
			   
			.STYLE20 {color: #000000; font-weight: bold; font-family: Georgia, "Times New Roman", Times, serif; font-size: 10px; }
			.table {
				border: 1px solid #00;
				border-collapse: collapse;
				border-style:outset;
			}
			.STYLE21 {
				color: #000000;
				font-family: Georgia, "Times New Roman", Times, serif;
				font-size: 12px;
				font-weight: bold;
			}

			-->
    
		</style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
		function setPicked() {
			var input = document.getElementById("picked");
			var boxes = document.getElementsByName("cbox");
			
			for (var i = 0; i < boxes.length; i++) {
				if (boxes[i].checked == true) {
					input.value = input.value + " " + boxes[i].id;
				}			
			}
		}
	</script>
	<body>
		<div align="center" valign="middle">
			<h1>&nbsp;</h1>
			<h1 class="STYLE16" style="color: #003CFF">Generating Schedule </h1>
			<?php
				include('session_login_check.php');
				include "DBaccess.php";
				
				if ($_POST['numCourses'] == 0) {
					$wantedClasses = array_filter( explode(" ", $_POST['wantedClasses']) );
					$sets = array();
					array_push($sets, $wantedClasses);
					$_SESSION['setsOfCourses'] = $sets;
					header("location:temp_generateSchedules.php");
				}
				
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
				$classesByGroup = array();
				
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
					global $classesByGroup;
					
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
							$numCourses--;
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
							if (!array_key_exists("$GroupID", $classesByGroup)) {
								$classesByGroup["$GroupID"] = array();
							}
							$classesByGroup["$GroupID"] = array_merge($classesByGroup["$GroupID"], $courseIds);
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
											if (!array_key_exists("$GroupID", $classesByGroup)) {
												$classesByGroup["$GroupID"] = array();
											}
											array_push($classesByGroup["$GroupID"], $row[0]);
										}
									} else {
										# ask user which dep
										$noAmbiguity = 0;
										array_push($classOptions, sprintf("There are multiple classes that satisfy requirement group %s. To help us match you with your preferred classes, please choose a department from which to take classes.<br />", $groupDesc));
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
										if (!array_key_exists("$GroupID", $classesByGroup)) {
											$classesByGroup["$GroupID"] = array();
										}
										array_push($classesByGroup["$GroupID"], $row[0]);
									}
								}
							} else {
								# add all courses to array and modify $Flag appropriately
								$neededClasses = array_merge($neededClasses, $courseIds);
								$degreeCourses[$DegreeInd] = array_merge($degreeCourses[$DegreeInd], $courseIds);
								if (!array_key_exists("$GroupID", $classesByGroup)) {
									$classesByGroup["$GroupID"] = array();
								}
								$classesByGroup["$GroupID"] = array_merge($classesByGroup["$GroupID"], $courseIds);
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
									array_push($groupOptions, sprintf("There are multiple options that satisfy requirements group %s. Please pick %s option(s) among the following.<br />", $groupDesc, $Flag));
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
						
						#printf("Major #%d: %s in %s<br />", $i+1, $majorDesc, $collegeDesc);
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
						
						#printf("Minor #%d: %s<br />", $i-4, $minorDesc);
						buildCourseList($minorParentId, $userid, 0, $i);
					}
				}
				if (count($groupOptions) > 0) {
					printf("<h2>Requirement Preferences:</h2>");
				}
				foreach ($groupOptions as $st) {
					echo $st;
				}
				if (count($classOptions) > 0) {
					printf("<h2>Department Preferences:</h2>");
				}
				foreach ($classOptions as $st) {
					echo $st;
				}
				
				printf("<input type=\"hidden\" name=\"groupsInput\" id=\"groupsInput\" value=\"%s\" />", $_POST['groupsInput']);
				printf("<input type=\"hidden\" name=\"wantedClasses\" id=\"wantedClasses\" value=\"%s\" />", $_POST['wantedClasses']);
				printf("<input type=\"hidden\" id=\"numCourses\" name=\"numCourses\" value=\"%s\" />", $_POST['numCourses']);
				if (!$noAmbiguity) {
					printf("<br />");
					printf("<input type=\"submit\" name=\"Submit\" value=\"Submit\" />");
				}
				print("</form>");
				if ($noAmbiguity) {
					printf("<h2>Course Preferences:</h2>");
					
					printf("<h4>We've generated a list of courses you can take for next semester.<br />");
					printf("You can either pick which ones you'd like to take, or leave it up to us. We'll try to match you with the best ones.</h4>");
					printf("<form name=\"lastPrefForm\" method=\"post\" action=\"passOnLists.php\" onsubmit=\"setPicked()\">");
					printf("<table name=\"checkboxes\" id=\"checkboxes\" border=\"1\">");
					foreach ($neededClasses as $cid) {
						if ($cid != "" && !in_array($cid, $wantedClasses)) {
							printf("<tr>");
							printf("<td>");
							printf("<input type=\"checkbox\" name=\"cbox\" id=\"$cid\" value=\"$cid\">");
							printf("</td>");
							printf("<td>");
							printf("%s", getCourseInfo($cid));
							printf("</td>");
							printf("</tr>");
						}
					}
					print("</table>");
					printf("<input type=\"hidden\" id=\"picked\" name=\"picked\" value=\"\" />");
					printf("<input type=\"hidden\" name=\"wantedClasses\" id=\"wantedClasses\" value=\"%s\" />", $_POST['wantedClasses']);
					printf("<input type=\"hidden\" id=\"numCourses\" name=\"numCourses\" value=\"%s\" />", $_POST['numCourses']);
					printf("<input type=\"submit\" name=\"Submit\" value=\"Generate Schedule\" />");
					printf("</form>");
					
					$_SESSION['list'] = $classesByGroup;
					
				}
			?>
		</div>
	</body>
</html>
