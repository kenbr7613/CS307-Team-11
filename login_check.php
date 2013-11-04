<?php
include "DBaccess.php";

$db = dbConnect();
$username = mysql_real_escape_string($_POST["user"]); 
$password = md5( mysql_real_escape_string($_POST["pass"]) ); 

/*
// invalid character check
if (strpos($password, "'") != false or strpos($password, "\"") != false) {
	header("location:login_fail.php");
	exit();
}
if (strpos($username, "'") != false or strpos($username, "\"") != false) {
        header("location:login_fail.php);
	exit();
}
*/

$sql = "SELECT UserID FROM Users where Username='$username' AND Password='$password'";
$result = mysql_query($sql, $db);

if( mysql_num_rows($result) == 1) {	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	session_start();
	$_SESSION['login'] = $row["UserID"];
	header("location:login_suc.php?username=$username");
}
else {
	header("location:login_fail.php?username=$username");
}	

?>
