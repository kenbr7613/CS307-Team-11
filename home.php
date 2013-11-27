<html>
<head>
<title>Purdue Planner Homepage</title>
<style type="text/css"> 
<!-- 

.offscreen { 
  position: absolute; 
  top: -30em; 
  left: -300em; 
} 

div#vmenu { 
   margin: 0; 
   padding: .25em 0em .25em 0em; 
   border: solid 1px #30c9e0; 
   background: #29088A; 
   width: 13.5em; 
} 

div#vmenu ul { 
   margin: 0; 
   padding: 0; 
   list-style: none; 
} 

div#vmenu ul li { 
   margin: 0; 
   padding: 0; 
   list-style: none; 
} 

div#vmenu ul a:link { 
   margin: 0; 
   padding: .2em 0em .2em .4em; 
   text-decoration: none; 
   font-weight: bold; 
   font-size: medium; 
   background-color: #29088A; 
   color: #0059a0; 
   display: block; 
} 

div#vmenu ul a:active { 
   margin: 0; 
   padding: .25em .5em .25em .5em; 
   text-decoration: none; 
   font-weight: bold; 
   font-size: medium; 
   background: #4d8cba; 
   color: #ffffff; 
   display: block; 
} 

div#vmenu ul a:visited { 
   margin: 0; 
   padding:  .25em .5em .25em .5em; 
   text-decoration: none; 
   font-weight: bold; 
   font-size: medium; 
   background: #29088A; 
   color: #ffffff; 
   display: block; 
} 

div#vmenu ul li a:hover { 
   margin: 0; 
   padding: .2em 0em .2em .4em; 
   text-decoration: none; 
   font-weight: bold; 
   font-size: medium; 
   background-color: #30c9e0; 
   color: #ffffff; 
   display: block; 
} 
 
 #navbar ul { 
	margin: 0; 
	padding: 5px; 
	list-style-type: none; 
	text-align: left; 
	background-color: #6DB4F2; 
	} 
 
#navbar ul li {  
	<!--display: inline;--> 
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
   border-top: 1px solid #96d1f8;
   background: #65a9d7;
   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
   background: -o-linear-gradient(top, #3e779d, #65a9d7);
   padding: 15px 25px;
   -webkit-border-radius: 17px;
   -moz-border-radius: 17px;
   border-radius: 17px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
   color: white;
   font-size: 24px;
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
 
--> 
</style> 
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
				while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
					array_push($courseidArray, $row[0]);					
				}
				$sql = "select Credits from Courses where CourseID in (" . implode(", ", $courseidArray) . ");";
				$result = mysql_query($sql, $db);
				while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
					$totalCredits = $totalCredits + $row[0];
				}
				$creditMax = 120;
				return sprintf("%.0f", ($totalCredits/$creditMax)*100);
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
body {
	background-color: #FFFFFF;
}
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="fb-root"></div>
                <script>
                  window.fbAsyncInit = function() {
                        FB.init({
                          appId      : '255720611242546', // App ID
                          channelURL : 'lore.cs.purdue.edu:11392/channel.html', // Channel File
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
<center>
<div id="navbar"> 
<ul> 
	<li>
	  <h2 style="color: #FFFFFF; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;">Purdue Planner</h2>
	</li> 
    
  </ul> 
</div> 
<div style="position:relative; width: 100%; height: 800px; font-size: 24px; color: #6F6F70; font-style: normal; font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif;">

	<div id="vmenu" style="background-color: #29088A; height: 99%; width: 15%; float: left; border: 0px solid; border-radius: 0px; color: #000000;">
    <ul> 
	<li>
	  <h4 style="color: #FFFFFF; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: center;">
      <a target="frame" href="gradProcess.php">Home</a>
      <a target="frame" href="suggestSchedule.php"> Suggest a Schedule</a>
      <a target="frame" href="editAccount.php"> Modify Account</a>
      <a target="frame" href="viewSchedule.php"> View/Edit Current Schedule</a>
      <a href="logout.php"> Logout</a></h2>
	</li>
  	</ul> 
    </div>
    <div id="content" style="background-color: #FFF; height: 800px; width: 80%; float: left; border: 0px solid; border-radius: 0px; color: #000000;">
	<iframe name="frame" id="frame" width="100%" height="798" frameborder="0" src="gradProcess.php" seamless>
  	<p>Your browser does not support iframes.</p>
	</iframe>
    </div>
    
    <!--<div align="center" class="fb-login-button" data-show-faces="false" data-width="200" data-max-rows="1" onlogin="getFriends();">Login with Facebook</div>
                <p id="flist"></p>
  </div>-->
</div>
<div id="footer" style="background-color: #6DB4F2; clear: both; text-align: center; color: #FFFFFF; style=; font-family: Baskerville, 'Palatino Linotype', Palatino, 'Century Schoolbook L', 'Times New Roman', serif;"background-color: #6DB4F2; clear: both; text-align: center; color: #FFFFFF;">
Designed by Team 11 in CS307, Fall 2013, Purdue University, West Lafayette</span></div>
</center>
<!-- End Save for Web Slices -->
</body>
</html>