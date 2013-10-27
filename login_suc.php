<?php
session_start();
//if(!session_is_registered($_GET["username"])){
$username = $_GET["username"];
if(!isset($_SESSION['username'] ) ) {
	echo "error";
	die();
}

echo "Login Success";

?>


