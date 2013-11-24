<?php
	include('session_login_check.php');
?>
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
		function setWantedValue() {
			setSchedValue();
			
			var courses = document.getElementById("wantedClasses");
			var table = document.getElementById("listOfClasses");
			var rows = table.rows.length;
			var row;
			for (var i = 0; i < rows; i++) {
				row = table.rows[i];
				courses.value = courses.value + " " + row.cells[0].id;
			}
		}
		function addRange() {
			var table = document.getElementById("schedPref");
			var rows = table.rows;
			var row = table.insertRow(rows.length);
			
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);
			var cell5 = row.insertCell(4);
			var cell6 = row.insertCell(5);
			cell1.innerHTML = "No class between:";
			
			var select1 = document.createElement("select");
			for (var i = 1; i < 13; i++) {
				var opt = document.createElement("option");
				opt.innerHTML = i;
				if (i == 12) {
					opt.value = 0;
				} else {
					opt.value = i;
				}
				select1.appendChild(opt);
			}
			
			var select2 = document.createElement("select");
			for (var i = 0; i <= 55; i = i+5) {
				var opt = document.createElement("option");
				opt.innerHTML = i;
				opt.value = i;
				select2.appendChild(opt);
			}
			
			var select3 = document.createElement("select");
			var opt = document.createElement("option");
			opt.innerHTML = "AM";
			opt.value = 0;
			select3.appendChild(opt);
			opt = document.createElement("option");
			opt.innerHTML = "PM";
			opt.value = 1;
			select3.appendChild(opt);			
			
			cell2.appendChild(select1);
			cell2.innerHTML = cell2.innerHTML + ":";
			cell2.appendChild(select2);
			cell2.appendChild(select3);
			
			cell3.innerHTML = "and";
			
			var select4 = document.createElement("select");
			for (var i = 1; i < 13; i++) {
				var opt = document.createElement("option");
				opt.innerHTML = i;
				if (i == 12) {
					opt.value = 0;
				} else {
					opt.value = i;
				}
				select4.appendChild(opt);
			}
			
			var select5 = document.createElement("select");
			for (var i = 0; i <= 55; i = i+5) {
				var opt = document.createElement("option");
				opt.innerHTML = i;
				opt.value = i;
				select5.appendChild(opt);
			}
			
			var select6 = document.createElement("select");
			var opt = document.createElement("option");
			opt.innerHTML = "AM";
			opt.value = "0";
			select6.appendChild(opt);
			opt = document.createElement("option");
			opt.innerHTML = "PM";
			opt.value = "1";
			select6.appendChild(opt);
			
			cell4.appendChild(select4);
			cell4.innerHTML = cell4.innerHTML + ":";
			cell4.appendChild(select5);
			cell4.appendChild(select6);
			
			var days = ["Mondays", "Tuesdays", "Wednesdays", "Thursdays", "Fridays", "Every day of the week"];
			var vals = ["M", "T", "W", "R", "F", "MTWRF"];
			var select7 = document.createElement("select");
			for (var i = 0; i < 6; i++) {
				var opt = document.createElement("option");
				opt.innerHTML = days[i];
				opt.value = vals[i];
				select7.appendChild(opt);
			}
			cell5.innerHTML = cell5.innerHTML + "on ";
			cell5.appendChild(select7);
			
			cell6.innerHTML = "<button type=\"button\" onclick=\"dropRange(this)\">Remove Range</button>";
		}
		function dropRange(button) {
			var table = document.getElementById("schedPref");
			var td = button.parentNode;
			var tr = td.parentNode;
			var ind = tr.rowIndex;
			table.deleteRow(ind);
		}
		function setSchedValue() {
			var sched = document.getElementById("sched");			
			var table = document.getElementById("schedPref");
			var rows = table.rows.length;
			var row;
			for (var i = 0; i < rows; i++) {
			
				row = table.rows[i];
				var options = row.cells[1].getElementsByTagName("select");				
				var begHour = options[0].value;
				var begMin = options[1].value;
				var begSuf = options[2].value;
				if (begSuf == "1") {
					begHour = parseInt(begHour) + 12;
				}
			
				var options = row.cells[3].getElementsByTagName("select");				
				var endHour = options[0].value;
				var endMin = options[1].value;
				var endSuf = options[2].value;
				if (endSuf == "1") {
					endHour = parseInt(endHour) + 12;
				}
			
				var options = row.cells[4].getElementsByTagName("select");
				var day = options[0].value;
				
				if (begMin < 10) {
					begMin = "0" + begMin;
				}
				if (begHour < 10) {
					begHour = "0" + begHour;
				}
				if (endMin < 10) {
					endMin = "0" + endMin;
				}
				if (endHour < 10) {
					endHour = "0" + endHour;
				}
				
				sched.value = sched.value + " " + begHour + ":" + begMin + "-" + endHour + ":" + endMin + ":" + day + " ";
			}
		}
	</script>
	<?php
		include "DBaccess.php";
		
		$db = dbConnect();
		echo "<script>\n";
		echo "function populateNum() {\n";
		echo "var deps = document.getElementById(\"dep\");\n";
		echo "var lvls = document.getElementById(\"level\");\n";
		echo "var dep = deps.options[deps.selectedIndex].value;\n";
		
		$sql = "select distinct Department from Courses;";
		$resultD = mysql_query($sql, $db);
		while ($dep = mysql_fetch_array($resultD)) {
			printf("if (dep == \"%s\") {\n", $dep[0]);
			printf("\tlvls.options.length=0;\n");
			$i = 0;
			
			$sql = sprintf("select Level,Title,CourseID from Courses where Department=\"%s\" order by Level;", $dep[0]);
			$resultL = mysql_query($sql, $db);
			while ($lev = mysql_fetch_array($resultL)) {
				$level = $lev[0];
				$title = $lev[1];
				$cid = $lev[2];
				printf("\tlvls.options[%d]=new Option(\"%s - %s\", \"%s\", false, false);\n", $i++, $level, $title, $cid);
			}		
			printf("}\n");
		}
		printf("}</script>");
		
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
				</table>						
				<br />
				<br />
				<h2>Schedule Preferences:</h2>
				<button type="button" onclick="addRange()">Add Range</button>
				<table name="schedPref" id="schedPref" border="0">
				</table>
				<input type="submit" name="Submit" value="Submit" />
				<input type="hidden" name="groupsInput" id="groupsInput" value="" />
				<input type="hidden" name="wantedClasses" id="wantedClasses" value="" />
				<input type="hidden" name="sched" id="sched" value="" />
			</form>
			<form action="home.php"><input type="submit" value="Go Back"></form>
		</div>
	</body>
</html>
