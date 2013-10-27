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
.STYLE10 {color: #FFFFFF; font-family: Georgia, "Times New Roman", Times, serif; }
-->
    
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312"></head>
    <body>
    <div align="center" valign="middle">
      <h1><span class="STYLE9">Course Openings&nbsp;&nbsp; </span></h1>
      <p><span class="STYLE2">For Development Purposes Only</span></p>
        <form action="" method="post">
        <table border="0" class="STYLE10">
            <tr>
                <td>Course Number (CRN):</td>
                <td><input name="crn_input" type="text"/></td>
            </tr>
            <tr>
                <td>Term:</td>
                <td><input name="term_input" type="text"/></td>
            </tr>
        </table>
        <input id="submit" type="submit" value="submit" />
        </form>
        <br />
		<?php
			function talkWithDatabase($userID) {
				// connect with database server
				$con = mysql_connect("localhost:11394", "root", "cs307team11") || die("couldn't connect");
				// use a specific database
				$db_selected = mysql_select_db("purdueplanner");
				// pull data from a table in the database
				$result = mysql_query("SELECT * FROM userIDs");
				// iterate though each row of the returned results one at a time
				while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
					if ($row["id"] == $userID) {
						return $row["name"];
					}
				}
				return "unknown";
			}
			function getCourseInformation($term, $crn) {
				$link = sprintf("https://selfservice.mypurdue.purdue.edu/prod/bwckschd.p_disp_detail_sched?term_in=%d&crn_in=%d", $term, $crn);
				$curl =  curl_init($link);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($curl);
				curl_close($curl);
				//get title
				$result = strstr($output, '<TH CLASS="ddlabel" scope="row" >');
				$result = substr($result, 33);
				$title = strstr($result, '<', true);
				//get slots
				$result = strstr($result, '<TH CLASS="ddlabel" scope="row" ><SPAN class="fieldlabeltext">Seats</SPAN></TH>');
				//We have the class segment now
				//get Capacity
				$result = strstr($result, '<TD CLASS="dddefault">');
				$result = substr($result, 22);
				//we have the capacity now
				$capacity = strstr($result, '<', true);
				//get actual
				$result = strstr($result, '<TD CLASS="dddefault">');
				$result = substr($result, 22);
				//we have the capacity now
				$actual = strstr($result, '<', true);
				//get remaining
				$result = strstr($result, '<TD CLASS="dddefault">');
				$result = substr($result, 22);
				//we have the capacity now
				$remaining = strstr($result, '<', true);
				
				printf("<p class=\"STYLE10\">Title: %s</p>", $title);
				printf("<p class=\"STYLE10\">Total Capacity: %d</p>", $capacity);
				printf("<p class=\"STYLE10\">Students Enrolled: %d</p>", $actual);
				printf("<p class=\"STYLE10\">Remaining Slots: %d</p>", $remaining);
			}
			
			$con = mysql_connect("lore.cs.purdue.edu:11394", "root", "cs307team11") || die("couldn't connect");
			if (isset($_POST["crn_input"]) && isset($_POST["term_input"])) {
				$crn = $_POST["crn_input"];
				$term = $_POST["term_input"];
			} else {
				$crn = 0;
				$term = 0;
			}
			$openings = getCourseInformation($term, $crn);
		?>
    </div>
    </body>
</html>
