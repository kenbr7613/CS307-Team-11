<?php
// Create connection
$con=mysqli_connect("lore.cs.purdue.edu:11394","root","cs307team11","purduePlannerDB");

// Check connection
if (mysqli_connect_errno($con))
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
else {
	echo "Connection established.";
}

?> 
