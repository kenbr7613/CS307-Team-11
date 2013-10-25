
<?php
echo "hello";
include "/u/u88/bpastine/cs307/apache/DBconfig.php";

function dbConnectPDO() {
	$pdo = new PDO('mysql:host=$db_host;dbname=db_name', $username, $password);
	return $pdo;
}

function dbConnect() {
	$db = mysql_connect("$db_host", "$username", $password);
	mysql_select_db("$db_name");
	return $db;
}

//Checks user credentials
//argument: user, password
//return 0 fail
//return 1 pass

function checkCred($user, $pass) {
	$db = dbConnect();
	$username = mysql_real_escape_string($_POST['$user']); 
	$password = md5( mysql_real_escape_string($_POST['$pass']) ); 	

	$result = mysql_query("SELECT * FROM Users where username='$username' password='$password'");
	if( mysql_num_rows($result) ) {
		return 1;
	}
	else {
		return 0;
	}
}

function login($user, $pass) {
	session_register("'$user'");
	session_register("'$pass'");
}


echo "hello";
//test
if ( checkCred("abc", md5("abcdefg")) ) {
	echo "correct";
}
else {
	echo "false";
}

?>
