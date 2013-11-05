<!DOCTYPE html>
<html>
    <head>
		<?php
			include('session_login_check.php');
			include "DBaccess.php";
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
			<h1 class="STYLE16">Edit Your Account </h1>
			<form name="form1" method="post" action="settingsChanged.php">
			<table width="663" height="344" border="2">
				<tr>
				<td><div align="center" class="STYLE3">Email</div></td>
				<td><label>
				<div align="center">
				<input name="email" type="text" id="email" />
				</div>
				</label></td>
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
				<td><div align="center" class="STYLE3">First Name </div></td>
				<td><label>
				<div align="center">
				<input name="fname" type="text" id="fname" />
				</div>
				</label></td>
				</tr>
				<tr>
				<td><div align="center" class="STYLE3">Last Name </div></td>
				<td><label>
				<div align="center">
				<input name="lname" type="text" id="lname" />
				</div>
				</label></td>
				</tr>
				<tr>
				<td><div align="center" class="STYLE3">College</div></td>
				<td><label>
				<div align="center">
				<select name="select">
				</select>
				</div>
				</label></td>
				</tr>
				<tr>
				<td height="38"><div align="center" class="STYLE3">Major(s) * </div></td>
				<td><label>
				<div align="center">
				<select name="select2">
				</select>
				<select name="select3">
				<option>N/A</option>
				</select>
				<select name="select4">
				<option>N/A</option>
				</select>
				</div>
				</label></td>
				</tr>
				<tr>
				<td height="38"><div align="center" class="STYLE3">Minor(s) * </div></td>
				<td><div align="center">
				<label>
				<select name="select5">
				<option>N/A</option>
				</select>
				</label>
				<label>
				<select name="select6">
				<option>N/A</option>
				</select>
				</label>
				<label>
				<select name="select7">
				<option>N/A</option>
				</select>
				</label>
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
		</div>
	</body>
</html>
