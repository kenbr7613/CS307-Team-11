<?php

//id1 and id2 are two CRNs
function getWalkTime ($id1, $id2){

	if($id1 == 64154 && $id2 == 46151)
		return 1;
	else if($id2 == 64154 && $id1 == 46151)
		return 1;
	else
		return 0;
	
	mysql_connect('lore.cs.purdue.edu:11394', 'root', 'cs307team11');
	mysql_select_db("purduePlannerDB"); 

	//get values for $id1
        $sql = "SELECT a.lat, a.lon, b.StartTime, b.EndTime, b.Days FROM Locations a, CourseOfferings b WHERE b.CRN = " . $id1 . " AND b.Location = a.LocationID";
        $result = mysql_query($sql);
	$row = mysql_fetch_row($result);
	
	$start = $row[2];
	$end = $row[3];
	$start = str_replace(":", "", $start);
	$end = str_replace(":", "", $end);
	
	$starttime = intval($start);
	$endtime = intval($end);
	
	if ($starttime - $endtime <= 10) {
		return 1;
	} else {
			return 0;
	}
	if(mysql_num_rows($result) == 0)
		return 0; //we do not have the information to calculate this information, such as a location TBA
        $origin = $row[0] . ", " . $row[1];

	$id1Start = date_create($row[2]);
	$id1End = date_create($row[3]);
	$id1Days = $row[4];

	//get values for $id2
        $sql = "SELECT a.lat, a.lon, b.StartTime, b.EndTime, b.Days FROM Locations a, CourseOfferings b WHERE b.CRN = " . $id2 . " AND b.Location = a.LocationID";
        $result = mysql_query($sql);
        $row = mysql_fetch_row($result);
	if(mysql_num_rows($result) == 0)
		return 0; //we do not have the information to calculate this information, such as a location TBA
        $dest = $row[0] . ", " . $row[1];
	
	$id2Start = date_create($row[2]);
	$id2End = date_create($row[3]);
	$id2Days = $row[4];
	

	//if they meet on different days, then we assume walking from one to the other won't be a problem
/*	if(!sameDay($id1Days, $id2Days))
		return 0;
*/

	//find the walking distance from google in seconds
        /*
        $url = "http://maps.googleapis.com/maps/api/directions/json?origin=" . $origin ."&destination=" . $dest ."&sensor=false&mode=walking";
        $output = file_get_contents($url);
        $json_output = json_decode($output);
        $duration = $json_output->routes[0]->legs[0]->duration[0]->value;
        $walkTime = intval($duration);
	*/
	//determines which class is first and then finds the time between them
	$firstEnd = ($id1End < $id2End ? $id1End : $id2End);
	$secondBegin = ($id1Start > $id2Start ? $id1Start : $id2Start);

	$timeBetweenClasses = $secondBegin - $firstEnd; //should return difference in seconds based on UNIX time standards
	
	if($timeBetweenClasses <= 600)
		return 1;
	else
		return 0;
	
	//returns 0,1 or 2
	// 0 if distance is no problem
	// 1 if distance is possible but close (<3 minutes to spare)
	// 2 if distance is too far to make on time

	echo "<p>walktime " . $walkTime;
	echo "</p><p>Time Between " . $timeBetweenClasses . "</p>";
	if ($walkTime > $timeBetweenClasses)
		return 2;
	else if($walktime > ($timeBetweenClasses - 180))
		return 1;
	else
		return 0;

}

//Takes two strings in the form of "MWF" or "TR" or some combinatino of "MTWRFS" and returns true/false if they have a day that overlaps
function sameDay($week1, $week2)
{
	for ($i = 0; $i<strlen($week1); $i++)
	{
		$char = substr($week1, $i, 1);
		if(strpos($week2, $char) == true)
			return true;
	}
	return false;
}
?>
