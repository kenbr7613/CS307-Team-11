<?php
include('session_login_check.php');
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
			//$schedule = new UserSchedule($userid);
		}
		else {
			echo "ERROR: dropping course {$_POST['course']}\n";
		}
	}
	
	//add a candidate
	if( isset($_POST['candidate']) && ($_POST['candidate'] != '') ) {
		if( isset( $_SESSION['courseCandidate'] ) ) {
			$candidate = $_SESSION['courseCandidate'];
		}
		$candidate[] = $_POST['candidate'];
		$_SESSION['courseCandidate'] = $candidate;
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

Sample Registration
<table border = '1'>
<?php
$schedule->listSchedule();
if( isset( $_SESSION['courseCandidate'] ) ) {
	$candidate = $_SESSION['courseCandidate'];
	foreach($candidate as $value) {
		echo $schedule->createRow($value, "red");
	}
}

?>
</table>
<input type ="radio" name="drop" value="drop">Delete Selected Course
<br>
Add a Course
<input type="text" name="candidate">
<br>
<button type ="submit">Submit</button>
<button type ="reset">Reset</button>

</form>

