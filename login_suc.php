<?php
session_start();

if( !( isset($_SESSION['login']) && $_SESSION['login'] != '' ) ) {
	echo "error";
	die();
}

echo "Login Success";

?>


