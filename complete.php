<!DOCTYPE html>
<html>
    <head>
	<?php
		include "DBaccess.php";
		$email=$_POST["email"]; // get email
		$courses=$_POST["courses"];
		$ids = explode(" ", $courses);
		
		$db = dbConnect();
		$sql = sprintf("select UserID from Users where Username=\"%s\"", $email);
		$result = mysql_query($sql, $db);
		$ar = mysql_fetch_array($result, MYSQL_BOTH);
		$userId = $ar[0];
		
		for ($i = 0; $i < count($ids); $i++) {
			$sql = sprintf("insert into UserCoursesCompleted (UserID, CourseID) values(%s, %s);", $userId, $ids[$i]);
			$result = mysql_query($sql, $db);
		}
	?>
        <title>Purdue Planner</title>
    <style type="text/css">
        body 
        {
	background-image: url();
	background-repeat: no-repeat;
        }
<!--
Backgd {
	background-image: url(images/backgd.jpg);
}
.STYLE7 {
	font-family: Arial, Helvetica, sans-serif;
	color: #CC9900;
	font-size: 12px;
}
.STYLE11 {
	color: #000000;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 10px;
}
.STYLE16 {
	color: #000DFF;
	font-style: italic;
	font-family: Georgia, "Times New Roman", Times, serif;
}
.STYLE17 {
	font-size: 12px;
	font-family: "Times New Roman", Times, serif;
}
.STYLE18 {
	font-size: 9px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #666666;
}

.button {
   border-top: 1px solid #96d1f8;
   background: #65a9d7;
   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
   background: -o-linear-gradient(top, #3e779d, #65a9d7);
   padding: 3.5px 7px;
   -webkit-border-radius: 5px;
   -moz-border-radius: 5px;
   border-radius: 5px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
   color: white;
   font-size: 10px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
   }
.button:hover {
   border-top-color: #28597a;
   background: #28597a;
   color: #ccc;
   }
.button:active {
   border-top-color: #1b435e;
   background: #1b435e;
   }
   
.STYLE20 {color: #000000; font-weight: bold; font-family: Georgia, "Times New Roman", Times, serif; font-size: 10px; }
.table {
	border: 1px solid #00;
  	border-collapse: collapse;
	border-style:outset;
}
.STYLE21 {
	color: #000000;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 12px;
	font-weight: bold;
}
 #navbar ul { 
	margin: 0; 
	padding: 5px; 
	list-style-type: none; 
	text-align: left; 
	background-color: #6DB4F2; 
	} 
 
#navbar ul li {  
	display: inline; 
	} 
 
#navbar ul li a { 
	text-decoration: none; 
	padding: .2em 1em; 
	color: #fff; 
	background-color: #6DB4F2; 
	} 
 
#navbar ul li a:hover { 
	color: #000; 
	background-color: #fff; 
	} 
-->
    
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312"></head>
    <body>
    <div id="navbar"> 
  <ul> 
	<li>
	  <h2 style="color: #FFFFFF; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;"><img src="3.jpg" width="183" height="62" alt=""/></h2>
	</li> 
    
  </ul> 
</div> 
    <div align="center" valign="middle">
      <h1>&nbsp;</h1>
      <h1 align="center">&nbsp;</h1>
      <h1 class="STYLE16">Account Creation Successful </h1>
      <p class="STYLE21">Please <a href="index.php">log in.</a></p>
    </div>
    </body>
</html>
