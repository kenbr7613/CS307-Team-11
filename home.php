<html>
<head>
<title>Purdue Planner Homepage</title>
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
<table id="__01" width="801" height="600" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="8">
			<img src="images/&#x672a;&#x6807;&#x9898;-1_01.jpg" width="800" height="41" alt=""></td>
		<td>
			<img src="images/&#x5206;&#x9694;&#x7b26;.gif" width="1" height="41" alt=""></td>
	</tr>
	<tr>
		<td colspan="6" rowspan="2">
			<img src="images/&#x672a;&#x6807;&#x9898;-1_02.jpg" width="730" height="151" alt=""></td>
		<td>
			<a href="logout.php">
				<img src="images/LOGOUT.jpg" width="54" height="33" border="0" alt=""></a></td>
		<td rowspan="6">
			<img src="images/&#x672a;&#x6807;&#x9898;-1_04.jpg" width="16" height="559" alt=""></td>
		<td>
			<img src="images/&#x5206;&#x9694;&#x7b26;.gif" width="1" height="33" alt=""></td>
	</tr>
	<tr>
		<td rowspan="5">
			<img src="images/&#x672a;&#x6807;&#x9898;-1_05.jpg" width="54" height="526" alt=""></td>
		<td>
			<img src="images/&#x5206;&#x9694;&#x7b26;.gif" width="1" height="118" alt=""></td>
	</tr>
	<tr>
		<td rowspan="4">
			<img src="images/&#x672a;&#x6807;&#x9898;-1_06.jpg" width="91" height="408" alt=""></td>
		<td>
			<a href="suggestSchedule.php">
				<img src="images/suggest_schedule.jpg" width="234" height="103" border="0" alt=""></a></td>
		<td rowspan="4">
			<img src="images/&#x672a;&#x6807;&#x9898;-1_08.jpg" width="96" height="408" alt=""></td>
		<td colspan="2">
			<a href="viewSchedule.php">
				<img src="images/viewSchedule.jpg" width="250" height="103" border="0" alt=""></a></td>
		<td rowspan="4">
			<img src="images/&#x672a;&#x6807;&#x9898;-1_10.jpg" width="59" height="408" alt=""></td>
		<td>
			<img src="images/&#x5206;&#x9694;&#x7b26;.gif" width="1" height="103" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/&#x672a;&#x6807;&#x9898;-1_11.jpg" width="234" height="61" alt=""></td>
		<td colspan="2">
			<img src="images/&#x672a;&#x6807;&#x9898;-1_12.jpg" width="250" height="61" alt=""></td>
		<td>
			<img src="images/&#x5206;&#x9694;&#x7b26;.gif" width="1" height="61" alt=""></td>
	</tr>
	<tr>
		<td>
			<a href="editAccount.php">
				<img src="images/EDIT_ACCOUNT.jpg" width="234" height="101" border="0" alt=""></a></td>
		<td rowspan="2">
			<img src="images/&#x672a;&#x6807;&#x9898;-1_14.jpg" width="11" height="244" alt=""></td>
		<td>
			<a href="gradProcess.php">
				<img src="images/gradProgress.jpg" width="239" height="101" border="0" alt=""></a></td>
		<td>
			<img src="images/&#x5206;&#x9694;&#x7b26;.gif" width="1" height="101" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/&#x672a;&#x6807;&#x9898;-1_16.jpg" width="234" height="143" alt=""></td>
		<td>
			<img src="images/&#x672a;&#x6807;&#x9898;-1_17.jpg" width="239" height="143" alt=""></td>
		<td>
			<img src="images/&#x5206;&#x9694;&#x7b26;.gif" width="1" height="143" alt=""></td>
	</tr>
</table>
<div align="center" class="fb-login-button" data-show-faces="false" data-width="200" data-max-rows="1" onlogin="getFriends();">Login with Facebook</div>
                <p id="flist"></p>
</div>
</center>
<!-- End Save for Web Slices -->
</body>
</html>