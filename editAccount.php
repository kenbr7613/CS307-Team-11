<!DOCTYPE html>
<html>
    <head>
		<script>
			function getFriends() {
				var friends = document.getElementById("friends");	
				FB.api('/me/friends', function(response) {
					var friendsSale = response.data;
					var len = friendsSale.length;
					for (var i=0; i<len; i++) {				
						friends.value = friends.value + "_" + friendsSale[i].name;					
					}
				});
			}
		function addClass(){
			var deps = document.getElementById("dep");
			var dep = deps.options[deps.selectedIndex].innerHTML;
			
			var lvls = document.getElementById("level");
			var lvl = lvls.options[lvls.selectedIndex].innerHTML;
			
			var table = document.getElementById("listOfClasses");
			var rows = table.rows.length;
			
			// make sure the class hasn't already been added
			var alreadyThere = 0;
			for (var i = 0; i < rows; i++) {
				var id = table.rows[i].cells[0].id;
				if (id == lvls.options[lvls.selectedIndex].value) {
					alreadyThere = 1;
				}
			}
			
			if (alreadyThere == 0) {
				var row = table.insertRow(rows);
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				
				cell1.innerHTML = dep.concat(lvl);
				cell1.id = lvls.options[lvls.selectedIndex].value;
				cell2.innerHTML = "<button type=\"button\" onclick=\"dropClass(this)\">Remove Class</button>";
			}
		}
		function dropClass(button) {
			var table = document.getElementById("listOfClasses");
			var td = button.parentNode;
			var tr = td.parentNode;
			var ind = tr.rowIndex;
			table.deleteRow(ind);
		}
		function setClassValue() {
			var courses = document.getElementById("courses");
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
			
			$colleges = array();
			$majors = array();
			$minors = array();
			$courses = array();
			$userId = $_SESSION['login'];
			
			$db = dbConnect();
			$sql = sprintf("select DegreeID,RequirementDesc from DegreeRequirements where Type=1 order by RequirementDesc");
			$result = mysql_query($sql, $db);
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
				array_push($colleges, array($row[0], $row[1]));
			}
			
			$sql = sprintf("select DegreeID,RequirementDesc from DegreeRequirements where Type=2 order by RequirementDesc");
			$result = mysql_query($sql, $db);
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
				array_push($majors, array($row[0], $row[1]));
			}
			
			$sql = sprintf("select DegreeID,RequirementDesc from DegreeRequirements where Type=3 order by RequirementDesc");
			$result = mysql_query($sql, $db);
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
				array_push($minors, array($row[0], $row[1]));
			}
			
			//$sql = sprintf("select CourseID from UserCoursesCompleted where UserID=%d", $userId);
			$sql = sprintf("SELECT a.CourseID, a.Department, a.Level, a.Title FROM Courses a, UserCoursesCompleted b WHERE a.CourseID = b.CourseID AND UserID=%d", $userId);
			$result = mysql_query($sql, $db);
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
				//$sql = sprintf("select Department,Level,Title from Courses where CourseID=%d", $row[0]);
				//$resultCourse = mysql_query($sql, $db);
				//$rowCourse = mysql_fetch_array($resultCourse);
				array_push($courses, array($row[0], $row[1], $row[2], $row[3]));
			}
			
			$userInfo;
			$sql = sprintf("select Username,College1,College2,College3,Major1,Major2,Major3,Minor1,Minor2,Minor3,FirstName,LastName from Users where UserID=%d", $userId);
			$result = mysql_query($sql, $db);
			while ($row = mysql_fetch_array($result)) {
				$userInfo =$row;
			}
			
			echo "<script>\n";
			echo "function populateNum() {\n";
			echo "var deps = document.getElementById(\"dep\");\n";
			echo "var lvls = document.getElementById(\"level\");\n";
			echo "var dep = deps.options[deps.selectedIndex].value;\n";
			
			$sql = "select CourseID,Department,Level,Title from Courses order by Department and Level;";
			$result = mysql_query($sql, $db);
			$prevDep = "";
			$i = 0;
			while ($row = mysql_fetch_array($result)) {
				$cid = $row[0];
				$dep = $row[1];
				$level = $row[2];
				$title = $row[3];
				
				if ($prevDep != $dep) {
					if ($prevDep != "") {
						printf("}");
					}
					printf("if (dep == \"%s\") {\n", $dep);
					printf("\tlvls.options.length=0;\n");
					$i = 0;
				}
				
				printf("\tlvls.options[%d]=new Option(\"%s - %s\", \"%s\", false, false);\n", $i++, $level, $title, $cid);
				$prevDep = $dep;
			}
			printf("}\n");
			printf("}</script>");
		?>
		<title>Purdue Planner</title>
		<style type="text/css">
			body 
			{
	background-image: url();
	background-repeat: no-repeat;
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
			.STYLE16 {
	color: #003CFF;
	font-style: italic;
	font-family: Georgia, "Times New Roman", Times, serif;
}
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
		<div id="fb-root"></div>
		<script>
		  window.fbAsyncInit = function() {
			FB.init({
			  appId      : '255720611242546', // App ID
			  channelURL : 'lore.cs.purdue.edu:11392/channel.html', // Channel File
			  status     : true, // check login status
			  cookie     : true, // enable cookies to allow the server to access the session
			  oauth      : true, // enable OAuth 2.0
			  xfbml      : true  // parse XFBML
			});

			// Additional initialization code here
		  };

		  // Load the SDK Asynchronously
		  (function(d){
			 var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
			 js = d.createElement('script'); js.id = id; js.async = true;
			 js.src = "//connect.facebook.net/en_US/all.js";
			 d.getElementsByTagName('head')[0].appendChild(js);
		   }(document));
		</script>
		<div align="center" valign="middle">
			<h1>&nbsp;</h1>
			<h1 class="STYLE16">Edit Your Account </h1>
			<form name="form1" method="post" action="settingsChanged.php" onsubmit="setClassValue()">
			<table width="1000" height="344" border="2">
				<tr>
					<td><div align="center" class="STYLE3">Email</div></td>
					<td><label>
					<div align="center">
					<input name="email" type="text" id="email" 
						<?php
							printf("value =\"%s\" ", $userInfo[0]);
						?>
					/>
					</div>
					</label></td>
				</tr>
				<tr>
					<td height="28"><div align="center"><span class="STYLE3">First Name </span></div></td>
					<td><div align="center">
					<label>
					<input name="fname" type="text" id="fname" 
						<?php
							printf("value =\"%s\" ", $userInfo[10]);
						?>
					/>
					</label>
					</div></td>
				</tr>
				<tr>
					<td height="30"><div align="center"><span class="STYLE3">Last Name </span></div></td>
					<td><div align="center">
					<label>
					<input name="lname" type="text" id="lname" 
						<?php
							printf("value =\"%s\" ", $userInfo[11]);
						?>
					/>
					</label>
					</div></td>
				</tr>
				<tr>
					<td><div align="center" class="STYLE3">Purdue Planner Password <br />(leave blank if you want your password unchanged) </div></td>
					<td><label>
					<div align="center">
					<input name="passwd" type="password" id="passwd" />
					</div>
					</label></td>
			  </tr>
				<tr>
					<td><div align="center" class="STYLE3">Confirm Password </div></td>
					<td><label>
					<div align="center">
					<input name="passwd2" type="password" id="passwd2" />
					</div>
					</label></td>
				</tr>
				<tr>
					<td height="38"><div align="center" class="STYLE3">Major(s) * </div></td>
					<td>
						<div align="center">
							<table border="0">
								<tr>
									College:
									<select name="college1">
										<option>N/A</option>
										<?php
											for ($i = 0; $i < sizeof($colleges); $i++) {
												printf("<option value=\"%s\" ", $colleges[$i][0]);
												if ($userInfo[1] == $colleges[$i][0]) {
													printf("selected");
												}
												printf(">%s</option>", $colleges[$i][1]);
											}
										?>
									</select>
									Major:
									<select name="major1">
										<option>N/A</option>
										<?php
											for ($i = 0; $i < sizeof($majors); $i++) {
												printf("<option value=\"%s\" ", $majors[$i][0]);
												if ($userInfo[4] == $majors[$i][0]) {
													printf("selected");
												}
												printf(">%s</option>", $majors[$i][1]);
											}
										?>
									</select>
								</tr>
							</table>
							<table border="0">
								<tr>
									College:
									<select name="college2">
										<option>N/A</option>
										<?php
											for ($i = 0; $i < sizeof($colleges); $i++) {
												printf("<option value=\"%s\" ", $colleges[$i][0]);
												if ($userInfo[2] == $colleges[$i][0]) {
													printf("selected");
												}
												printf(">%s</option>", $colleges[$i][1]);
											}
										?>
									</select>
									Major:
									<select name="major2">
										<option>N/A</option>
										<?php
											for ($i = 0; $i < sizeof($majors); $i++) {
												printf("<option value=\"%s\" ", $majors[$i][0]);
												if ($userInfo[5] == $majors[$i][0]) {
													printf("selected");
												}
												printf(">%s</option>", $majors[$i][1]);
											}
										?>
									</select>
								</tr>
							</table>
							<table border="0">
								<tr>
									College:
									<select name="college3">
										<option>N/A</option>
										<?php
											for ($i = 0; $i < sizeof($colleges); $i++) {
												printf("<option value=\"%s\" ", $colleges[$i][0]);
												if ($userInfo[3] == $colleges[$i][0]) {
													printf("selected");
												}
												printf(">%s</option>", $colleges[$i][1]);
											}
										?>
									</select>
									Major:
									<select name="major3">
										<option>N/A</option>
										<?php
											for ($i = 0; $i < sizeof($majors); $i++) {
												printf("<option value=\"%s\" ", $majors[$i][0]);
												if ($userInfo[6] == $majors[$i][0]) {
													printf("selected");
												}
												printf(">%s</option>", $majors[$i][1]);
											}
										?>
									</select>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td height="38"><div align="center" class="STYLE3">Minor(s) * </div></td>
					<td><div align="center">
					<table border="0">
						<tr>
							Minor:
							<select name="minor1">
								<option>N/A</option>
								<?php
									for ($i = 0; $i < sizeof($minors); $i++) {
										printf("<option value=\"%s\" ", $minors[$i][0]);
										if ($userInfo[7] == $minors[$i][0]) {
											printf("selected");
										}
										printf(">%s</option>", $minors[$i][1]);
									}
								?>
							</select>
						</tr>
					</table>
					<table border="0">
						<tr>
							Minor:
							<select name="minor2">
								<option>N/A</option>
								<?php
									for ($i = 0; $i < sizeof($minors); $i++) {
										printf("<option value=\"%s\" ", $minors[$i][0]);
										if ($userInfo[8] == $minors[$i][0]) {
											printf("selected");
										}
										printf(">%s</option>", $minors[$i][1]);
									}
								?>
							</select>
						</tr>
					</table>
					<table border="0">
						<tr>
							Minor:
							<select name="minor3">
								<option>N/A</option>
								<?php
									for ($i = 0; $i < sizeof($minors); $i++) {
										printf("<option value=\"%s\" ", $minors[$i][0]);
										if ($userInfo[9] == $minors[$i][0]) {
											printf("selected");
										}
										printf(">%s</option>", $minors[$i][1]);
									}
								?>
							</select>
						</tr>
					</table>
					</div></td>
				</tr>
				<tr>
					<td height="51" colspan="2"><div align="center">
					<label>
					<input type="submit" name="Submit" value="Submit" />
					</label>
					<label>
					<input type="reset" name="Submit2" value="Reset" />
					</label>
		  </form>
					<form action="home.php"><input type="submit" value="Go Back"></form>
					</div>
					<label></label>
					<div align="center">
					<label></label>
					</div>          
					<div align="right"></div><label></label></td>
				</tr>
			</table>
			<br />
			<div align="center" class="fb-login-button" data-show-faces="false" data-width="200" data-max-rows="1" onlogin="getFriends();">Sync account with Facebook friends</div>
			<br />
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
			<input type="hidden" name="courses" value="" id="courses">
			<input type="hidden" name="friends" value="" id="friends">
			<input type="submit" value="Submit" />
		</form>
		<br />
		<br />
		<table id="listOfClasses" border="0">
			<tbody>
				<?php
					for ($i = 0; $i < sizeof($courses); $i++) {
						printf("<tr><td id=\"%d\">%s%s - %s</td><td><button type=\"button\" onclick=\"dropClass(this)\">Remove Class</button</td></tr>", $courses[$i][0], $courses[$i][1], $courses[$i][2], $courses[$i][3]);
					}
				?>
			</tbody>
		</table>
		</div>
	</body>
</html>
