<!DOCTYPE html>
<html>
    <head>
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
		
		include "DBaccess.php";
		$db = dbConnect();
		
		echo "<script>\n";
		echo "function populateNum() {\n";
		echo "var deps = document.getElementById(\"dep\");\n";
		echo "var lvls = document.getElementById(\"level\");\n";
		echo "var dep = deps.options[deps.selectedIndex].value;\n";
		
		$sql = "select CourseID,Department,Level,Title from Courses order by Department, Level;";
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
<!--
.STYLE1 {font-family: Geneva, Arial, Helvetica, sans-serif}
body {
	background-image: url();
}
.STYLE2 {font-family: Geneva, Arial, Helvetica, sans-serif; color: #999900; }
.STYLE3 {
	color: #000000;
	font-weight: bold;
	font-family: "Times New Roman", Times, serif;
}
.STYLE4 {color: #000000}
.STYLE5 {
	color: #003CFF
}
.STYLE6 {font-size: 12px}
.STYLE7 {
	color: #FF6600;
	font-weight: bold;
	font-size: 12px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
 #navbar ul { 
	margin: 0; 
	padding: 5px; 
	list-style-type: none; 
	text-align: left; 
	background-color: #6DB4F2; 
	} 
 
#navbar ul li {  
	display: inline; 
	} 
 
#navbar ul li a { 
	text-decoration: none; 
	padding: .2em 1em; 
	color: #fff; 
	background-color: #6DB4F2; 
	} 
 
#navbar ul li a:hover { 
	color: #000; 
	background-color: #fff; 
	} 
-->
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312"></head>
<body><div align="center">
	<div id="navbar"> 
  <ul> 
	<li>
	  <h2 style="color: #FFFFFF; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;"><img src="3.jpg" width="204" height="71" alt=""/></h2>
	</li> 
    
  </ul> 
</div> 
  <h1 class="STYLE2 STYLE4">&nbsp;</h1>
	<h1 class="STYLE2 STYLE4">&nbsp;</h1>
	<h1 id ="h1" class="STYLE2 STYLE5">Enter All Courses You Have Received Credit For </h1>
	<p class="STYLE1">&nbsp;</p>
	<form method="post" action="complete.php" onsubmit="setClassValue()">
		<table border="0">
			<tr>
				<td>
					Department:
					<select id="dep" onchange="populateNum()">
						<option value="null">--</option>
						<?php
							$db = dbConnect();
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
		<?php			
			$email=$_POST["email"];
			printf("<input type=\"hidden\" name=\"email\" value=\"%s\" id=\"email\">", $email);
		?>
		<input type="submit" value="Finish" />
	</form>
	<br />
	<br />
	<table id="listOfClasses" border="0">
	</table>
	</div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <div id="footer" style="background-color: #6DB4F2; clear: both; text-align: center; color: #FFFFFF; style=; font-family: Baskerville, 'Palatino Linotype', Palatino, 'Century Schoolbook L', 'Times New Roman', serif;"background-color: #6DB4F2; clear: both; text-align: center; color: #FFFFFF;">
Designed by Team 11 in CS307, Fall 2013, Purdue University, West Lafayette</span></div>
</body>
</html>

