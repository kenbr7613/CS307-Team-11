<!DOCTYPE html>
<html>
    <head>
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
				$sql = "select Credits from Courses where CourseID in (" . implode(", ", $courseidArray) . ");";
				$result = mysql_query($sql, $db);
				while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
					$totalCredits = $totalCredits + $row[0];
				}
				$creditMax = 120;
				return sprintf("%.0f", ($totalCredits/$creditMax)*100);
			}
		?>
		<title>Purdue Planner</title>
		<style type="text/css">
			body 
			{
				background-image:url(images/backgd.jpg);
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
			<h1 class="STYLE16">Your Purdue Planner Home Page </h1>
			<h2>Graduation Progress:</h2>
			
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
			<br />
			<h2>Schedule Manager:</h2>
			<table width="580" height="134" border="1">
				<tr>
					<td align="center"><form action="suggestSchedule.php"><input class="button" type="submit" value="Suggest a Schedule"></form></td>
					<td align="center"><form action="viewSchedule.php"><input class="button" type="submit" value="View/Edit Your Current Schedule"></form></td>
				</tr>
				<tr>
					<td align="center"><form action="editAccount.php"><input class="button" type="submit" value="Edit Your Account"></form></td>
					<td align="center"><form action="logout.php"><input class="button" type="submit" value="Logout"></form></td>
				</tr>
		</table>
		</div>
	</body>
</html>
