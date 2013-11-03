<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>Registration Review</title>
<style type="text/css">
<!--
body {
	background-image: url(images/backgd.jpg);
}
.STYLE1 {color: #FF0000}
.STYLE2 {
	font-family: "Times New Roman", Times, serif;
	font-weight: bold;
}
.STYLE4 {color: #FF0000; font-weight: bold; }
-->
</style></head>

<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<h1 align="center" class="STYLE2"><img src="images/purdue_logo.png" width="215" height="80" /></h1>
<p align="center" class="STYLE2">&nbsp;</p>
<h1 align="center" class="STYLE2">Registration Review</h1>
<p align="center" class="STYLE2">&nbsp;</p>
<div align="center">
  <table width="555" height="124" border="2">
    <tr>
      <td width="267" height="29"><div align="center">Passwords match each other </div></td>
      <td width="270"><div align="center" class="STYLE1">
        <div align="center"><strong id="tx1">NO</strong></div>
      </div></td>
    </tr>
    <tr>
      <td height="25"><div align="center">Password is legal </div></td>
      <td><div align="center" class="STYLE1"><strong  id="tx2">NO</strong></div></td>
    </tr>
    <tr>
      <td height="30"><div align="center">Email is not occupied </div></td>
      <td><div align="center" class="STYLE1"><strong id="tx4">NO</strong></div></td>
    </tr>
    <tr>
      <td height="26"><div align="center">Email is legal </div></td>
      <td><div align="center" class="STYLE4"><strong id="tx3">NO</strong></div></td>
    </tr>
  </table>
  <h1 class="STYLE1" id="text">&nbsp; </h1>
</div>
<?php
	include "DBaccess.php";
	$everyThingGood=1; // 1: everything is good and user is created successfully; 0: something is wrong!
	$passwd=$_POST["passwd"]; // get password
	$passwd2=$_POST["passwd2"]; // get repeated password
	$email=$_POST["email"]; // get email
	if($passwd === $passwd2) {
		// check if passwords match
		echo "<script>document.getElementById(\"tx1\").innerHTML=\"OK\";</script>";
		echo "<script>document.getElementById(\"tx1\").style.color=\"#339900\";</script>";
	} else {
		$everyThingGood=0;
	}
	if(strlen($passwd) < 6) {
		// is password too short?
		echo "<script>document.getElementById(\"tx2\").innerHTML=\"Too short\";</script>";
		$everyThingGood=0;
	} elseif (strpos($passwd, "'") != false or strpos($passwd, "\"") != false) {
		// check if there is a \" or ' to prevent sql injection
		echo "<script>document.getElementById(\"tx2\").innerHTML=\"Invalid character\";</script>";
		$everyThingGood=0;
	} else {
		echo "<script>document.getElementById(\"tx2\").innerHTML=\"OK\";</script>";
		echo "<script>document.getElementById(\"tx2\").style.color=\"#339900\";</script>";
	}
	$preg=preg_match("/\w+([-+.']\w+)*@\w+\.\w+([-.]\w+)*/",trim($email));
	if($preg) {
		// check email format
		echo "<script>document.getElementById(\"tx3\").innerHTML=\"OK\";</script>";
		echo "<script>document.getElementById(\"tx3\").style.color=\"#339900\";</script>";
	} else {
		$everyThingGood=0;
	}
	$db = dbConnect();
	$sql = "SELECT * FROM Users where Username='$email'";
	$result = mysql_query($sql, $db);
	if( mysql_num_rows($result) == 0) {
		echo "<script>document.getElementById(\"tx4\").innerHTML=\"OK\";</script>";
		echo "<script>document.getElementById(\"tx4\").style.color=\"#339900\";</script>";
	} else {
		$everyThingGood=0;
	}
	
	$lname=mysql_real_escape_string($_POST["lname"]);
	$fname=mysql_real_escape_string($_POST["fname"]);
	
		
	
	// if something is wrong, show "go back" button; otherwise show link to login screen
	if($everyThingGood == 0) {
		echo "<p align=\"center\"><a href='javascript:history.go(-1)'><input type=\"button\" name=\"Submit3\" value=\"Go back\" /></a></p>";
	} else {
		$md5pass=md5(mysql_real_escape_string($passwd));
		$sql="INSERT INTO Users(Username,Password,FirstName,LastName) VALUES('$email','$md5pass','$fname','$lname')";
		$result = mysql_query($sql, $db);
		echo "<p align=\"center\">User successfully created. Please <a href=\"index.php\">log in</a>.</p>";
	}
?>


</body>
</html>
