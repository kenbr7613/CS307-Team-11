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

$sql = "SELECT * FROM Users where Username='$username' AND Password='$password'";
//echo $username;
//echo $_POST["pass"];
//echo $password;
$result = mysql_query($sql, $db);

if( mysql_num_rows($result) == 1) {
	//session_register($username);
	//session_register($password);
	session_start();
	$_SESSION['login'] = $username;
	header("location:login_suc.php?username=$username");
}
else {
	header("location:login_fail.php?username=$username");
}	

?>
