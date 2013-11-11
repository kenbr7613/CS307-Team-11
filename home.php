<!DOCTYPE html>
<html>
    <head>
		<?php
			include('session_login_check.php');
			include "DBaccess.php";
			function getPercentComplete() {
				$userid = $_SESSION['login'];
				$totalCredits = 0;
				$db = dbConnect();
				$sql = sprintf("select CourseID from UserCoursesCompleted where UserID=\"%s\"", $userid);
				$result = mysql_query($sql, $db);
				$courseidArray = array();
				if (mysql_num_rows($result) == 0) {
					return "0%";
				} else {
					while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
						array_push($courseidArray, $row[0]);					
					}
					$sql = "select Credits from Courses where CourseID in (" . implode(", ", $courseidArray) . ");";
					$result = mysql_query($sql, $db);
					while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
						$totalCredits = $totalCredits + $row[0];
					}
					$creditMax = 124;
					return sprintf("%.0f", ($totalCredits/$creditMax)*100);
				}
			}
		?>
		<script>
			function getFriends() {
			var flist = document.getElementById("flist");
			flist.innerHTML = "Your friends: <br />";

			// FB.getLoginStatus(function(response) {
					// flist.innerHTML = response.status;
					// var uid = response.authResponse.userID;
					// var accessToken = response.authResponse.accessToken;
					// if (response.status == 'connected') {
						// FB.api('me/friends', { fields: 'first_name, last_name'},function(response){
							  
					   // });
					  // }
				// });
			
			FB.api('/me/friends', function(response) {
				var friendsSale = response.data;
				var len = friendsSale.length;
				for (var i=0; i<len; i++) {
					flist.innerHTML = flist.innerHTML + friendsSale[i].name + ", ";
				}
			});
			}
		</script>
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
		<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
	</head>
	<body>
		<div id="fb-root"></div>
		<script>
		  window.fbAsyncInit = function() {
			FB.init({
			  appId      : '255720611242546', // App ID
			  channelURL : 'lore.cs.purdue.edu:11392/testing/channel.html', // Channel File
			  status     : true, // check login status
			  cookie     : true, // enable cookies to allow the server to access the session
			  oauth      : true, // enable OAuth 2.0
			  xfbml      : true  // parse XFBML
			});

			// Additional initialization code here
		  };

		  // Load the SDK Asynchronously
		  (function(d){
			 var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
			 js = d.createElement('script'); js.id = id; js.async = true;
			 js.src = "//connect.facebook.net/en_US/all.js";
			 d.getElementsByTagName('head')[0].appendChild(js);
		   }(document));
		</script>
		<div align="center" valign="middle">
			<h1>&nbsp;</h1>
			<h1 align="center"><img src="images/purdue_logo.png" width="215" height="80"></h1>
			<h1 class="STYLE16">Your Purdue Planner Home Page </h1>
			<h2>Graduation Progress:</h2>
			
			<table>
			  <tr>
				<td><?php 
							$percentComplete = getPercentComplete();
							printf("%s%%: ", $percentComplete); 
						?></td>
				<td width=200 style="border: 2px solid black;padding:none">
				  <hr style="color:#008000;background-color:#008000;height:15px; border:none;
							 margin:0;" align="left" width=<?php
									echo "$percentComplete";
								 ?>% />
				</td>
			  </tr>
			</table>
			<br />
			<h2>Schedule Manager:</h2>
			<table width="580" height="134" border="1">
				<tr>
					<td align="center"><form action="suggestSchedule.php"><input class="button" type="submit" value="Suggest a Schedule"></form></td>
					<td align="center"><form action="viewSchedule.php"><input class="button" type="submit" value="View/Edit Your Current Schedule"></form></td>
				</tr>
				<tr>
					<td align="center"><form action="editAccount.php"><input class="button" type="submit" value="Edit Your Account"></form></td>
					<td align="center"><form action="logout.php"><input class="button" type="submit" value="Logout"></form></td>
				</tr>
		</table>
		<br />
		<br />
		<div align="center" class="fb-login-button" data-show-faces="false" data-width="200" data-max-rows="1" onlogin="getFriends();">Login with Facebook</div>
		<p id="flist"></p>
		</div>
	</body>
</html>
