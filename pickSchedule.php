<style type="text/css"> 
#navbar ul { 
	margin: 0; 
	padding: 3px; 
	list-style-type: none; 
	text-align: left; 
	background-color: #6DB4F2; 
	} 
 
#navbar ul li {  
	display: inline; 
	} 
 
#navbar ul li a { 
	text-decoration: none; 
	padding: .2em 0.2em; 
	color: #fff; 
	background-color: #6DB4F2; 
	} 

#navbarBlue ul { 
	margin: 0; 
	padding: 3px; 
	list-style-type: none; 
	text-align: left; 
	background-color: #29088A; 
	} 

table#carttable {
    background-color:#FFFFFF;
    border: solid #C0C0C0 3px;
}
table#scheduletable {
    background-color:#FFFFFF;
    border-collapse:collapse 
}
table#listscheduletable {
    background-color:#FFFFFF;
    border-collapse:collapse 
}
table#listscheduletable tr:first-child td {
    background-color:#C0C0C0;
    border-collapse:collapse 
}



.cartoutercontainer { position:relative }
.cartinnercontainer { position:relative; width: 50px; top:50%; text-align: center; }

tr.scheduleDays th { background-color:#6DB4F2; height:40px; width:90px; overflow: hidden;}
tr.scheduleDays th:first-child { background-color:#ffffff; height:40px;}
.scheduleTimes{border: solid 1px; background-color:#29088A; color:#ffffff}
.scheduleEmpty{  
border-top:;
border-right: 1px dotted #C0C0C0;
border-bottom:;
border-left: 1px dotted #C0C0C0;
}
.scheduleFilled{  
border-top: 1px solid #000000;
border-right: 1px solid #000000;
border-bottom: 1px solid #000000;
border-left: 1px solid #000000;
background-color:#FFFF00;
font-weight:bold;
color: #000066;
}

.scheduleoutercontainer { position:relative }
.scheduleinnercontainer { position:relative; top:50%; text-align: center;}

.suggestREG{  
border-top: 1px solid #000000;
border-right: 1px solid #000000;
border-bottom: 1px solid #000000;
border-left: 1px solid #000000;
background-color:#FFFF00;
font-weight:bold;
color: #000066;
text-align: center;
}

.suggestFACEBOOK{  
border-top: 1px solid #000000;
border-right: 1px solid #000000;
border-bottom: 1px solid #000000;
border-left: 1px solid #000000;
background-color:#4c66a4;
font-weight:bold;
color: #000066;
text-align: center;
background-image:url('facebooklogo.png');
background-repeat:no-repeat;
background-position:left top;
background-size:20px 20px;
}

.suggestTIME{  
border-top: 1px solid #000000;
border-right: 1px solid #000000;
border-bottom: 1px solid #000000;
border-left: 1px solid #000000;
background-color:#FF0000;
font-weight:bold;
color: #000066;
text-align: center;
}

.addCourseTable {
	border: 1px solid #C0C0C0;
	border-spacing: 0px;
}
.linkedoutercontainer { position:relative; border-collapse:collapse; }
.linkedinnercontainer { position:relative; height: 20px; top:50%; text-align:center; border-collapse:collapse;}

span.poptext {z-index: 1}
span.poptext:hover {text-decoration:underline;}
span.poptext span {
	height: 2%;
	overflow:hidden;
  margin: 20px 0 0 0px; 
  padding: 3px 3px 3px 3px;
  border-style:solid; 
  border-color:black; 
  border-width:1px; 
  z-index: 6;
  display:none;}
span.poptext:hover span {
display:none;
position: relative;
left: 0px;
top: 10px;
z-index: 6;
background: #ffffff;
} 
select { width: 70px }



tr.listschedule td {}




</style>

<?php
	include('session_login_check.php');
	include('UserSchedule.php');
	include_once('DBaccess.php');
	error_reporting(0);
	
	$schedules = $_SESSION['setsOfSchedules'];
	
	
	//get number of $schedules
	$numSchedule = count($schedules);
	if(isset($_GET["schedNum"])) {
		$currSchedNum = $_GET["schedNum"];
	}
	else {
		$currSchedNum = -1;
	}
	
	if($currSchedNum == null || $numSchedule > 0) {
		$currSchedNum = 0;
	}
	?>
	
	<div id="navbarBlue"> 
		<ul> 
			<li>
			<h3 style="color: #FFFFFF; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; margin:0px 0px;">Suggested Schedule</h3>
			</li> 
			<?php
			if($numSchedule > 0) {
				echo "<li>";
			    echo '<h5 style="color: #C0C0C0; font-family: Gotham, \'Helvetica Neue\', Helvetica, Arial, sans-serif; margin:0px 0px;">';
				echo "Viewing "; echo $currSchedNum +1; echo " of "; echo $numSchedule; echo " schedules";
				echo '</h5>';
				echo '</li>';
				
				if($currSchedNum-1 >= 0) {
					//echo '<form action="./pickSchedule.php?schedNum='; echo $currSchedNum-1; echo '">';
					//echo '<input type="submit" value="Previous">';
					//echo '</form>';
		
					echo "<button onclick=\"location.href='./pickSchedule.php?schedNum=";
					echo $currSchedNum-1;
					echo "'\">";
					echo "Previous</button>";
				}
				if($currSchedNum+1 < $numSchedule){
					//echo '<form action="./pickSchedule.php?schedNum='; echo $currSchedNum+1; echo '">';
					//echo '<input type="submit" value="Next">';
					//echo '</form>';
		
					echo "<button onclick=\"location.href='./pickSchedule.php?schedNum=";
					echo $currSchedNum+1;
					echo "'\">";
					echo "Next</button>";
				}
				if( ($currSchedNum >= 0) && ($currSchedNum < $numSchedule) ){
					echo '<form name="cart" method="post" action='; echo "viewSchedule.php"; echo '?>';
					//echo '<input type="button" value="Register selected schedule" name="candidate">'; 
					echo '<button name="candidate2" value="';
					foreach($schedules[$currSchedNum] as $section) {
						echo $section[0];
						echo ":";
					}
					echo '">Register selected schedule</button>';
					echo '</form>';
				}	
			}
			else {
				echo "<li>";
			    echo '<h5 style="color: #C0C0C0; font-family: Gotham, \'Helvetica Neue\', Helvetica, Arial, sans-serif; margin:0px 0px;">';
				echo "No Schedule Found";
				echo '</h5>';
				echo '</li>';
			}
			?>
		</ul> 
	</div>
		
<?php
	if( ($numSchedule > 0) && ($currSchedNum >= 0) && ($currSchedNum < $numSchedule) ) {
		
		$currSched = $schedules[$currSchedNum];
		
		$userid = $_SESSION['login'];
		$class = new UserSchedule($userid);
		$class->setMode(0);
		
		foreach ($currSched as $section) {
			$crn = $section[0];
		

			$friends = $section[1];
			$walking = $section[2];
			if($friends > 0) {
				$color = "FACEBOOK";
			}
			else if($walking > 0) {
				$color = "TIME";
			}
			//else if($walking == 2) {}
			else {
				$color = "REG";
			}
			
			
			$result = $class -> addCourseWithColor($crn, $color);
			
		}
		echo '<table border ="0">';
		echo '		<tr style="height:20px">';
		echo '			<td style="background-color:#FFFF00; width:20px"></td>';
		echo '			<td><h6 style="color: #A9A9A9; font-family: Gotham, \'Helvetica Neue\', Helvetica, Arial, sans-serif; margin:0px 0px;">Suggested Course</h5></td>';
		echo '			<td style="background-color:#4c66a4; width:20px"></td>';
		echo '			<td><h6 style="color: #A9A9A9; font-family: Gotham, \'Helvetica Neue\', Helvetica, Arial, sans-serif; margin:0px 0px;">Facebook Friend</h5></td>';
		echo '			<td style="background-color:#FF0000; width:20px"></td>';
		echo '			<td><h6 style="color: #A9A9A9; font-family: Gotham, \'Helvetica Neue\', Helvetica, Arial, sans-serif; margin:0px 0px;">Classes Far Away</h5></td>';
		echo '		</tr>';
		echo '</table>';
		
		$class -> drawWeeklySchedule();
	}
	
	
	
	echo '<br>';
	

	
	

	
	
	
	$i = 0;
	$db = dbConnect();
	
	foreach ($schedules as $schedule) {
		printf("schedule %d<br />", ++$i);
		$sections = array();
		foreach ($schedule as $section) {
			// $crn = CRN of section
			$crn = $section[0];
			
			// $friends = number of friends in that section
			$friends = $section[1];
			
			// $walking = 0 if user can make it to this class no problem
			// $walking = 1 if user can almost make it to this (<3 minutes to spare)
			// $walking = 2 if user can not make it to this class
			$walking = $section[2];
			
			printf("CRN: %s, %s number of friends in this section, ", $crn, $friends);
			if ($walking == 0) {
				printf("user can make this class");
			} else if ($walking == 1) {
				printf("user can almost make this class");
			} else if ($walking == 2) {
				printf("user can NOT make this class");
			}
			printf("<br />");
			
			array_push($sections, $crn);
		}
		printf("<br />");
	}
?>
