<!doctype html>
<?php
			include('session_login_check.php');
			include "DBaccess.php";
			function getPercentComplete() {
				$userid = $_SESSION['login'];
				$totalCredits = 0;
				$db = dbConnect();
				$sql = sprintf("select CourseID from UserCoursesCompleted where UserID=\"%s\"", $userid);
				$result = mysql_query($sql, $db);
				$courseidArray = array();
				while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
					array_push($courseidArray, $row[0]);					
				}
				if (count($courseidArray) > 0) {
					$sql = "select Credits from Courses where CourseID in (" . implode(", ", $courseidArray) . ");";
					$result = mysql_query($sql, $db);
					while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
						$totalCredits = $totalCredits + $row[0];
					}
				}
				$creditMax = 120;
				//return sprintf("%.0f", ($totalCredits/$creditMax)*100);
				return count($courseidArray)>40 ? "99.9" : sprintf("%.0f", (count($courseidArray)/40)*100);
			}
?>
<html>
<head>
<meta charset="utf-8">

<style type="text/css">
body {
	background-color: #FFFFFF;
}
</style>
</head>

<body>
<center>
<h3><span style="font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; color:#A1A1A1;">
  <p>&nbsp;</p>
<?PHP
date_default_timezone_set('America/New_York'); 
echo date('l dS \of F Y h:i:s A');
?>
</span>
</h3>

<h3>&nbsp;</h3>
<h2><span style="font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #585757;">Your Graduation Progress:</span></h2>
    <table>
                          <tr>
                                <td><?php 
                                                        $percentComplete = getPercentComplete();
                                                        printf("%s%%: ", $percentComplete); 
                                    ?></td>
                                <td width=200 style="border: 2px solid black;padding:none">
                                  <hr style="color:#008000;background-color:#008000;height:15px; border:none;
                                                         margin:0;" align="left" width=<?php
                                                                        echo "$percentComplete";
                                                                 ?>% />
                                </td>
                          </tr>
    </table>
</center>
		<?php	
			mysql_connect('lore.cs.purdue.edu:11394', 'root', 'cs307team11');
			mysql_select_db("purduePlannerDB"); 

			//setup Paramaters
			$UserID = $_SESSION['login'];

			//pull colleges, majors and minors
			$sql = "SELECT * FROM Users WHERE UserID = " .  $UserID;
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			$col1 = $row['College1'];
			$col2 = $row['College2'];
			$col3 = $row['College3'];
			$maj1 = $row['Major1'];
			$maj2 = $row['Major2'];
			$maj3 = $row['Major3'];
			$min1 = $row['Minor1'];
			$min2 = $row['Minor2'];
			$min3 = $row['Minor3'];

			//select college
			echo "<p>" . pullName($col1) . " Requirements</p><ul>";
			printList($col1, $UserID);
			echo "</ul>";

			//select Major
			echo "<p>" . pullName($maj1) . " Requirements</p><ul>";
			printList($maj1, $UserID);
			echo "</ul>";

			//select other colleges and majors
			if($col2 != 0 && $maj2 != 0)
			{
				echo "<p>" . pullName($col2) . " Requirements</p><ul>";
				printList($col2, $UserID);
				echo "</ul>";
				echo "<p>" . pullName($maj2) . " Requirements</p><ul>";
				printList($maj2, $UserID);
				echo "</ul>";
			}
			if($col3 != 0 && $maj3 != 0)
			{
				echo "<p>" . pullName($col3) . " Requirements</p><ul>";
				printList($col3, $UserID);
				echo "</ul>";
				echo "<p>" . pullName($maj3) . " Requirements</p><ul>";
				printList($maj3, $UserID);
				echo "</ul>";
			}

			//select minors
			if($min1 != 0)
			{
				echo "<p>" . pullName($min1) . " Requirements</p><ul>";
				printList($min1, $UserID);
				echo "</ul>";
			}
			if($min2 != 0)
			{
				echo "<p>" . pullName($min2) . " Requirements</p><ul>";
				printList($min2, $UserID);
				echo "</ul>";
			}
			if($min3 != 0)
			{
				
				echo "<p>" . pullName($min3) . " Requirements</p><ul>";
				printList($min3, $UserID);
				echo "</ul>";
			}
		?>
</body>
</html>
<?php


	function pullName($id)
	{
		$sql = "SELECT RequirementDesc FROM DegreeRequirements WHERE DegreeID = " . $id;
		$desc = mysql_fetch_array(mysql_query($sql));
		return $desc['RequirementDesc'];
	}

	function printList($DegreeID, $UserID)
	{
		$sql = "SELECT ParentGroupID FROM DegreeRequirements WHERE DegreeID = " . $DegreeID;
		$result = mysql_query($sql);
		$row = mysql_fetch_row($result);
		$parentGroupID = $row[0];

		$sql = "SELECT * FROM RequirementGroups WHERE GroupID = " . $parentGroupID;
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result))
		{
			if($row['Flag'] != 9999)
			{
				echo "<li>" . $row['SubGroupDesc'] . " - ";
				if(isCompleted($row['SubGroupID'], $UserID, $row['Flag']))
					echo "<font color='Green'>Completed</font>";
				else
					echo "<font color='Red'>Not Completed</font>";
				$url = "viewReqs.php?GroupID=" . $row['SubGroupID'] . "&Flag=" . $row['Flag'] . "&Desc=" . row['SubGroupDesc'];
				echo " <a href='". $url . "'>View Requirements</a> </li>";
			} else {
				$classSQL = "SELECT * FROM Courses WHERE CourseID = " . $row['CourseID'];
				$classResult = mysql_query($classSQL);
				$classRow = mysql_fetch_array($classResult);
				echo "<li>" . $classRow['Department'] . $classRow['Level'] . " " . $classRow['Title'];
				if(userCompleted($UserID, $row['CourseID']))
					echo " - <font color='Green'>Completed</font>";
				else
					echo " - <font color='Red'>Not Completed</font>";
				echo "</li>";
			}
		}
	}


	function printSubGroupTable($groupID,$desc, $numReq)
	{
		echo "<p><table border=1 width=100% cellpadding=5 cellspacing=0 bordercolor=#999999>\r\n";
		if(isCompleted($groupID, 1, $numReq))
		{
			echo "<tr><td>" . $desc . " - ";
			echo "<font color='Green'>Completed</font>";
			echo "</td></tr>\r\n";
			echo "</table></p>";
			return;
		}
		else
		{
			echo "<tr><td><h3>" . $desc . " - ";
			echo "<font color='Red'>Not Completed</font></h3>";
			echo "</td></tr>\r\n";
		}
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

				printSubGroupTable($row['SubGroupID'], $row['SubGroupDesc'], $row['Flag']);
				echo "</td></tr>";
			} else {
				//printCourseRow($row['CourseID']);
			}
		}
		echo "</table></p>\r\n";
	}

	function isCompleted($GroupID, $UserID, $Flag)
	{
		$sql = "SELECT * FROM RequirementGroups WHERE GroupID = " . $GroupID;
		$result = mysql_query($sql);
		if($Flag == 0)
			$countNeeded = mysql_num_rows($result);
		else
			$countNeeded = $Flag;
		$count = 0;
		while($row = mysql_fetch_array($result))
		{
			if ($row['Flag'] == 9999 && userCompleted($UserID, $row['CourseID']))
				$count++;
			else if($row['Flag'] != 9999 && isCompleted($row['SubGroupID'], $UserID, $row['Flag']))
				$count++;
		}

		if($count >= $countNeeded)
			return true;
		else
			return false;
	}

	function userCompleted($UserID, $CourseID)
	{
		$sql = "SELECT * FROM UserCoursesCompleted WHERE UserID = " . $UserID . " AND CourseID = " . $CourseID;
		$result = mysql_query($sql);
		if(mysql_num_rows($result) > 0)
			return true;
		else
			return false;
	}
		

	function printCourseRow($CourseID) {
		$sql = "SELECT * FROM Courses WHERE CourseID = " . $CourseID;
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		echo "<tr><td>" . $row['Department'] . $row['Level'] . " " . $row['Title'] . "</td></tr>\r\n";
	}
?>
