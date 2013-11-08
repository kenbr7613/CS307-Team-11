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

      <td><div align="center" class="STYLE1">
        <div align="center"><strong  id="tx2">NO</strong></div>
      </div></td>
    </tr>
    <tr>
      <td height="30"><div align="center">Email is not occupied </div></td>
      <td><div align="center" class="STYLE1" id="tx4"><strong>NO</strong></div></td>
    </tr>
    <tr>
      <td height="26"><div align="center">Email is legal </div></td>
      <td><div align="center" class="STYLE4" id="tx3">NO</div></td>
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
	if( mysql_num_rows($result) == 1) {
		$everyThingGood=0;
	} else {
		echo "<script>document.getElementById(\"tx4\").innerHTML=\"OK\";</script>";
		echo "<script>document.getElementById(\"tx4\").style.color=\"#339900\";</script>";
	}
	
	$lname=mysql_real_escape_string($_POST["lname"]);
	$fname=mysql_real_escape_string($_POST["fname"]);
	
	$college1=$_POST["select"];
	$major1=$_POST["select2"];
	$major2=$_POST["select3"];
	$major3=$_POST["select4"];
	$minor1=$_POST["select5"];
	$minor2=$_POST["select6"];
	$minor3=$_POST["select7"];
	
	// if something is wrong, show "go back" button; otherwise show link to login screen
	if($everyThingGood == 0) {
		echo "<p align=\"center\"><a href='javascript:history.go(-1)'><input type=\"button\" name=\"Submit3\" value=\"Go back\" /></a></p>";
	} else {
		$md5pass=md5(mysql_real_escape_string($passwd));
		$sql="INSERT INTO Users(Username,Password,FirstName,LastName,College1,Major1,Major2,Major3,Minor1,Minor2,Minor3) VALUES('$email','$md5pass','$fname','$lname','$college1','$major1','$major2','$major3','$minor1','$minor2','$minor3')";
		$result = mysql_query($sql, $db);
		echo "<p align=\"center\">User successfully created. Please <a href=\"index.php\">log in</a>.</p>";
	}
?>


</body>
</html>
