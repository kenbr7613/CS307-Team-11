<!DOCTYPE html>
<html>
    <head>
        <title>Purdue Planner</title>
    <style type="text/css">
        body 
        {
	background-image:url(images/backgd.jpg);
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
.STYLE16 {color: #FFFF00; font-style: italic; font-family: Georgia, "Times New Roman", Times, serif;}
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

-->
    
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312"></head>
    <body>
    
    <div align="center" valign="middle">
      <h1>&nbsp;</h1>
      <h1 align="center"><img src="images/purdue_logo.png" width="215" height="80"></h1>
      <h1 class="STYLE16">Welcome to Purdue Planner! </h1>
      <p class="STYLE21">Please log in below</p>
      <form name="form1" method="post" action="login_check.php">
        <table width="580" height="134" border="2" class="table">
          <tr>
            <th width="280" scope="col"><div align="center"><span class="STYLE11">Email</span></div></th>
            <th width="278" scope="col"><label>
              <input type="text" name="user">
            </label></th>
          </tr>
          <tr>
            <td><div align="center"><span class="STYLE20">Password </span></div></td>
            <td><div align="center">
              <label>
              <input type="password" name="pass">
              </label>
            </div></td>
          </tr>
          <tr>
            <td colspan="2"><div align="center">
              <label>
              
              
              <div align="center">
                <input name="Submit" type="submit" class="button" value="Login">
                <a href="signup.php" class="STYLE7">New user? Click here to sign up.</a> </div>
              </label>
              
            </div></td>
          </tr>
        </table>
      </form>
      <p class="STYLE17">&nbsp;</p>
      <p class="STYLE18">Designed and developed by Team 11, at Purdue University, West Lafayette, IN, in 2013. </p>
    </div>
    </body>
</html>
