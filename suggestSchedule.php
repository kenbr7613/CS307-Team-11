<!DOCTYPE html>
<html>
    <head>
		<?php
			include('session_login_check.php');
			include "DBaccess.php";
		?>
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
	<body>
		<div align="center" valign="middle">
			<h1>&nbsp;</h1>
			<h1 align="center"><img src="images/purdue_logo.png" width="215" height="80"></h1>
			<h1 class="STYLE16">Generate a Schedule for Next Semester </h1>
			<h2>Course Preferences:</h2>
			<form name="form1" method="post" action="generateSchedule.php">
				<table border="0">
					<tr>
						<td>
							Courses you want to take:
						</td>
						<td>
							<input name="courses" id="courses" type="text" />
						</td>
					</tr>
					<tr>
						<td>
							Number of courses:
						</td>
						<td>
							<select name="numCourses" id="numCourses">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
							</select>
						</td>
					</tr>
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
								<option value="7">7</option>
								<option value="8">8</option>
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
								<option value="7">7</option>
								<option value="8">8</option>
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
								<option value="7">7</option>
								<option value="8">8</option>
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
								<option value="7">7</option>
								<option value="8">8</option>
							</select>
						</td>
					</tr>
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
			</form>
			<form action="home.php"><input type="submit" value="Go Back"></form>
		</div>
	</body>
</html>
