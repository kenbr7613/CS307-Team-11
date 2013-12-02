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
 <!--end css-->

 
<?php
include('linked_sections.php');
include('UserSchedule.php');

echo "<br>";
$userid = $_SESSION['login'];

//Create UserSchedule using loggin userid
$schedule = new UserSchedule($userid);
?>

<script>
//var coursesArray = new Array();
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

function highlightAndWrite(rowStart, rowEnd, colArray, text){
		console.error(rowStart);
		console.error(rowEnd);
		console.error(colArray);
		console.error(text);
		var col;
		for (var i=0;i<=colArray.length;i++) {
			col=colArray[i];
			for(var j=rowStart; j <= rowEnd; j++) {
				if(document.getElementById(j + ":" + col) != null)
					document.getElementById(j + ":" + col).style.backgroundColor="#00CCFF";
				if(j==rowStart && document.getElementById(j + ":" + col) != null)
					document.getElementById(j + ":" + col).innerHTML = text;
			}
		}
}
function twoHighlight(rowStart1, rowEnd1, colArray1, text1, rowStart2, rowEnd2, colArray2, text2) {
	highlightAndWrite(rowStart1, rowEnd1, colArray1, text1);
	highlightAndWrite(rowStart2, rowEnd2, colArray2, text2);
}
function show(prevCourse, level) {
console.error("in show");
	document.getElementById(prevCourse + ":" + level).style.display="inline";
}

function selectLevel() {
	var selected = document.getElementById("id");
    var courseID = selected.options[selected.selectedIndex].value;
}


function addRowToTable(newRow) {
		console.error("in add row to table");
		console.error(newRow);
		var table = document.getElementById("addCourseTable");
		var numRow = table.rows.length;
		var numNewColumn = newRow.length;
		console.error("length", numNewColumn);
		var aRow = table.insertRow(numRow);
		var cell;
		for(var i= 0;i<numNewColumn; i++) {
			console.error("in loop");
			cell = aRow.insertCell(i);
			cell.innerHTML=newRow[i];
			//cell.onclick=
		}
		/*
		console.error("in add row to table");
	while(!window.onload){
		console.error("wait");
	}
	if (window.onload){			
	console.error("loaded");
	*/
	/*
	//	return;
	//} 
	//else {
	//	console.error("wait");
	//	setTimeout('addRowToTable(row)', 1000);
	//}
	*/
}


function addRow(newRow) {
	//var newRow = eval(jasonFormat);
	console.error("in add row");
	newRow.toString();
	var table = document.getElementById("addCourseTable");
	var numRow = table.rows.length;
	var numNewColumn = newRow.length;
	var row = table.insertRow(numRow);
	var cell;
	for(var i= 0;i<numNewColumn; i++) {
		cell = row.insertCell(i);
		cell.innerHTML=newRow[i];
	}
}

</script>

<?php
function addToCart($CRN) {
/*
		if( !($schedule -> courseExists($CRN)) ){
			echo "ERROR: Course does not exist";
		}
		else if( $schedule -> isTaking($CRN) ) {
			echo "ERROR: Course already registered";
		}
		else if( isset($_SESSION['courseCandidate']) && in_array($CRN, $_SESSION['courseCandidate']) ){
			echo "ERROR: Course already in cart";
		}
		else {
			if( isset( $_SESSION['courseCandidate'] ) ) {
				$candidate = $_SESSION['courseCandidate'];
			}
			$candidate[] = $CRN;
			$_SESSION['courseCandidate'] = $candidate;
		}
		*/
}
	
//form action
if($_SERVER["REQUEST_METHOD"] == "POST") {
	//drop a course
	if( isset($_POST['drop']) && !isset($_POST['course']) ) {
		echo "ERROR: No course selected\n";
	}
	else if( isset($_POST['drop']) && isset($_POST['course']) ) {
		$return = $schedule->dropCourse($_POST['course']);
		if($return > 0) {
			echo "Successfuly dropped {$_POST['course']}\n";
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
			echo "Successfuly added {$_POST['cartCourse']}\n";
		}
		else {
			echo "ERROR: adding course {$_POST['cartCourse']}\n";
		}
	}
	
	//clear candidates
	if( isset($_POST['clear']) ) {
		unset($_SESSION['courseCandidate']);
		//$_SESSION['courseCandidate'] = null;
	}
	//add a candidate
	else if( isset($_POST['candidate']) && ($_POST['candidate'] != null) && ($_POST['candidate'] != '') ) {
		$linked_classes = getLinkedClasses($_POST['candidate']);
		if(count($linked_classes) == 0) {
			$CRN = $schedule -> getCRNFromCourseID($_POST['candidate']);
			echo 'add CRN ', $CRN;
			if($CRN < 1) {
				//echo "ERROR: Adding course";
			}
			else if( !($schedule -> courseExists($CRN)) ){
				echo "ERROR: Course does not exist";
			}
			else if( $schedule -> isTaking($CRN) ) {
				echo "ERROR: Course already registered";
			}
			else if( isset($_SESSION['courseCandidate']) && in_array($CRN, $_SESSION['courseCandidate']) ){
				echo "ERROR: Course already in cart";
			}
			else {
				if( isset( $_SESSION['courseCandidate'] ) ) {
					$candidate = $_SESSION['courseCandidate'];
				}
				$candidate[] = $CRN;
				$_SESSION['courseCandidate'] = $candidate;
			}
			unset($_SESSION['linked_classes']);
		}
		else if(count($linked_classes) > 0) {
			$_SESSION['linked_classes'] = $linked_classes;
			/*
			echo '<script>';
			echo '';
			echo 'addRowToTable(new Array("Linked Sections Found"));';
			echo '</script>';
			*/
			/*
			echo 'in if';
			echo count($linked_classes);
			$_SESSION['linked_classes'] = $linked_classes;
			print_r($_SESSION['linked_classes']);
			
			$temp = array();
			
			for($i=0; $i<count($linked_classes); $i++) {
				$temp[] = $linked_classes[$i][0];
			}
			
			$uniqueClasses = array_unique($temp);
			print_r($uniqueClasses);
			foreach($uniqueClasses as $uniqueClass) {
				echo "trying to add $uniqueClass";
				addToCart($uniqueClass)
			}
			*/
		}
			//convert to javascript array
			/*
			echo '<script>';
			
			echo 'var table=document.getElementById("addCourseTable");';
			echo 'var row=table.insertRow(0);';
			echo 'var cell1=row.insertCell(0);';
			echo 'var cell2=row.insertCell(1);';
			echo 'cell1.innerHTML="New";';
			echo 'cell2.innerHTML="New";';
			
			
    		echo 'var coursesArray = eval(', json_encode($linked_classes), ');';
    		//echo 'courseArray.toString();';
    		echo 'var tempArray = new array();';
			for($i=0; $i<count($linked_classes); $i++) {
				echo 'tempArray.push(';
				echo $linked_classes[$i][0];
				echo ');';
			}
			*/
			//echo 'var table = document.getElementByID("addCourseTable");';
			//echo 'var numRow = table.rows.length;';
			//echo 'var numNewColumn = tempArray.length;';
			//echo 'var row = table.insertRow(numRow);';
			//echo 'var row = table.insertRow(0);';
			//echo 'var cell1 = row.insertCell(0);';
			//echo 'var cell2 = row.insertCell(0);';
			//echo 'cell1.innerHTML="cell1";';
			//echo 'cell2.innerHTML="cell2";';
			//echo 'var cell = row.insertcell(0);';
			
			
			//echo 'for(var i= 0;i<numNewColumn; i++) {';
			//echo '	cell = row.insertCell(i);';
			//echo '	cell.innerHTML="abcdef";';
			//echo '}';
			//echo '</script>';
		
		/*
		echo '<script type="text/javascript">';
			echo 'var coursesArray = new array()';
			for($i=0; i<count($linked_classes); $i++) {
				echo 'var tempArray = new array()';
				
				for($j=0; i<count($linked_classes[$i]); $j++) {
				
				}
				
			}
		//convert to javascript array
		var array = new array();
		array.push();
		echo '<script type="text/javascript">', 'selectLevel()', '</script>';
		
		$courses[] = array_unique($linked_classes[$i][0]);
			array_search('green', $array);
			echo "got course $linked_classes[$i][0]";
		*/
			
		/*
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
		*/
	}
	
	if( isset($_POST['linkedSec']) && ($_POST['linkedSec'] != '') ) {
			list($lec, $linked) = explode(":", $_POST['linkedSec']);
			//add lecture
			if( !($schedule -> courseExists($lec)) ){
				echo "ERROR: Course does not exist";
			}
			else if( $schedule -> isTaking($lec) ) {
				echo "ERROR: Course already registered";
			}
			else if( isset($_SESSION['courseCandidate']) && in_array($lec, $_SESSION['courseCandidate']) ){
				echo "ERROR: Course already in cart";
			}
			//add linked
			else if( !($schedule -> courseExists($linked)) ){
				echo "ERROR: Course does not exist";
			}
			else if( $schedule -> isTaking($linked) ) {
				echo "ERROR: Course already registered";
			}
			else if( isset($_SESSION['courseCandidate']) && in_array($linked, $_SESSION['courseCandidate']) ){
				echo "ERROR: Course already in cart";
			}
			else {
				if( isset( $_SESSION['courseCandidate'] ) ) {
					$candidate = $_SESSION['courseCandidate'];
				}
				$candidate[] = $lec;
				$candidate[] = $linked;
				$_SESSION['courseCandidate'] = $candidate;
			}
			
		
		unset($_SESSION['linked_classes']);
		
	}

	//register a course
	if( isset($_POST['register']) && ($_POST['register'] != '') ) {
		$return = $schedule->addCourse($_POST['register']);
		if($return > 0) {
			echo "Successfuly added {$_POST['register']}\n";
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
			
			//$sql = "select CourseID,Department,Level,Title from Courses order by Department and Level;";
			$sql = "select Courses.CourseID, Department, Level, Title from Courses inner join CourseOfferings on Courses.CourseID = CourseOfferings.CourseID group by Courses.CourseID;";
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
				printf("\tlvls.options[%d]=new Option(\"%s - %s\", \"%s\");\n", $i++, $level, $title, $cid);
			
				//printf("\tlvls.options[%d]=new Option(\"%s - %s\", \"%s\", false, false).setAttribute(\"courseID\", \"{$cid}\");\n", $i++, $level, $title, $cid);
				//printf("\tlvls.options[%d]=setAttribute(\"courseID\", \"{$cid}\");");
				$prevDep = $dep;
			}
			printf("}\n");
			printf("}</script>");
?>



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
	else {
		//echo 'Cart Empty';
		//echo '<br>';
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
							echo "<option value=\"$s\">$s</option>";
						}
					?>					
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Level: 
				<select id="level" name="candidate" onchange="selectLevel()">
					<option value="null">--</option>
				</select>
			</td>
		</tr>
	</table>
	
	
	<?php
	/*
		function createLinkedSecTable($array) {
			if($array == null)
				return;
				
			echo '<tr>';
			$prevElement = "random";
			
			for($i=0; $i<count($array); $i++) {
				//check if next level exists
				$array[$i]
				if($prevElement != $array[$i]) {
					echo '<td>';
					echo $array[$i];
					echo '</td>';
					createLinkedSecTable($array[$i]);
				}
				$prevElement = $array[$i];
			}
			echo '</tr>';
		}
		if(isset($_SESSION['linked_classes']) && ($_SESSION['linked_classes'] != '') ){
			$linked_classes = $_SESSION['linked_classes'];
			print_r($linked_classes);
			echo '<table id ="addCourseTable">';
			createLinkedSecTable($linked_classes);
			echo '</table>';
			
		}
		*/
		
		
		

	?>

	<button type ="submit">Submit</button>
	<button type ="reset">Reset</button>
	</form>
	<!--- select course end -->
	
	
	<form name="linked" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<?php
	if(isset($_SESSION['linked_classes']) && ($_SESSION['linked_classes'] != '') ){
			echo '<h5 style="color: #3B3131; font-family: Gotham, \'Helvetica Neue\', Helvetica, Arial, sans-serif; margin:0px 0px;">Linked Section Found</h5>';
			
			$linked_classes = $_SESSION['linked_classes'];
			$_SESSION['linked_classes'];
			//print_r($linked_classes);
			$temp = array();
			for($i=0; $i<count($linked_classes); $i++) {
				$temp[] = $linked_classes[$i][0];		
			}
			$uniqueClasses = array_unique($temp);
			echo '<table id ="addCourseTable" class="addCourseTable">';
			
			foreach($uniqueClasses as $uniqueClass) {
				//LEVEL 0: print a uniqueClass
				$level = 1;
				echo '<tr>';
				echo "<td onclick=\"show({$uniqueClass}, {$level})\" bgcolor=\"#6DB4F2\" style=\"font-weight: bold;\">";
					echo 'Lec:'; echo $uniqueClass; echo '</td>';
				echo '</tr>';
				
				$prevLevel = $uniqueClass;
				$maxLevel = count($linked_classes[0]); //TODO
				
				//NEXT LEVEL: start from level 1;
				//MOVE THROUGH LEVELS
				for($level=1; $level<$maxLevel; $level++) {
					echo "<tr id=\"{$prevLevel}:{$level}\" style =\"display:none;\">";
					//MOVE THROUGH ROWS
					for($row=0; $row<count($linked_classes); $row++) {
						if ( $linked_classes[$row][$level-1] == $prevLevel) {		
							//create a cell
							
							
							$posValues = $schedule->calculateHighlightPosition($linked_classes[$row][$level]);
							$posValues2 = $schedule->calculateHighlightPosition($prevLevel);
							echo '<td class="addCourseTable" bgcolor="#00CCFF" align="center"';
								echo 'onclick=\'';
									echo 'twoHighlight(';
									foreach($posValues as $go) {
										echo $go;
										echo ',';
									}		
									echo '"'; echo 'Section:'; echo $linked_classes[$row][$level]; echo '"';
									echo ',';
									foreach($posValues2 as $go) {
										echo $go;
										echo ',';
									}
									echo '"'; echo 'Lec:'; echo $prevLevel; echo '"';
									echo ');';
									/*
								$posValues2 = $schedule->calculateHighlightPosition($prevLevel);	
									echo ' highlightAndWrite(';
									foreach($posValues2 as $go) {
										echo $go;
										echo ',';
									}
									echo '"'; echo 'Lec:'; echo $prevLevel; echo '"';
									echo ');';	*/	
								echo '\'';
							echo '>';
							echo 'Section';
							echo '<div class="linkedoutercontainer">';
							echo '<div class="linkedinnercontainer">';
							echo "{$linked_classes[$row][$level]}"; 
							echo '</div>';
							echo '</div>';
							
							
							
							echo "<input type=\"radio\" name=\"linkedSec\" value=\"{$prevLevel}:{$linked_classes[$row][$level]}\">";
							echo '</td>';
						}
						
						if($level > 1) {
							$prevLevel = $linked_classes[$row][$level-1];
						}
					}
					echo '</tr>';
				}
				
				
			}
			echo '</table>';
			echo '<h6 style="color: #3B3131; font-family: Gotham, \'Helvetica Neue\', Helvetica, Arial, sans-serif; margin:0px 0px;">Add Selected Linked Section to Cart</h5>';
			echo '<button type ="submit">Submit</button>';
		}
		?>
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


