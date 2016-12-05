<?php
//Author: Cedric Abou-Farhat #27032633
include 'navigation.php';
require_once 'mysql_connect.php';
$membertorate = array();
$currentrating = array();
$tripstofinalize = array();

// Once a trip gets rated, it will be set to completed in the database. 
// this is our way of simulating that a trip was completed

// for each element of the form array set below, it finds the value to be assigned to who
// e.g id 1 - > 4 stars
if (isset($_POST['ratedrivers'])) {
	global $tripstofinalize;
    foreach($_POST as $key => $value) {
        if (is_array($value)) {
            if ($key == 'riderid_') {
                foreach($value as $k => $v) {
                    array_push($membertorate, $v);
                }
            }
            if ($key == 'rate_') {
                foreach($value as $k => $v) {
                    array_push($currentrating, $v);
                }
            }
        }
    }
 // This will retreive the amount of times a driver was rated to compute an average for each driver
    // Once rating is done, it will finalize the trip by setting it to completed
    for ($i = 0; $i <count($membertorate); $i++){
 // assigned rating and id   	
    	$setrating = $currentrating[$i];
    	$setid = $membertorate[$i];
// gets current db rating
    	$returnCurrentRating = "SELECT rating, ratecount FROM members WHERE id = '$setid'";
    	$qury = mysqli_query($dbc, $returnCurrentRating);
    	$avg = mysqli_fetch_assoc($qury);
// gets the count and avg
    	$currentcount = $avg['ratecount'];
    	$currentaverage = $avg['rating'];
// computes new rating
    	$newrating = ($currentaverage * $currentcount + $setrating) / ($currentcount + 1);
    	$currentcount++;
// sets it to the current driver
    	$statement = "UPDATE members SET rating = '$newrating', ratecount = '$currentcount' WHERE id = '$setid'";
    	if (!mysqli_query($dbc, $statement));
    		echo mysqli_error($dbc);
    }
    finalize ($dbc);
}

// this function finalizes a trip by setting it to completed
function finalize (mysqli $dbc) {
	global $userid;
	for ($i = 0; $i < count($_SESSION['completedtrips']); $i++) {
		$currtrip = $_SESSION['completedtrips'][$i];
		
		$statement = "UPDATE trips SET completed = 1 WHERE tripID = '$currtrip'";

		if (!mysqli_query($dbc, $statement))
			echo mysqli_error($dbc);
		else $success = "<h3> Thank you for using Super </h3>";
	}
	if (isset($success)) echo $success;
}

// This will return all the trips booked by the member and allows the rider to chat with the driver
function showmybookedtrips (mysqli $dbc) {
	global $userid;
	global $tripstofinalize;
	global $nobookedtrips;
// stores the id of the trips to rate later on	
	$statement = "SELECT * FROM trips, tripMembers WHERE tripMembers.memberID = '$userid' 
	AND trips.tripID = tripMembers.tripID 
	AND tripMembers.confirmed = '1' AND trips.completed = '0'";
	$query = mysqli_query($dbc, $statement);

	if (mysqli_num_rows($query) == 0) 
		$nobookedtrips = "<h3>You currently have no booked trips</h3>";

	while ($row = mysqli_fetch_assoc($query)) {

		$dbtripid = $row['tripID'];
		array_push($tripstofinalize, $dbtripid);

		$dbdriverid = $row['driverID'];
		$statement2 = "SELECT rating, fname, lname FROM members WHERE id = '$dbdriverid'";
		$query2 = mysqli_query($dbc, $statement2);
		$row2 = mysqli_fetch_assoc($query2);
		$newdbFirstname = $row2['fname'];
		$newdbLastname = $row2['lname'];
		$newdbRating = $row2['rating'];

		$dbdate = $row['dateAndTime'];
		$dbfrequency = $row['frequency'];
		$dbfromstreet = $row['fromStreetNum'];
		$dbtostreet = $row['toStreetnum'];
		$dbfromcity = $row['fromCity'];
		$dbtocity = $row['toCity'];
		$dbfrompostal = $row['fromPostalCode'];
		$dbtopostal = $row['toPostalCode'];
		$dbprice = $row['price'];
		$dbspecial = $row['specialTripDesc'];
		echo "
		<tr>
		<td>$dbfromstreet ($dbfromcity) / $dbfrompostal</td>
		<td>$dbtostreet ($dbtocity) / $dbtopostal</td>
		<td>$dbdate</td>
		<td>$dbfrequency</td>
		<td>$dbprice</td>
		<td>$dbspecial</td>
		<td>$newdbFirstname $newdbLastname</td>
		<td>$newdbRating</td>
		<td><input type = 'radio' value = '$dbdriverid' name = 'rider []'></td>
		</tr>";
	}
	$_SESSION['completedtrips'] = $tripstofinalize;
}

// this function will display all the trips that were accepted by riders
// therefore are the trips a user is currently enrolled in. 
function showmycompletedtrips (mysqli $dbc) {
	global $userid;

// a confirmed trip will have a value of 1
	$statement = "SELECT * FROM trips, tripMembers WHERE tripMembers.memberID = '$userid' 
	AND trips.tripID = tripMembers.tripID 
	AND tripMembers.confirmed = '1' AND trips.completed = '0'";
// queries the statement
	$query = mysqli_query($dbc, $statement);
// finds all info on the requested trip
	while ($row = mysqli_fetch_assoc($query)) {

		$dbtripid = $row['tripID'];

		$dbdriverid = $row['driverID'];
		$statement2 = "SELECT rating, fname, lname FROM members WHERE id = '$dbdriverid'";
		$query2 = mysqli_query($dbc, $statement2);
		$row2 = mysqli_fetch_assoc($query2);
		$newdbFirstname = $row2['fname'];
		$newdbLastname = $row2['lname'];
		$newdbRating = $row2['rating'];

		$dbdate = $row['dateAndTime'];
		$dbfromstreet = $row['fromStreetNum'];
		$dbtostreet = $row['toStreetnum'];
		$dbfromcity = $row['fromCity'];
		$dbtocity = $row['toCity'];
		$dbfrompostal = $row['fromPostalCode'];
		$dbtopostal = $row['toPostalCode'];
		$dbprice = $row['price'];
// This will be in charge of assigning a rating to a driver
		echo "
		<tr>
		<td>$dbfromstreet ($dbfromcity) / $dbfrompostal</td>
		<td>$dbtostreet ($dbtocity) / $dbtopostal</td>
		<td>$dbdate</td>
		<td>$dbprice</td>
		<td>$newdbFirstname $newdbLastname</td>
		<td>$newdbRating</td>
		<td><select name = 'rate []'>
		  <option value='0'>0</option>
		  <option value='1'>1</option>
		  <option value='2'>2</option>
		  <option value='3'>3</option>
		  <option value='4'>4</option>
		  <option value='5'>5</option>
		  <option value='6'>6</option>
		  <option value='7'>7</option>
		  <option value='8'>8</option>
		  <option value='9'>9</option>
		  <option value='10'>10</option>
		</select></td>
		<input type = 'hidden' value = '$dbdriverid' name = 'riderid []'>
		</tr>";

	}
}
?>

<!DOCTYPE>
<html>
	<head>
		<style type="text/css">
			table {border-collapse: collapse; width: 100%; background-color: #f2f2f2;}
			th, td {padding: 5px; text-align: left; width: 10%; border-bottom: 1px solid #ddd;}
			h1 {padding-left: 15px;} h3 {color: black; font-style: italic;}
			input[type=submit] {width: 10%;background-color: #4CAF50;color: white;padding: 14px 20px;margin: 8px 0;border: 4px;}
		</style>
	</head>
	<body>
	<h1> My Booked Rides </h1>
		<form action = 'chat.php' method = 'post'>	
			<table>
				<tr>
				    <th>From</th>
				    <th>To</th>
				    <th>Date</th>
				    <th>Frequency</th>
				    <th>Price $</th>
				    <th>Special Note</th>
				    <th>Driver Name </th>
				    <th>Rating </th>
				    <th>Message Driver</th>			
				</tr>
				  <?php global $nobookedtrips; 
				  showmybookedtrips($dbc); 
				  if (isset($nobookedtrips)) echo $nobookedtrips;
				  ?>	
			</table>
			<input type = 'submit' name = 'messagefromrider' value = 'Message Driver'>
		</form>
		<h4 style = "color: red; font-style: italic;"> Thank you for using super. Please rate our services below.</h4>
		<form action = '' method = 'post'>	
			<table>
				<tr>
				    <th>From</th>
				    <th>To</th>
				    <th>Date</th>
				    <th>Price $</th>
				    <th>Driver Name </th>
				    <th>Rating </th>
				    <th>Rate Driver</th>			
				</tr>
				  <?php showmycompletedtrips($dbc) ?>	
			</table>
			<input type = 'submit' name = 'ratedrivers' value = 'Rate Driver'>
		</form>						
	</body>	
</html>