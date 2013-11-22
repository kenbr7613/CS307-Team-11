<?php
include('DBaccess.php');

class UserSchedule {
	var $userID;
	var $courseOfferingIDs;
	var $db;
	var $writeMode;
	
	public function __construct($userid) {		
		$this->userID = $userid;
		$this->db = dbConnect();
		$this->courseOfferingIDs = array();
		$this->writeMode = 1;
		
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
			echo " Course does not exist ";
			return -1;
		}
		
		//check if the user is already taking the course;
		if( $this->isTaking($crn) ) {
			echo " Course is already Registered ";
			return -1;
		}
		
		//check if time is available
		$courseTime = $this->getCourseTime($offeringID);
		if($courseTime == -1) {
			return -1;
		}
		
		if(!$this->timeAvailable($courseTime['StartTime'], $courseTime['EndTime'], $courseTime['StartDate'], $courseTime['EndDate'], $courseTime['Days']) ) {
			echo " TIME CONFLICT ";
			return -1;
		}
		
		if($this->writeMode == 1) {
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
		else {
			$this->courseOfferingIDs[] = $offeringID;
			return 1;
		}
	}
	
	public function testSchedConflict($crn) {
		//get courseOfferingID from database
		$offeringID = $this->getcourseOfferingID($crn);
		if($offeringID < 1) {
			//echo " Course does not exist ";
			return -1;
		}
		
		//check if the user is already taking the course;
		if( $this->isTaking($crn) ) {
			//echo " Course is already Registered ";
			return -1;
		}
		
		//check if time is available
		$courseTime = $this->getCourseTime($offeringID);
		if($courseTime == -1) {
			return -1;
		}
		
		if(!$this->timeAvailable($courseTime['StartTime'], $courseTime['EndTime'], $courseTime['StartDate'], $courseTime['EndDate'], $courseTime['Days']) ) {
			//echo " TIME CONFLICT ";
			return -1;
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
			$temp = $this->courseOfferingIDs;
			if( ($index = array_search($offeringID, $temp)) != false ){
				unset($temp[$index]);
				$this->courseOfferingIDs = $temp;
			}
			/*
			if( ($index = array_search($offeringID, $this->courseOfferingIDs)) != false ) {
				//print_r($this->courseOfferingIDs);
				//echo "unset";
				//echo $index;
				
    			unset($this->couseOfferingIDs[$index]);
    			//print_r($this->courseOfferingIDs);
			}
			*/
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
	
	public function timeAvailable($startTime, $finnishTime, $startDate, $endDate, $days) {
		$array = $this->courseOfferingIDs;
		foreach($array as $value) {
			$result = $this->getCourseDetail($value);
			if($result == -1) {
				//error
				return false;
			}
			if( ($result['Start'] >= $startTime) && ($result['Start'] <= $finnishTime) ){
				if( similar_text($days, $result['Days']) > 0 ){
					return false;
				}
			}
			else if( ($result['End'] >= $startTime) && ($result['End'] <= $finnishTime) ){
				if (similar_text($days, $result['Days']) > 0 ){
					return false;
				}
			}
			
		}
		return true;
	}

	
	
	public function drawWeeklySchedule() {
		$courses = array();
		//get all course info
		$array = $this->courseOfferingIDs;
		foreach($array as $value) {
			$result = $this->getCourseDetail($value);
			if($result == -1) {
				//error
				return;
			}
			$courses[] = $result;
		}
	
		//create array of days
		$daysArray = array();
		for($i=0; $i < 7; $i++) {
			$daysArray[] = array();
		}
		
		//create array of times
		$timesArray = array();
		for($i = 0; $i < (4*23); $i++) {
			$timesArray[] = $daysArray;
		}
		
		foreach($courses as $value) {
			$start = $value['Start'];
			$end = $value['End'];
			$days = $value['Days'];
			
			//get start, end; hours and minutes
			list($startHour, $startMinute) = explode(":", $start);
			list($endHour, $endMinute) = explode(":", $end);
			$startHour = intval($startHour);
			$startMinute = intval($startMinute);
			$endHour = intval($endHour);
			$endMinute = intval($endMinute);
			
			//convert hours and minutes to timesArray index
			$startMinuteIndex;
			if($startMinute < 20) { $startMinuteIndex = 0; }
			else if($startMinute < 30) { $startMinuteIndex = 1; }
			else if($startMinute < 50) { $startMinuteIndex = 2; }
			else { $startMinuteIndex = 3; }
			
			$endMinuteIndex;
			if($endMinute <= 20) { $endMinuteIndex = 0; }
			else if($endMinute <= 30) { $endMinuteIndex = 1; }
			else if($endMinute <= 50) { $endMinuteIndex = 2; }
			else { $endMinuteIndex = 3; }
			
			
			$startIndex = $startHour * 4 + $startMinuteIndex;
			$endIndex = $endHour * 4 + $endMinuteIndex;
			$timeSpan = $endIndex - $startIndex + 1;
						
			//convert days to indicies
			$daysIndices = array();
			if( strstr($days, 'TBA') != false) {  }
			else {
				if( strstr($days, 'M') != false) { $daysIndices[] = 0; }
				if( strstr($days, 'T') != false) { $daysIndices[] = 1; }
				if( strstr($days, 'W') != false) { $daysIndices[] = 2; }
				if( strstr($days, 'R') != false) { $daysIndices[] = 3; }
				if( strstr($days, 'F') != false) { $daysIndices[] = 4; }
			}
			
			//populate times array
			foreach($daysIndices as $ind) {
				$timesArray[$startIndex][$ind] = $value;
				$timesArray[$startIndex][$ind]['TimeSpan'] = $timeSpan;
			}
		}		
		
		//draw table!!!!
		$borrow = array(0,0,0,0,0,0,0);
		echo "<table id=\"schedule\"border = \"1\">";
		echo "<tr><th></th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr>";
		for($timeInd = 32; $timeInd < 72; $timeInd++) {
			//time
			if($timeInd % 4 == 0 ) {
				$realTime = strval(floor($timeInd / 4));
				$realTime .= ":00";
			}
			else{
				$realTime = null;
			}
			//borrowed row
			for($i = 0; $i < 7; $i++) {
				if( !isset( $timesArray[$timeInd][$i]['TimeSpan'] )) {
					$span = 0;
				}
				else {
					$span = $timesArray[$timeInd][$i]['TimeSpan'];
				}
				if($span > 1) {
					$borrow[$i] += ($span);
				}
			}
				
			echo $this->drawWeeklyScheduleRow( $realTime, $timesArray[$timeInd], $borrow, $timeInd );
			
			//decrement borrow
			for($i = 0; $i < 7; $i++) {
				if($borrow[$i] > 0) {
					$borrow[$i]--;
				}
			}
		}
		echo "</table>";
	}
	

	
	public function drawWeeklyScheduleRow($realTime, $array, $borrow, $timeInd) {		
		$string = "<tr>";
		//print time if minute is zero
		if($realTime != null) {
			$string .= "<td rowspan = \"4\">$realTime</td>";
		}
		else {
			//$string .= "<td></td>";
		}
		
		
		for($i = 0; $i < 7; $i++) {
			if($array[$i] != null){
				$span = $array[$i]['TimeSpan'];
				$string .= "<td id=\"{$timeInd}:{$i}\" rowspan = \"{$span}\" bgcolor=\"#C0C0C0\">";
				$string .= "{$array[$i]['Department']} {$array[$i]['Level']}";
				$string .= "</td>";
			}
			else {
				if($borrow[$i] > 0) {
					//do nothing
				}
				else {
					//$string .= "<td> &nbsp;	</td>";
					$string .= "<td id=\"{$timeInd}:{$i}\">&nbsp;</td>";
				}
			}
			
		}

		$string .= "</tr>";
		return $string;
	}
	
	private function getCourseDetail($offeringID) {
		$sql = "select * from CourseOfferings where CourseOfferingID = $offeringID";
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			return -1;
		}
		
		//department level start end location
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		//get start and end date
		$days = $row['Days'];
		$startTime = $row['StartTime'];
		$endTime = $row['EndTime'];
		
		
		//get level and department
		$courseID = $row['CourseID'];
		$sql = "select * from Courses where CourseID = $courseID";
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			return -1;
		}
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$department = $row['Department'];
		$level = $row['Level'];
		
		$courseInfo = array("Department"=>$department, "Level"=>$level, "Start"=>$startTime, "End"=>$endTime, "Days"=>$days);
		
		return $courseInfo;
	}

	public function listSchedule() {
		//echo "<table border = '1'>";
		$array = $this->courseOfferingIDs;
		$tableRow = array(' ', 'CRN', 'Title', 'Credits', 'StartTime', 'EndTime', 'Days', 'Room', 'Instructor');
		echo $this->drawTableRow($tableRow, "white");
	
		foreach($array as $value) {
			$return = $this->createListScheduleRow($value, "white");
			if($return == -1) {
				//error
			}
			else {
				echo $return;
			}
		}
	}
	
	public function createListScheduleRow($offeringID, $color) {
		$sql = "select * from CourseOfferings where CourseOfferingID = $offeringID";
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			return -1;
		}
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		//get additional info the course
		$courseID = $row['CourseID'];
		$courseinfo = $this->getCourseInfo($courseID);
		if($courseinfo == -1) {
			//error
			return -1;
		}
		
		$department = $courseinfo['Department'];
		$level = $courseinfo['Level'];
		$deptLevel = "$department $level";
		
		$tableRow = array(
		"<input type=\"checkbox\" name=\"course\" value=\"{$row['CRN']}\">", 
		$row["CRN"], 
		$deptLevel,
		$courseinfo["Credits"],
		$row["StartTime"], 
		$row["EndTime"],
		$row["Days"],
		$row["Room"],
		$row["Instructor"] );
		return $this->drawTableRow($tableRow, $color);
		
	}
	public function courseExists($crn){
		$offeringID = $this->getcourseOfferingID($crn);
		$sql = "select * from CourseOfferings where CourseOfferingID = $offeringID";
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			//error
			return false;
		}
		else {
			return true;
		}
	}
	
	public function createCandidateBox($crn){
		$offeringID = $this->getcourseOfferingID($crn);
		$sql = "select * from CourseOfferings where CourseOfferingID = $offeringID";
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			//error
			return -1;
		}
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		//get additional info the course
		$courseID = $row['CourseID'];
		$startTime = $row['StartTime'];
		$endTime = $row['EndTime'];
		$days = $row['Days'];
		$deptLevel = $this->getDeptLevel($courseID);
		
		//calculate row and column
		list($startHour, $startMinute) = explode(":", $startTime);
		list($endHour, $endMinute) = explode(":", $endTime);
		$startHour = intval($startHour);
		$startMinute = intval($startMinute);
		$endHour = intval($endHour);
		$endMinute = intval($endMinute);
		
		
		$startMinuteIndex;
		if($startMinute < 20) { $startMinuteIndex = 0; }
		else if($startMinute < 30) { $startMinuteIndex = 1; }
		else if($startMinute < 50) { $startMinuteIndex = 2; }
		else { $startMinuteIndex = 3; }
		
		$endMinuteIndex;
		if($endMinute <= 20) { $endMinuteIndex = 0; }
		else if($endMinute <= 30) { $endMinuteIndex = 1; }
		else if($endMinute <= 50) { $endMinuteIndex = 2; }
		else { $endMinuteIndex = 3; }
		
		$startIndex = $startHour * 4 + $startMinuteIndex;
		$endIndex = $endHour * 4 + $endMinuteIndex;
		$timeSpan = $endIndex - $startIndex + 1;
		
		
		$columns = array();
		if( strstr($days, 'M') != false) { $columns[] = 0; }
		if( strstr($days, 'T') != false) { $columns[] = 1; }
		if( strstr($days, 'W') != false) { $columns[] = 2; }
		if( strstr($days, 'R') != false) { $columns[] = 3; }
		if( strstr($days, 'F') != false) { $columns[] = 4; }

		
		//$row = ($startHour - 8) * 4 + $startMinuteIndex;
		$rowStart = $startIndex;
		$rowEnd = $endIndex;
		
		$jsArray = "[";
		foreach($columns as $list){
			$jsArray .= strval($list);
			$jsArray .= strval(",");
		}
		if(sizeof($columns) > 0) {
			$jsArray = substr($jsArray, 0, -1);
		}
		$jsArray .= "]";
		
		$string = "<table border = \"1\"><tr onClick=\"highlight({$rowStart},{$rowEnd}, {$jsArray})\" id=\"{$startTime}.{$endTime}.{$days}\"><td style=\"vertical-align:middle\"><input type=\"checkbox\" name=\"cartCourse\" value=\"{$crn}\"></td><td style=\"vertical-align:middle\"><div style=\"height: 50px;\" \"vertical-align:middle\"> $deptLevel </div> </td></tr></table>";
		return $string;
	}
	
	public function setMode($mode) {
		$this->writeMode = $mode;
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
	private function getDeptLevel($courseID) {
		$sql = "select * from Courses where CourseID = $courseID";
		$result = mysql_query($sql, $this->db);
		if(!$result) {
			return -1;
		}
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$department = $row['Department'];
		$level = $row['Level'];
		
		$returnVal = $department;
		$returnVal .= " ";
		$returnVal .=$level;
		return $returnVal;
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
		$sql = "select StartTime, EndTime, StartDate, EndDate, Days from CourseOfferings where CourseOfferingID = $offeringID";		 
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
				"EndDate" => $row["EndDate"],
				"Days" => $row["Days"]
				);
			}
		}
		
	}
}
?>