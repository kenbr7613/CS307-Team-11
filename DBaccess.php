<?php


/////////////////////////////////////////////////////////
//
//Function: dbConnectPDO
//
//Author: Ki Hyun Koo
//
//Use: Connects to Database Using PDO
//
//Arguments: NONE
//
//Return: 
//$pdo		-PDO database object
//
/////////////////////////////////////////////////////////
/*
function dbConnectPDO() {
	$pdo = new PDO('mysql:host=$db_host;dbname=db_name', $username, $password);
	return $pdo;
}
*/


/////////////////////////////////////////////////////////
//Function: dbConnect
//
//Author: Ki Hyun Koo
//
//Use: Connects to Database
//
//Arguments: NONE
//
//Return: 
//$db		-database object
//
/////////////////////////////////////////////////////////
function dbConnect() {
	//include db config file
	include('DBconfig.php');
	
	$db = mysql_connect($db_host, $db_username, $db_password) or die ('could not connect');
	
	//$db = mysql_connect('lore.cs.purdue.edu:11394', 'root','cs307team11') or die ('could not connect');
	mysql_select_db($db_name, $db);
	if (!$db) {
    		die('Could not connect: ' . mysql_error());
	}
	else {
		return $db;
	}
}


/////////////////////////////////////////////////////////
//Function: checkCred
//
//Author: Ki Hyun Koo
//
//Use: Checks user's credentials
//
//Arguments:
//$user		-username
//$pass		-password
//
//
//Return: 
//0			-if fail
//1 		-if passed
/////////////////////////////////////////////////////////
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

/////////////////////////////////////////////////////////
//Function: Login
//
//Author: Ki Hyun Koo
//
//Use: Checks credentials of user using username and password then redirects to given url a new session is started with username
//
//Arguments:
//$user		-username
//$pass		-password
//$urlSuc	-url to be redirected if login is sucessful
//$urlFail	-url to be redirected if login fails
//
//Return: NONE
/////////////////////////////////////////////////////////
function login($user, $pass, $urlSuc, $urlFail) {
	$db = dbConnect();
	$username = mysql_real_escape_string($user); 
	$password = md5( mysql_real_escape_string($_POST['$pass']) ); 
	
	$sql = "SELECT * FROM Users where Username='$username' AND Password='$password'";
	
	$result = mysql_query($sql, $db);
	
	if( mysql_num_rows($result) == 1) {
		session_start();
		$_SESSION['login'] = $username;
		header("location:{$urlSuc}");
	}
	else {
		header("location:{$urlFail}");
	}	
}


?>
