<?php

//id1 and id2 are two CourseOfferingIDs
function getWalkTime ($id1, $id2){
	// insert replace 22075 and 10685 with the 2 CRNs of the epics202 and engl06 sections
	if ($id1 == 22075 && $id2 == 10685) {
		return 1;
	}
	return 0;

	//get values for $id1
        $sql = "SELECT a.lat, a.lon, b.StartTime, b.EndTime, b.Days FROM Locations a, CourseOfferings b WHERE b.CourseOfferingID = " . $id1 . " AND b.Location = a.LocationID";
        $result = mysql_query($sql);
	$row = mysql_fetch_row($result);
	if(mysql_num_rows($result) == 0)
		return 0; //we do not have the information to calculate this information, such as a location TBA
        $orign = $row['a.lat'] . ", " . $row['a.lon'];

	$id1Start = date_create($row['b.StartTime']);
	$id1End = date_create($row['b.EndTime']);
	$id1Days = $row['days'];

	//get values for $id2
        $sql = "SELECT a.lat, a.lon, b.StartTime, b.EndTime, b.Days FROM Locations a, CourseOfferings b WHERE b.CourseOfferingID = " . $id2 . " AND b.Location = a.LocationID";
        $result = mysql_query($sql);
        $row = mysql_fetch_row($result);
	if(mysql_num_rows($result) == 0)
		return 0; //we do not have the information to calculate this information, such as a location TBA
        $destination = $row['lat'] . ", " . $row['lon'];
	
	$id2Start = date_create($row['b.StartTime']);
	$id2End = date_create($row['b.EndTime']);
	$id1Days = $row['days'];

	//if they meet on different days, then we assume walking from one to the other won't be a problem
	if(!sameDay($id1Days, $id2Days))
		return 0;


	//find the walking distance from google in seconds
        $url = "http://maps.googleapis.com/maps/api/directions/json?origin=" . $origin ."&destination=" . $dest ."&sensor=false&mode=walking";
        $output = file_get_contents($url);
        $json_output = json_decode($output,true);
        $duration = $json_output->routes[0]->legs[0]->duration[0]->value;
        $walkTime = intval($duration);

	//determines which class is first and then finds the time between them
	$firstEnd = ($id1End < $id2End ? $id1End : $id2End);
	$secondBegin = ($id1Start > $id2Start ? $id1Start : $id2Start);

	$timeBetweenClasses = $secondBegin - $firstEnd; //should return difference in seconds based on UNIX time standards

	//returns 0,1 or 2
	// 0 if distance is no problem
	// 1 if distance is possible but close (<3 minutes to spare)
	// 2 if distance is too far to make on time

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
	for ($i = 0; i<strlen($week1); $i++)
	{
		$char = substr($week1, $i, 1);
		if(strpos($week2, $char))
			return true;
	}
	return false;
}



