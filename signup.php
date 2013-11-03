<style type="text/css">
<!--
.STYLE1 {font-family: Geneva, Arial, Helvetica, sans-serif}
body {
	background-image: url(images/backgd.jpg);
}
.STYLE2 {font-family: Geneva, Arial, Helvetica, sans-serif; color: #999900; }
.STYLE3 {
	color: #000000;
	font-weight: bold;
	font-family: "Times New Roman", Times, serif;
}
.STYLE4 {color: #000000}
.STYLE5 {color: #FFFF33}
.STYLE6 {font-size: 12px}
.STYLE7 {
	color: #FF6600;
	font-weight: bold;
	font-size: 12px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
-->
</style>
<title>Sign Up</title><div align="center">
  <h1 class="STYLE2 STYLE4">&nbsp;</h1>
  <h1 class="STYLE2 STYLE4"><img src="images/purdue_logo.png" width="215" height="80" /></h1>
  <h1 class="STYLE2 STYLE5">New User for Purdue Planner </h1>
  <p class="STYLE1">&nbsp;</p>
  <form id="form1" name="form1" method="post" action="register.php">
    <table width="663" height="344" border="2">
      
      <tr>
        <td><div align="center" class="STYLE3">Email</div></td>
        <td><label>
          <div align="center">
            <input name="email" type="text" id="email" />
          </div>
        </label></td>
      </tr>
      
      <tr>
        <td height="28"><div align="center"><span class="STYLE3">First Name </span></div></td>
        <td><div align="center">
          <label>
          <input name="fname" type="text" id="fname" />
          </label>
        </div></td>
      </tr>
      <tr>
        <td height="30"><div align="center"><span class="STYLE3">Last Name </span></div></td>
        <td><div align="center">
          <label>
          <input name="lname" type="text" id="lname" />
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="center" class="STYLE3">Purdue Planner Password (at least 6 characters) </div></td>
        <td><label>
          <div align="center">
            <input name="passwd" type="password" id="passwd" />
          </div>
        </label></td>
      </tr>
      <tr>
        <td><div align="center" class="STYLE3">Confirm Password </div></td>
        <td><label>
          <div align="center">
            <input name="passwd2" type="password" id="passwd2" />
          </div>
        </label></td>
      </tr>
      <tr>
        <td><div align="center" class="STYLE3">First Name </div></td>
        <td><label>
          <div align="center">
            <input name="fname" type="text" id="fname" />
          </div>
        </label></td>
      </tr>
      <tr>
        <td><div align="center" class="STYLE3">Last Name </div></td>
        <td><label>
          <div align="center">
            <input name="lname" type="text" id="lname" />
          </div>
        </label></td>
      </tr>
      <tr>
        <td><div align="center" class="STYLE3">College</div></td>
        <td><label>
          <div align="center">
            <select name="select">
            </select>
          </div>
        </label></td>
      </tr>
      <tr>
        <td height="38"><div align="center" class="STYLE3">Major(s) * </div></td>
        <td><label>
          <div align="center">
            <select name="select2">
            </select>
            <select name="select3">
              <option>N/A</option>
            </select>
            <select name="select4">
              <option>N/A</option>
            </select>
          </div>
        </label></td>
      </tr>
      <tr>
        <td height="38"><div align="center" class="STYLE3">Minor(s) * </div></td>
        <td><div align="center">
          <label>
          <select name="select5">
            <option>N/A</option>
          </select>
          </label>
          <label>
          <select name="select6">
            <option>N/A</option>
          </select>
          </label>
          <label>
          <select name="select7">
            <option>N/A</option>
          </select>
          </label>
        </div></td>
      </tr>
      <tr>
        <td height="51" colspan="2"><div align="center">
          <label>
          <input type="submit" name="Submit" value="Submit" />
          </label>
          <label>
          <input type="reset" name="Submit2" value="Reset" />
          </label>
          <a href="index.php" class="STYLE7">Go back to log in.</a>
          
        </div>
          <label></label>
          <div align="center">
            <label></label>
          </div>          
          <div align="right"></div><label></label></td>
      </tr>
    </table>
  </form>
  <p class="STYLE1 STYLE6">* Please enter up to 3 majors and minors. Choose N/A if you have less than 3. At this time we do not support more than 3 majors or minors. We apologize for any inconvinience. </p>
  </div>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
