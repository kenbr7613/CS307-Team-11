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
	
	<title>Purdue Planner</title>
	<style type="text/css">
<!--
.STYLE1 {font-family: Geneva, Arial, Helvetica, sans-serif}
body {
	background-image: url(images/backgd.jpg);
}
.STYLE2 {font-family: Geneva, Arial, Helvetica, sans-serif; color: #999900; }
.STYLE3 {
	color: #000000;
	font-weight: bold;
	font-family: "Times New Roman", Times, serif;
}
.STYLE4 {color: #000000}
.STYLE5 {color: #FFFF33}
.STYLE6 {font-size: 12px}
.STYLE7 {
	color: #FF6600;
	font-weight: bold;
	font-size: 12px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312"></head>
<body><div align="center">
	<h1 class="STYLE2 STYLE4">&nbsp;</h1>
	<h1 class="STYLE2 STYLE4"><img src="images/purdue_logo.png" width="215" height="80" /></h1>
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
</body>
</html>

