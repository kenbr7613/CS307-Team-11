<?php
session_start();
if( !( isset($_SESSION['login']) && $_SESSION['login'] != '' ) )  {
	//login is not set redirect to login
	header ("Location: index.php");
}
?>