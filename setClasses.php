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
			
			var row = table.insertRow(rows);
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			
			cell1.innerHTML = dep.concat(lvl);
			cell2.innerHTML = "<button type=\"button\" onclick=\"dropClass(this)\">Remove Class</button>";
		}
		function dropClass(button) {
			var table = document.getElementById("listOfClasses");
			var td = button.parentNode;
			var tr = td.parentNode;
			var ind = tr.rowIndex;
			table.deleteRow(ind);
		}
	</script>
	<?php
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
	
	<title>Purdue Planner</title>
	<style type="text/css">
<!--
.STYLE1 {font-family: Geneva, Arial, Helvetica, sans-serif}
body {
	background-image: url(../images/backgd.jpg);
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
	<h1 class="STYLE2 STYLE4"><img src="../images/purdue_logo.png" width="215" height="80" /></h1>
	<h1 id ="h1" class="STYLE2 STYLE5">Enter All Courses You Have Received Credit For </h1>
	<p class="STYLE1">&nbsp;</p>
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
	<button tpye="button">Finish</button>
	<br />
	<br />
	<table id="listOfClasses" border="0">
	</table>
	</div>
</body>
</html>

