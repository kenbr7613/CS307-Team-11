<?php
include('session_login_check.php');
?>
<!-- css -->
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
 <!--end css-->
 
<?php
include('linked_sections.php');
include('UserSchedule.php');

echo "<br>";
$userid = $_SESSION['login'];

//Create UserSchedule using loggin userid
$schedule = new UserSchedule($userid);

//form action
if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	//drop a course
	if( isset($_POST['drop']) && !isset($_POST['course']) ) {
		echo "ERROR: No course selected\n";
	}
	else if( isset($_POST['drop']) && isset($_POST['course']) ) {
		$return = $schedule->dropCourse($_POST['course']);
		if($return > 0) {
			echo "successfuly dropped {$_POST['course']}\n";
			$schedule = null;
			$schedule = new UserSchedule($userid);
			//$schedule = new UserSchedule($userid);
			
		}
		else {
			echo "ERROR: dropping course {$_POST['course']}\n";
		}
	}
	//reg from cart
	if( isset($_POST['regCandidate']) && !isset($_POST['cartCourse']) ) {
		echo "ERROR: No course selected\n";
	}
	else if( isset($_POST['regCandidate']) && isset($_POST['cartCourse']) ) {
		/*
		if( !($schedule -> courseExists($_POST['cartCourse'])) ){
			echo "ERROR: Course does not exist";
		}
		else if( $schedule -> isTaking($_POST['cartCourse']) ) {
			echo "ERROR: Course already registered";
		}
		*/
		$return = $schedule->addCourse($_POST['cartCourse']);
		if($return > 0) {
			echo "successfuly added {$_POST['cartCourse']}\n";
		}
		else {
			echo "ERROR: adding course {$_POST['cartCourse']}\n";
		}
	}
	
	//clear candidates
	if( isset($_POST['clear']) ) {
		$_SESSION['courseCandidate'] = null;
	}
	//add a candidate
	if( isset($_POST['candidate']) && ($_POST['candidate'] != '') ) {
		/*
		$linked_classes = getLinkedClasses($_POST['candidate']);
		for($i=0; i<count($linked_classes); $i++) {
			for($j=0; i<count($linked_classes[$i]); $j++) {
				
			}
		}
		*/
		if( !($schedule -> courseExists($_POST['candidate'])) ){
			echo "ERROR: Course does not exist";
		}
		else if( $schedule -> isTaking($_POST['candidate']) ) {
			echo "ERROR: Course already registered";
		}
		else if( isset($_SESSION['courseCandidate']) && in_array($_POST['candidate'], $_SESSION['courseCandidate']) ){
			echo "ERROR: Course already in cart";
		}
		else {
			if( isset( $_SESSION['courseCandidate'] ) ) {
				$candidate = $_SESSION['courseCandidate'];
			}
			$candidate[] = $_POST['candidate'];
			$_SESSION['courseCandidate'] = $candidate;
		}
	}
	
	
	//register a course
	if( isset($_POST['register']) && ($_POST['register'] != '') ) {
		$return = $schedule->addCourse($_POST['register']);
		if($return > 0) {
			echo "successfuly added {$_POST['register']}\n";
		}
		else {
			echo "ERROR: adding course {$_POST['register']}\n";
		}
	}
	
}
?>

<?php
			$db = dbConnect();
			echo "<script>\n";
			echo "function populateNum() {\n";
			echo "var deps = document.getElementById(\"dep\");\n";
			echo "var lvls = document.getElementById(\"level\");\n";
			echo "var dep = deps.options[deps.selectedIndex].value;\n";
			
			$sql = "select CourseID,Department,Level,Title from Courses order by Department and Level;";
			$result = mysql_query($sql, $db);
			$prevDep = "";
			$i = 0;
			while ($row = mysql_fetch_array($result)) {
				$cid = $row[0];
				$dep = $row[1];
				$level = $row[2];
				$title = $row[3];
				
				if ($prevDep != $dep) {
					if ($prevDep != "") {
						printf("}");
					}
					printf("if (dep == \"%s\") {\n", $dep);
					printf("\tlvls.options.length=0;\n");
					$i = 0;
				}
				
				printf("\tlvls.options[%d]=new Option(\"%s - %s\", \"%s\", false, false);\n", $i++, $level, $title, $cid);
				$prevDep = $dep;
			}
			printf("}\n");
			printf("}</script>");
?>

<script>


function highlight(rowStart, rowEnd, colArray, crn){
		var col;
		for (var i=0;i<=colArray.length;i++) {
			col=colArray[i];
			for(var j=rowStart; j <= rowEnd; j++) {
				document.getElementById(j + ":" + col).style.backgroundColor="red";
			}
		}
		
		document.getElementById(crn).style.backgroundColor="red";
		
		/*
		var divs = document.getElementsByTagName("cartinnercontainer");
  		for (var i = 0; i < divs.length; i++) {
			if (divs[i].id == crn) {
				divs[i].style.backgroundColor = "red";
			}
			else {
			   divs[i].style.backgroundColor = "#ffc";
			}
  		}
  		*/
}

</script>

<style>
th{
	width: 70px;
}
td
{
height:25px;
}
</style>


<div id="container" style="width:70%;float:left">

	<div id="listRegistration">
	<!--form start-->
	<form name="listSchedule" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

	<!--listschedule start-->
		<!--title--->
		<div id="navbar"> 
		<ul> 
			<li>
			<h3 style="color: #FFFFFF; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; margin:0px 0px;">Current Registration</h3>
			</li> 
			<li>
			<h5 style="color: #C0C0C0; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; margin:0px 0px;">You are currently registered for the following courses</h5>
			</li>
	
		</ul> 
		</div> 
		<!-- end title -->
		<br>
	<table id="listscheduletable" border = '1'>
	<?php $schedule->listSchedule(); ?>
	</table>
	<input type ="radio" name="drop" value="drop">Drop Selected Course
	<br>
	Register a Course
	<input type="text" name="register">
	<br>
	<button type ="submit">Submit</button>
	<button type ="reset">Reset</button>
	</form>
	</div>


	<div id="weeklySchedule" style="clear:both">
		<!--title--->
		<div id="navbarBlue"> 
		<ul> 
			<li>
			<h3 style="color: #FFFFFF; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; margin:0px 0px;">Your week</h3>
			</li> 
	
		</ul> 
	<table border ="0">
				<tr style="height:20px">
					<td style="background-color:#FFFF00; width:20px"></td>
					<td><h6 style="color: #A9A9A9; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; margin:0px 0px;">Registered Course</h5></td>
					<td style="background-color:#FF0000; width:20px"></td>
					<td><h6 style="color: #A9A9A9; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; margin:0px 0px;">Cart selection</h5></td>
				</tr>
			</table>
		</div> 
		<!-- end title -->
	
	<?php $schedule -> drawWeeklySchedule(); ?>
	
	</div>
	<hr>
	<form name ="printform">
	<input type="button" onClick="window.print()" value="Print">
	</form>
</div>



<!---div cart START -->
<div id="cart" style="width:29%;float:right;">
	<form name="cart" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	
	<!--title--->
	<div id="navbar"> 
  	<ul> 
		<li>
	  	<h3 style="color: #FFFFFF; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; margin:0px 0px;">Cart</h3>
		</li> 
		<li>
		<h5 style="color: #C0C0C0; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; margin:0px 0px;">Add courses you want to take</h5>
		</li>
    
  	</ul> 
	</div> 
	<!-- end title -->
	<?php
	if( isset( $_SESSION['courseCandidate'] ) ) {
		$candidate = $_SESSION['courseCandidate'];
		foreach($candidate as $value) {
			$return = $schedule->createCandidateBox($value);
			if($return == -1) {
				echo "Course does not exist";
				print_r($_SESSION['courseCandidate']);
			}
			else {
				echo $return;
			}
		}
	}
	?>
	<input type="submit" value="Register selected" name="regCandidate">
	<input type="submit" value="Clear cart" name="clear">
	<!--- select course start -->
		<!--title--->
	<br>
	<h5 style="color: #A9A9A9; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; margin:0px 0px;">Add a Course to Cart</h5>
	<!-- end title -->
	<table border="0">
		<tr>
			<td>
				Department:
				<select id="dep" onchange="populateNum()">
					<option value="null">--</option>
					<?php
						$sql = "select distinct Department from Courses;";
						$result = mysql_query($sql, $db);
						while ($dep = mysql_fetch_array($result, MYSQL_BOTH)) {
							$s = $dep[0];
							echo "<option name=\"candiate\" value=\"$s\">$s</option>";
						}
					?>					
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Level: 
				<select id="level">
					<option value="null">--</option>
				</select>
			</td>
		</tr>
	</table>
	<!--- select course end -->
	<br>
	Add a Course to Cart
	<br>
	<input type="text" name="candidate">
	<?php
	/*
	
	*/
	?>
	<br>
	<button type ="submit">Submit</button>
	<button type ="reset">Reset</button>
	</form>
</div>

<!---div cart END -->


<!----
Sample Registration
<table border = '1'>
<?php /*
$schedule->listSchedule();
if( isset( $_SESSION['courseCandidate'] ) ) {
	$candidate = $_SESSION['courseCandidate'];
	foreach($candidate as $value) {
		echo $schedule->createRow($value, "red");
	}
}

*/?>

<!-------
</table>


<input type ="radio" name="drop" value="drop">Delete Selected Course
<br>
Add a Course
<input type="text" name="candidate">
<br>
<button type ="submit">Submit</button>
<button type ="reset">Reset</button>
---->


