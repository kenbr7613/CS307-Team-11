<!DOCTYPE html>
<html>
    <head>
        <title>Purdue Planner</title>
    <style type="text/css">
        body 
        {
            background-image:url(images/backgd.jpg);
        }
<!--
Backgd {
	background-image: url(images/backgd.jpg);
}
.STYLE2 {
	color: #FF0000;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: bold;
}
.STYLE4 {color: #FF9900}
.STYLE6 {
	color: #FF9900;
	font-weight: bold;
	font-family: Georgia, "Times New Roman", Times, serif;
}
.STYLE7 {
	font-family: Arial, Helvetica, sans-serif;
	color: #999999;
}
.STYLE9 {color: #FF9900; font-family: Georgia, "Times New Roman", Times, serif; }
-->
    
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312"></head>
    <body>
    
    <div align="center" valign="middle">
      <h1><span class="STYLE9">Welcome to Purdue Planner! </span></h1>
      <p><span class="STYLE2">Please log in below</span></p>
      <form name="form1" method="post" action="welcome.php">
        <table width="580" height="134" border="2">
          <tr>
            <th width="356" scope="col"><span class="STYLE9">Purdue Career Account </span></th>
            <th width="206" scope="col"><label>
              <input type="text" name="uname">
            </label></th>
          </tr>
          <tr>
            <td><div align="center"><span class="STYLE6">Password </span></div></td>
            <td><div align="center">
              <label>
              <input type="password" name="pwd">
              </label>
            </div></td>
          </tr>
          <tr>
            <td colspan="2"><div align="center">
              <label>
              
              <div align="center">
                <input type="submit" name="Submit" value="Login">
              <a href="signup.php" class="STYLE7">New user? Click here to sign up.</a>              </div>
              </label>
              <div align="left"><a href="#" class="STYLE4"></a></div>
            </div></td>
          </tr>
        </table>
      </form>
      <p>&nbsp;</p>
    </div>
    </body>
</html>
