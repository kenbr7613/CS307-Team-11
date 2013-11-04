<?php
include('DBaccess.php');

class UserSchedule {
	var $userID;
	var $courseOfferingIDs;
	var $db;
	
	public function __construct($userid) {		
		$this->userID = $userid;
		$this->db = dbConnect();
		$this->courseOfferingIDs = array();
		
		//Grab courses user is taking from DB
		$sql = "SELECT * FROM UserSchedule WHERE UserID = {$this->userID}";
		$result = mysql_query($sql, $this->db);
		
		//insert the results into array
		while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
			$this->courseOfferingIDs[] = $row["CourseOfferingID"];
		}
	}
	
	public function __destruct() {
		mysql_close($this->db);
	}
	
	public function addCourse($crn) {
		//get courseOfferingID from database
		$offeringID = $this->getcourseOfferingID($crn);
		if($offeringID < 1) {
			return -1;
		}
		
		//check if the user is already taking the course;
		if( $this->isTaking($crn) ) {
			return -1;
		}
		
		//check if time is available
		$courseTime = $this->getCourseTime($offeringID);
		if($courseTime == -1) {
			return -1;
		}
		
		if(!$this->timeAvailable($courseTime['StartTime'], $courseTime['EndTime'], $couseTime['StartDate'], $couseTime['EndDate']) ) {
			return -1;
		}
		
		//insert into UserSchedule table in database	
		$sql = "insert into UserSchedule (UserID, CourseOfferingID) values ($this->userID, $offeringID)";
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			return -1;
		}
		else {
			$this->courseOfferingIDs[] = $offeringID;
			return 1;
		}
	}

	public function dropCourse($crn) {
		//get courseOfferingID from database
		$offeringID = $this->getcourseOfferingID($crn);
		if($offeringID < 1) {
			return -1;
		}
		
		//check if the user is already taking the course;
		if( !$this->isTaking($crn) ) {
			return -1;
		}
		
		//drop a course in database
		$sql = "delete from UserSchedule where UserID = $this->userID and CourseOfferingID = $offeringID";
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			return -1;
		}
		else {
			if( ($index = array_search($offeringID, $this->courseOfferingIDs)) != false ) {
				print_r($this->courseOfferingIDs);
				echo "unset";
				echo $index;
    			unset($this->couseOfferingIDs[$index]);
    			print_r($this->courseOfferingIDs);
			}
			return 1;
		}
	}
	
	public function isTaking($crn) {
		$offeringID = $this->getcourseOfferingID($crn);
		if($offeringID < 1) {
			return FALSE;
		}
		
		return in_array($offeringID, $this->courseOfferingIDs);
	}
	
	public function timeAvailable($startTime, $finnishTime, $startDate, $endDate) {
		return true;
	}
	/*
	print_r($row);
				foreach($row as $value) {
					echo $value . " ";
				}
				*/
	
	public function listSchedule() {
		//echo "<table border = '1'>";
		$array = $this->courseOfferingIDs;
		$tableRow = array(' ', 'CRN', 'Title', 'Credits', 'StartTime', 'EndTime', 'Room', 'Instructor');
		echo $this->drawTableRow($tableRow, "white");
	
		foreach($array as $value) {
			echo $this->createRow($value, "white");
		}
		//echo "</table>";
	}
	
	public function createRow($offeringID, $color) {
		$sql = "select * from CourseOfferings where CourseOfferingID = $offeringID";
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			return -1;
		}
		else {
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			//get additional info the course
			$courseID = $row['CourseID'];
			$courseinfo = $this->getCourseInfo($courseID);
			
			$tableRow = array(
			"<input type=\"checkbox\" name=\"course\" value=\"{$row['CRN']}\">", 
			$row["CRN"], 
			$courseinfo["Title"],
			$courseinfo["Credits"],
			$row["StartTime"], 
			$row["EndTime"],
			$row["Room"],
			$row["Instructor"] );
			return $this->drawTableRow($tableRow, $color);
		}
	}
	
	private function drawTableRow($array, $color) {
	
		$string = "<tr bgcolor=\"$color\">";

		foreach($array as $value) {
			$string .= "<td>";
			$string .= $value;
			$string .= "</td>";
		}
		$string .= "</tr>";
		
		return $string;
	}
	
	private function getCourseInfo($courseID) {
		$sql = "select * from Courses where CourseID = $courseID";
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			return -1;
		}
		else {
			return mysql_fetch_array($result, MYSQL_ASSOC);
		}
	}
	
	private function getcourseOfferingID($crn) { 
		$sql = "select CourseOfferingID from CourseOfferings where CRN = $crn";
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			return -1;
		}
		else {
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			return $row["CourseOfferingID"];
		}
	}
	
	private function getCourseTime($offeringID) {
		$sql = "select StartTime, EndTime, StartDate, EndDate from CourseOfferings where CourseOfferingID = $offeringID";		 
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			return -1;
		}
		else {
			while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ){
				return array( 
				"StartTime" => $row["StartTime"],
				"EndTime" => $row["EndTime"],
				"StartDate" => $row["StartDate"],
				"EndDate" => $row["EndDate"]
				);
			}
		}
		
	}
}
?>