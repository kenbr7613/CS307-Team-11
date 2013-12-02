<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Purdue Planner</title>
<style type="text/css"> 
<!-- 
 
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

.button {
   border-top: 1px solid #adadad;
   background: #969696;
   background: -webkit-gradient(linear, left top, left bottom, from(#c4c4c4), to(#969696));
   background: -webkit-linear-gradient(top, #c4c4c4, #969696);
   background: -moz-linear-gradient(top, #c4c4c4, #969696);
   background: -ms-linear-gradient(top, #c4c4c4, #969696);
   background: -o-linear-gradient(top, #c4c4c4, #969696);
   padding: 4px 13px;
   -webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 5px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
   color: white;
   font-size: 9px;
   font-family: Georgia, serif;
   text-decoration: none;
   vertical-align: middle;
   }
.button:hover {
   border-top-color: #7a757a;
   background: #7a757a;
   color: #ccc;
   }
.button:active {
   border-top-color: #525252;
   background: #525252;
   }
 
--> 
</style> 
</head>


<body>
<div id="navbar"> 
  <ul> 
	<li>
	  <h2 style="color: #FFFFFF; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;"><img src="3.jpg" width="198" height="70" alt=""/></h2>
	</li> 
    
  </ul> 
</div> 
<div style="width: 100%; height: 700px; font-size: 24px; color: #6F6F70; font-style: normal; font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif;">
<center>
  <h2>Purdue Planner Sign In</h2>
  <div style="width: 1200px; height: 500px; box-shadow: 5px 5px 2px #888888; border: 1px solid; border-radius: 0px; font-size: 14px; color: #68686B;">
  		<div id="img" style="background-color: #FFF; height: 500px; width: 700px; float: left; border: 1px solid; border-radius: 0px; color: #000000;">
        	<img src="1.jpg" height="500" width="700">
        </div>
      	<div>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <form id="form1" method="post" action="login_check.php" >
        <p>
          <label for="textfield"><span style="font-size: 14px">EMAIL</span><br>
          </label>
          <input type="text" name="user" id="user">
        </p>
        <p>
          <label for="password">PASSWORD<br>
          </label>
          <input type="password" name="pass" id="pass">
        </p>
        <p>
          <input name="submit" type="submit" class="button" id="submit" value="Login">
        </p>
        <p>&nbsp;</p>
        <p><a href="signup.php">Dont'have an account yet?Click here to sign up!</a></p>
        </form>
        </div>
      
  </div>
</center>
</div>
<p>&nbsp;</p>
<div id="footer" style="background-color: #6DB4F2; clear: both; text-align: center; color: #FFFFFF; style=; font-family: Baskerville, 'Palatino Linotype', Palatino, 'Century Schoolbook L', 'Times New Roman', serif;"background-color: #6DB4F2; clear: both; text-align: center; color: #FFFFFF;">
Designed by Team 11 in CS307, Fall 2013, Purdue University, West Lafayette</span></div>
</body>
</html>