<?php
//include "/u/u88/bpastine/cs307/apache/DBconfig.php";

include "DBconfig.php";

function dbConnectPDO() {
	$pdo = new PDO('mysql:host=$db_host;dbname=db_name', $username, $password);
	return $pdo;
}

function dbConnect() {
	//$db = mysql_connect($db_host, $db_username, $db_password) or die ('could not connect');
	
	$db = mysql_connect('lore.cs.purdue.edu:11394', 'root','cs307team11') or die ('could not connect');
	mysql_select_db('purduePlannerDB', $db);
	if (!$db) {
    		die('Could not connect: ' . mysql_error());
	}
	else {
		return $db;
	}
}

//Checks user credentials
//argument: user, password
//return 0 fail
//return 1 pass

function checkCred($user, $pass) {
	$db = dbConnect();
	$username = mysql_real_escape_string($_POST['$user']); 
	$password = md5( mysql_real_escape_string($_POST['$pass']) ); 	
	$sql = "SELECT * FROM Users where Username='$username' AND Password='$password'";

	$result = mysql_query($sql, $db);
	
	if( mysql_num_rows($result) ) {
		return 1;
	}
	else {
		return 0;
	}
	
}

function login($user, $pass) {
	$db = dbConnect();
	$username = mysql_real_escape_string($user); 
	$password = md5( mysql_real_escape_string($_POST['$pass']) ); 
	
	$sql = "SELECT * FROM Users where Username='$username' AND Password='$password'";
	
	$result = mysql_query($sql, $db);
	
	if( mysql_num_rows($result) == 1) {
		session_register($username);
		session_register($password);
		header("location:login_suc.php?username=$username");
	}
	else {
		echo "login failed";
	}	
}


//test
/*
if ( checkCred("testuser", md5("testpass")) ) {
	echo "correct";
}
else {
	echo "false";
}
*/
//login("testuser", md5("testpass"));
//login("testuser1", "testpass");


?>
