<?php
include "DBaccess.php";


$linked_classes = getLinkedClasses(598);
//$linked_classes = getLinkedClasses(1439);
//$linked_classes = getLinkedClasses(51);
//$linked_classes = getLinkedClasses(11);
/*The return type is an array of arrays
$linked_classes[0] = 'CRN' 'CRN' ...
$linked_classes[1] = 'CRN' 'CRN' ...
*/

for ($x = 0; $x < count($linked_classes); $x++) {
	$item = $linked_classes[$x];	
	for ($y = 0; $y < count($item); $y++) {
		printf("%d ", $item[$y]);
	}
	printf("\n");
}

/*Given a CourseID, return an array of arrays containing the linked classes.
Ex: Given CourseID 1439(CS180), this is what the function returns

12956 and 12261 are lectures, the rest are labs

12956 65279 
12956 64743 
12956 64739 
12956 12257 
12261 12258 
12261 12259 
12261 12260 

Ex: Given CourseID 11(AAE 333), this is what the function returns

10024 is a Lab Prep Class, and the other ones are all Labs

10024 10016 
10024 10017 
10024 10018 
10024 10019 
10024 10020 
10024 10021 
10024 10022 
10024 10023

*/

function getLinkedClasses($courseID) 
{	
	$db = dbConnect();
	//query to start linked section search(start from Lectures, Individual Studies, and Labratory Preparation
	$query_for_primary_classes = sprintf("SELECT CRN, LinkID, LinkedSection, CourseType  FROM CourseOfferings Where CourseID=%d AND (CourseType=3 OR CourseType=5 OR CourseType=6);", $courseID);
	$main_classes = mysql_query($query_for_primary_classes, $db);
	$result_array = array();
	//loop through all main classes and look for linked sections
	for ($i = 0; $i < mysql_num_rows($main_classes); $i++) {
		$row = mysql_fetch_assoc($main_classes);
		$LinkID_primary = $row["LinkID"];
		$LinkedSection_primary = $row["LinkedSection"];
		$CRN_primary = $row["CRN"];
		//If there are no linked sections return the course itself
		if (is_null($LinkID_primary)) {
			return null;
		}
		//Contains ALL classes not a lecture, IS, or Lab Prep associated with our CourseID
		$query = sprintf("SELECT CRN, LinkID, LinkedSection, CourseType  FROM CourseOfferings Where LinkID='%s' AND CourseID=%d AND (CourseType!=3 OR CourseType!=5 OR CourseType!=6);", 					$LinkedSection_primary, $courseID);
		$secondary_classes = mysql_query($query, $db) or die($query."<br/><br/>".mysql_error());
		for ($j = 0; $j < mysql_num_rows($secondary_classes); $j++) {
			$row2 = mysql_fetch_assoc($secondary_classes);
            $LinkedSection_n = $row2["LinkedSection"];
            if ($LinkedSection_n == $LinkID_primary) {
	             $class_array = array();
                //add our lecture to the array
                $class_array[] = $CRN_primary;
                //insert into class array
                $class_array[] = $row2["CRN"];
                $result_array[] = $class_array;
            	continue;
            }
            //do this n times with a new row each time
            $linked_query = sprintf("SELECT CRN, LinkID, LinkedSection, CourseType  FROM CourseOfferings Where LinkID='%s' AND CourseID=%d AND (CourseType!=3 OR CourseType!=5 OR CourseType!=6);", 					$LinkedSection_n, $courseID);
            $n_classes = mysql_query($linked_query, $db) or die($linked_query."<br/><br/>".mysql_error());
            $k = 0;
            $count = mysql_num_rows($n_classes);
            while ($k < $count) {
                $obj = mysql_fetch_assoc($n_classes);
                $LinkedSection_n = $obj["LinkedSection"];//$row2["LinkedSection"];
                //Inner array of classes
                if ($LinkedSection_n == $LinkID_primary) {
	                $class_array = array();
                //add our lecture to the array
                $class_array[] = $CRN_primary;
                //insert into class array
                $class_array[] = $row2["CRN"];
                $class_array[] = $obj["CRN"];
				$result_array[] = $class_array;
				$k++;
				continue;
                }
                $new_query_2 = sprintf("SELECT CRN, LinkID, LinkedSection, CourseType  FROM CourseOfferings Where LinkID='%s' AND CourseID=%d AND (CourseType!=3 OR CourseType!=5 OR CourseType!=6);", 					$LinkedSection_n, $courseID);
            $class_obj = mysql_query($new_query_2, $db) or die($new_query_2."<br/><br/>".mysql_error());
            $j = 0;
            $count_j = mysql_num_rows($class_obj);
			while ($j < $count_j) {
				$obj2 = mysql_fetch_assoc($class_obj);
				 $LinkedSection_n = $obj2["LinkedSection"];//$row2["LinkedSection"];
				 $class_array = array();
                //add our lecture to the array
                $class_array[] = $CRN_primary;
                //insert into class array
                $class_array[] = $row2["CRN"];
                $class_array[] = $obj["CRN"];
                $class_array[] = $obj2["CRN"];
				while ($LinkedSection_n != $LinkID_primary) {
                    //continue going through the sections
                    printf("In inner loop with LinkedSection=%s and LinkID=%s and CRN=%s\n", $LinkedSection_n, $LinkID_primary, $CRN_primary);
                    $new_query = sprintf("SELECT CRN, LinkID, LinkedSection, CourseType  FROM CourseOfferings Where LinkID='%s' AND CourseID=%d AND (CourseType!=3 OR CourseType!=5 OR CourseType!						=6);",$LinkedSection_n, $courseID);
                    $inner_classes = mysql_query($new_query, $db) or die($new_query."<br/><br/>".mysql_error());
                    $class = mysql_fetch_assoc($inner_classes);
                    $LinkedSection_n = $class["LinkedSection"];
                    $class_array[] = $class["CRN"];
                }
                $result_array[] = $class_array;
                $j++;
			}
			$k++;
           }
		  }
	}
	
	return $result_array;
}
?>
