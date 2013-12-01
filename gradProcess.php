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
				return sprintf("%.0f", ($totalCredits/$creditMax)*100);
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

<h3>&nbsp;</h3>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

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
</body>
</html>
