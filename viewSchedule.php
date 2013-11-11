<?php
include('session_login_check.php');
?>

<?php
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


<script>

function highlight(rowStart, rowEnd, colArray){
		
		var col;
		for (var i=0;i<=colArray.length;i++) {
			col=colArray[i];
			for(var j=rowStart; j <= rowEnd; j++) {
				document.getElementById(j + ":" + col).style.backgroundColor="red";
			}
		}
	
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


<div id="container" style="width:100%">

<div id="listRegistration" style="width:60%;float:left">
<!--form start-->
<form name="listSchedule" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

<!--listschedule start-->
<hr>
Current Registration
<table border = '1'>
<?php $schedule->listSchedule(); ?>
</table>
<input type ="radio" name="drop" value="drop">Drop Selected Course
<br>
Register a Course
<input type="text" name="register">
<br>
<button type ="submit">Submit</button>
<button type ="reset">Reset</button>
<hr>
</div>
</form>


<!---div cart START -->
<div id="cart" style="width:39%;float:right;border:1px solid black">
	<form name="cart" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	Cart
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
	
	<br>
	Add a Course to Cart
	<br>
	<input type="text" name="candidate">
	<br>
	<button type ="submit">Submit</button>
	<button type ="reset">Reset</button>
	</form>
</div>

<!---div cart END -->

<div id="weeklySchedule" style="width:100%;clear:both">
<?php $schedule -> drawWeeklySchedule(); ?>
</div>



</div>
<hr>
<form name ="printform">
<input type="button" onClick="window.print()" value="Print">
</form>
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


