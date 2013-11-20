<!DOCTYPE html>
<html>
    <head>
		<title>Purdue Planner</title>
		<style type="text/css">
			body 
			{
				background-image:url(images/backgd.jpg);
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
		<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
	</head>
	<script>
		function addClass(){
			var deps = document.getElementById("dep");
			var dep = deps.options[deps.selectedIndex].innerHTML;
			
			var lvls = document.getElementById("level");
			var lvl = lvls.options[lvls.selectedIndex].innerHTML;
			
			var table = document.getElementById("listOfClasses");
			var rows = table.rows.length;
			
			var row = table.insertRow(rows);
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			
			//cell1.innerHTML = "<p id=\"" + lvls.options[lvls.selectedIndex].value + "\">" + lvlsdep.concat(lvl) + "</p>";
			//cell1.innerHTML = "<p>" + lvlsdep.concat(lvl) + "</p>";
			cell1.innerHTML = dep.concat(lvl);
			cell1.id = lvls.options[lvls.selectedIndex].value;
			cell2.innerHTML = "<button type=\"button\" onclick=\"dropClass(this)\">Remove Class</button>";
		}
		function dropClass(button) {
			var table = document.getElementById("listOfClasses");
			var td = button.parentNode;
			var tr = td.parentNode;
			var ind = tr.rowIndex;
			table.deleteRow(ind);
		}
		function setWantedValue() {
			var courses = document.getElementById("wantedClasses");
			var table = document.getElementById("listOfClasses");
			var rows = table.rows.length;
			var row;
			for (var i = 0; i < rows; i++) {
				row = table.rows[i];
				courses.value = courses.value + " " + row.cells[0].id;
			}
		}
	</script>
	<?php
		include('session_login_check.php');
		include "DBaccess.php";
		
		$db = dbConnect();
		$sql = "select distinct Department from Courses;";
		$resultD = mysql_query($sql, $db);
		$array = array();
		while ($dep = mysql_fetch_array($resultD, MYSQL_BOTH)) {
			$sql = sprintf("select Level,Title,CourseID from Courses where Department =\"%s\";", $dep[0]);
			$resultL = mysql_query($sql, $db);
			$array[$dep[0]] = array();
			while ($lev = mysql_fetch_array($resultL, MYSQL_BOTH)) {
				$array[$dep[0]][$lev[0]] = array($lev[0],$lev[1],$lev[2]);
			}							
		}
		echo "<script>\n";
		echo "function populateNum() {\n";
		echo "var deps = document.getElementById(\"dep\");\n";
		echo "var lvls = document.getElementById(\"level\");\n";
		echo "var dep = deps.options[deps.selectedIndex].value;\n";
		$keys1 = array_keys($array);
		foreach ($keys1 as $key1) {
			printf("if (dep == \"%s\") {\n", $key1);
			echo "\tlvls.options.length=0;\n";
			$i = 0;
			$keys2 = array_keys($array[$key1]);
			foreach ($keys2 as $key2) {				
				printf("\tlvls.options[%d]=new Option(\"%s - %s\", \"%s\", false, false);\n", $i++, $array[$key1][$key2][0], $array[$key1][$key2][1], $array[$key1][$key2][2]);
			}
			echo "}\n";
		}
		echo "}</script>";
		
	?>
	<body>
		<div align="center" valign="middle">
			<h1>&nbsp;</h1>
			<h1 align="center"><img src="images/purdue_logo.png" width="215" height="80"></h1>
			<h1 class="STYLE16">Generate a Schedule for Next Semester </h1>
			<form name="form1" method="post" action="generateCourses.php" onsubmit="setWantedValue()">
				<h2>Course Preferences:</h2>
				<h4>List the courses you know you want to take next semester:</h4>
				<table border="0">
					<tr>
						<td>
							Department:
							<select id="dep" onchange="populateNum()">
								<option value="null">--</option>
								<?php
									$sql = "select distinct Department from Courses;";
									$result = mysql_query($sql, $db);
									while ($dep = mysql_fetch_array($result, MYSQL_BOTH)) {
										$s = $dep[0];
										echo "<option value=\"$s\">$s</option>";
									}
								?>					
							</select>
						</td>
						<td>
							Level: 
							<select id="level">
								<option value="null">--</option>
							</select>
						</td>
					</tr>
				</table>
				<button type="button" onclick="addClass()">Add Class</button>
				<table id="listOfClasses" border="0">
				</table>
				<h4>If you want to take more courses, but don't know which ones, we can help you decide. List how many you want us to suggest:</h4>
				<table border="0">
					<tr>
						<td>
							Number of courses:
						</td>
						<td>
							<select name="numCourses" id="numCourses">
								<option value="0">0</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
							</select>
						</td>
					</tr>
					<!--
							<tr>
								<td>
									Number of Major Courses:
								</td>
								<td>
									Between: 
									<select name="numMajCoursesMin" id="numMajCoursesMin">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
									</select>
									and: 
									<select name="numMajCoursesMax" id="numMajCoursesMax">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									Number of GenEd Courses:
								</td>
								<td>
									Between: 
									<select name="numGenCoursesMin" id="numGenCoursesMin">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
									</select>
									and: 
									<select name="numGenCoursesMax" id="numGenCoursesMax">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
									</select>
								</td>
							</tr>
					-->
				</table>						
				<br />
				<br />
				<h2>Schedule Preferences:</h2>
				<table border="0">
					<tr>
						<td>
							No class between:
						</td>
						<td>
							<select name="begHour" id="endHour">
								<?php
									for ($i = 0; $i < 24; $i++) {
										printf("<option value=\"%d\">%02d</option>", $i, $i);
									}
								?>
							</select>
							:
							<select name="begMin" id="begMin">
								<?php
									for ($i = 0; $i < 60; $i++) {
										printf("<option value=\"%d\">%02d</option>", $i, $i);
									}
								?>
							</select>
							 and 
							<select name="endHour" id="endHour">
								<?php
									for ($i = 0; $i < 24; $i++) {
										printf("<option value=\"%d\">%02d</option>", $i, $i);
									}
								?>
							</select>
							:
							<select name="endMin" id="endMin">
								<?php
									for ($i = 0; $i < 60; $i++) {
										printf("<option value=\"%d\">%02d</option>", $i, $i);
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							No class before:
						</td>
						<td>
							<select name="earlyHour" id="earlyHour">
								<?php
									for ($i = 0; $i < 24; $i++) {
										printf("<option value=\"%d\">%02d</option>", $i, $i);
									}
								?>
							</select>
							:
							<select name="earlyMin" id="earlyMin">
								<?php
									for ($i = 0; $i < 60; $i++) {
										printf("<option value=\"%d\">%02d</option>", $i, $i);
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							No class after:
						</td>
						<td>
							<select name="lateHour" id="lateHour">
								<?php
									for ($i = 0; $i < 24; $i++) {
										printf("<option value=\"%d\">%02d</option>", $i, $i);
									}
								?>
							</select>
							:
							<select name="lateMin" id="lateMin">
								<?php
									for ($i = 0; $i < 60; $i++) {
										printf("<option value=\"%d\">%02d</option>", $i, $i);
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							Prefer classes with friends:
						</td>
						<td>
							<input type="checkbox" name="preferFriends" id="preferFriends">
						</td>
					</tr>
				</table>
				<input type="submit" name="Submit" value="Submit" />
				<input type="hidden" name="groupsInput" id="groupsInput" value="" />
				<input type="hidden" name="wantedClasses" id="wantedClasses" value="" />
			</form>
			<form action="home.php"><input type="submit" value="Go Back"></form>
		</div>
	</body>
</html>
