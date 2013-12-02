<html>
<head>
<title>Requirements</title>
</head>
<body>
<center>
<h2>Requirements</h2>
<?php
	if($_GET['Flag'] == 0)
		$req = "All";
	else
		$req = "Pick " . $_GET['Flag'];
	printSubGroupTable($_GET['GroupID'], $_GET['Desc'], $req);
?>
</center>
</body>
</html>
<?php


	function printSubGroupTable($groupID,$desc, $numReq)
	{
		mysql_connect('lore.cs.purdue.edu:11394', 'root', 'cs307team11');
		mysql_select_db("purduePlannerDB"); 

		echo "<p><table border=3 cellpadding=5 cellspacing=0 bordercolor=#000000>\r\n";
		echo "<tr><td><b>" . $desc . " - " . $numReq . "</b></td></tr>\r\n";
		$sql = "SELECT * FROM RequirementGroups WHERE GroupID = " . $groupID;
		$result = mysql_query($sql);
		while ($row = mysql_fetch_array($result)) {
			if($row['Flag'] != 9999)
			{
				echo "<tr><td>";
				if($row['Flag'] == 0)
					$req = "All";
				else
					$req = "Pick " . $row['Flag'];

				printSubGroupTable($row['SubGroupID'], $row['SubGroupDesc'], $req);
				echo "</td></tr>";
			} else {
				printCourseRow($row['CourseID']);
			}
		}
		echo "</table></p>\r\n";
	}

	function printCourseRow($CourseID) {
		$sql = "SELECT * FROM Courses WHERE CourseID = " . $CourseID;
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		echo "<tr><td>" . $row['Department'] . $row['Level'] . " " . $row['Title'] . "</td></tr>\r\n";
	}
?>
