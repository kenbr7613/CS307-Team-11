<html>
	<?php
		include "DBaccess.php";
		
		$colleges = array();
		$majors = array();
		$minors = array();
		
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
	?>
		
	<body>
		<style type="text/css">
			<!--
			.STYLE1 {font-family: Geneva, Arial, Helvetica, sans-serif}
			body {
			
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
		<title>Sign Up</title><div align="center">
		<div id="navbar"> 
  <ul> 
	<li>
	  <h2 style="color: #FFFFFF; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;">Purdue Planner</h2>
	</li> 
    
  </ul> 
</div> 
		<h1 class="STYLE2 STYLE4">&nbsp;</h1>
		<h1 class="STYLE2 STYLE5" style="color: #2321FF">New User for Purdue Planner </h1>
		<p class="STYLE1">&nbsp;</p>
		<form id="form1" name="form1" method="post" action="register.php">
			<table width="1000" height="344" border="2">
				<tr>
					<td><div align="center" class="STYLE3">Email</div></td>
					<td><label>
					<div align="center">
					<input name="email" type="text" id="email" />
					</div>
					</label></td>
				</tr>
				<tr>
					<td height="28"><div align="center"><span class="STYLE3">First Name </span></div></td>
					<td><div align="center">
					<label>
					<input name="fname" type="text" id="fname" />
					</label>
					</div></td>
				</tr>
				<tr>
					<td height="30"><div align="center"><span class="STYLE3">Last Name </span></div></td>
					<td><div align="center">
					<label>
					<input name="lname" type="text" id="lname" />
					</label>
					</div></td>
				</tr>
				<tr>
					<td><div align="center" class="STYLE3">Purdue Planner Password (at least 6 characters) </div></td>
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
												printf("<option value=\"%s\">%s</option>", $colleges[$i][0], $colleges[$i][1]);
											}
										?>
									</select>
									Major:
									<select name="major1">
										<option>N/A</option>
										<?php
											for ($i = 0; $i < sizeof($majors); $i++) {
												printf("<option value=\"%s\">%s</option>", $majors[$i][0], $majors[$i][1]);
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
												printf("<option value=\"%s\">%s</option>", $colleges[$i][0], $colleges[$i][1]);
											}
										?>
									</select>
									Major:
									<select name="major2">
										<option>N/A</option>
										<?php
											for ($i = 0; $i < sizeof($majors); $i++) {
												printf("<option value=\"%s\">%s</option>", $majors[$i][0], $majors[$i][1]);
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
												printf("<option value=\"%s\">%s</option>", $colleges[$i][0], $colleges[$i][1]);
											}
										?>
									</select>
									Major:
									<select name="major3">
										<option>N/A</option>
										<?php
											for ($i = 0; $i < sizeof($majors); $i++) {
												printf("<option value=\"%s\">%s</option>", $majors[$i][0], $majors[$i][1]);
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
										printf("<option value=\"%s\">%s</option>", $majors[$i][0], $majors[$i][1]);
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
										printf("<option value=\"%s\">%s</option>", $majors[$i][0], $majors[$i][1]);
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
										printf("<option value=\"%s\">%s</option>", $majors[$i][0], $majors[$i][1]);
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
					<a href="index.php" class="STYLE7">Go back to log in.</a>
					</div>
					<label></label>
					<div align="center">
					<label></label>
					</div>          
					<div align="right"></div><label></label></td>
				</tr>
			</table>
		</form>
		<p class="STYLE1 STYLE6">* Please enter up to 3 majors and minors. Choose N/A if you have less than 3. At this time we do not support more than 3 majors or minors. We apologize for any inconvinience. </p>
		<p class="STYLE1 STYLE6">&nbsp;</p>
		<p class="STYLE1 STYLE6">&nbsp;</p>
		<p class="STYLE1 STYLE6">&nbsp;</p>
		<p class="STYLE1 STYLE6">&nbsp;</p>
		<p class="STYLE1 STYLE6">&nbsp;</p>
		<p class="STYLE1 STYLE6">&nbsp;</p>
		</div>
        <div id="footer" style="background-color: #6DB4F2; clear: both; text-align: center; color: #FFFFFF; style=; font-family: Baskerville, 'Palatino Linotype', Palatino, 'Century Schoolbook L', 'Times New Roman', serif;"background-color: #6DB4F2; clear: both; text-align: center; color: #FFFFFF;">
Designed by Team 11 in CS307, Fall 2013, Purdue University, West Lafayette</span></div>
	</body>
</html>
