<?php 
//Author: Cedric Abou-Farhat #27032633
//Author: Nicolas Horta-Adam #27171315
include 'navigation.php';
require_once 'mysql_connect.php';

$incomingIDs = array();
$newtripIDs = array();

// This page is from a drivers point of view
// It's used to accept requests from members wishing to obtain a trip

function showtrips (mysqli $dbc) {
	global $userid;
	global $incomingIDs;
	global $newtripIDs;

// This statement shows the trips that were requested. Therefore having and confirmed value of 0
	$statement = "SELECT * FROM trips, tripMembers WHERE trips.driverID = '$userid' 
	AND tripMembers.confirmed = '0' AND tripMembers.tripID = trips.tripID";

	if (!$query = mysqli_query($dbc, $statement))
		echo mysqli_error($dbc);

	if (mysqli_num_rows($query) == 0) 
		echo "<h4 style = 'color: black; font-style: italic;'> You have no requests yet </h4>";

	while ($row = mysqli_fetch_assoc($query)) {
		$count = 0;
		$dbid = $row['memberID'];
// find the name of the client member also as his ratings
		$namestatement = "SELECT fname, lname, rating FROM members WHERE id = '$dbid'";
		$namequery = mysqli_query($dbc, $namestatement);
		$rowname = mysqli_fetch_assoc($namequery);
		$dispname = $rowname ['fname'];
		$displname = $rowname ['lname'];
		$disprating = number_format($rowname['rating'],2);
// info the the trip posted by the user
		$dbtripid = $row['tripID'];
		$dbdate = $row['dateAndTime'];
		$dbfromstreet = $row['fromStreetNum'];
		$dbtostreet = $row['toStreetnum'];
		$dbfromcity = $row['fromCity'];
		$dbtocity = $row['toCity'];
		$dbfrompostal = $row['fromPostalCode'];
		$dbtopostal = $row['toPostalCode'];
		$numtaken = $row['numSpacesTaken'];
		$dbprice = $row['price'];
		
		if (!isset($_POST['acceptrequest'])) {
		echo "
			<tr>
			<td>$dbfromstreet ($dbfromcity) / $dbfrompostal</td>
			<td>$dbtostreet ($dbtocity) / $dbtopostal</td>
			<td>$dbdate</td>
			<td>$dbprice</td>
			<td>$dispname $displname </td>
			<td>$disprating</td>
			<td><input type = 'checkbox' value = '$count' name = 'selected []'></td>
			</tr>";
		}
		$count++;
		array_push($incomingIDs, $dbid);
		array_push($newtripIDs, $dbtripid);
	}
		if (isset($_POST['acceptrequest'])) {
// every trip selected will be set to accepted
		foreach ($_POST as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $k => $v) {
					$currenttripid = $newtripIDs[$v];
					$currentmemberid = $incomingIDs[$v];

					$seatamountreturn = "SELECT numSpacesTaken, price FROM trips WHERE tripID = '$currenttripid'";
					$seatamountq = mysqli_query($dbc, $seatamountreturn);
					$seatrow = mysqli_fetch_assoc($seatamountq);
					$minusseats = $seatrow['numSpacesTaken'];
					$returnedPrice = $seatrow['price'];
					$minusseats--;
			//Sends the receipt 
			$paystatement = "INSERT INTO transactions (toMember, fromMember, amount) 
			VALUES ('$currentmemberid', '$userid', '$returnedPrice')";
			if (!mysqli_query($dbc, $paystatement)) echo mysqli_eror($dbc);


					$updateSeats = "UPDATE trips SET numSpacesTaken = '$minusseats'";
					if (!mysqli_query($dbc, $updateSeats)) echo mysqli_eror($dbc);

					$q = "UPDATE tripMembers SET confirmed = 1 
					WHERE tripMembers.tripID = $currenttripid 
					AND tripMembers.memberID = $currentmemberid";
					if (!mysqli_query($dbc, $q)) 
						echo mysqli_error($dbc); 
					else $SUCCESS =  "<h3 style = 'color: black;'>Trips accepted!</h4>";
				}
			}
		}
	}
	if (isset($SUCCESS))
		echo $SUCCESS;
}

// this will return all the accepted trips from the driver and allows the driver to chat with their
// future client
function showacceptedtrips (mysqli $dbc) {
global $userid;
$statement = "SELECT * FROM trips, tripMembers WHERE trips.driverID = '$userid' 
	AND tripMembers.confirmed = '1' AND tripMembers.tripID = trips.tripID";

	if (!$query = mysqli_query($dbc, $statement))
		echo mysqli_error($dbc);

	while ($row = mysqli_fetch_assoc($query)) {
		$dbid = $row['memberID'];
		
		$namestatement = "SELECT fname, lname, rating FROM members WHERE id = '$dbid'";
		$namequery = mysqli_query($dbc, $namestatement);
		$rowname = mysqli_fetch_assoc($namequery);
		$dispname = $rowname ['fname'];
		$displname = $rowname ['lname'];
		$disprating = number_format($rowname['rating'],2);

		$dbtripid = $row['tripID'];
		$dbdate = $row['dateAndTime'];
		$dbfromstreet = $row['fromStreetNum'];
		$dbtostreet = $row['toStreetnum'];
		$dbfromcity = $row['fromCity'];
		$dbtocity = $row['toCity'];
		$dbfrompostal = $row['fromPostalCode'];
		$dbtopostal = $row['toPostalCode'];
		$dbseatsleft = $row['numSpacesTaken'];
		$dbcapacity = $row['numSpacesAvailable'];
		$dbprice = $row['price'];
		echo "
		<tr>
		<td>$dbfromstreet ($dbfromcity) / $dbfrompostal</td>
		<td>$dbtostreet ($dbtocity) / $dbtopostal</td>
		<td>$dbdate</td>
		<td>$dbseatsleft of $dbcapacity</td>
		<td>$dbprice</td>
		<td>$dispname $displname</td>
		<td>$disprating</td>
		<td><input type = 'radio' value = '$dbid' name = 'driver []'></td>
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
			h1 {padding-left: 15px;} h3 {color: red;}
			input[type=submit] {width: 10%;background-color: #4CAF50;color: white;padding: 14px 20px;margin: 8px 0;border: 4px;}
		</style>	
	</head>
	<body>
	<h1> Riders' Requests </h1>
		<form action ='' method = 'post'>	
			<table>
				<tr>
				    <th>From</th>
				    <th>To</th>
				    <th>Date</th>
				    <th>Price $</th>
				    <th>Client Name</th>
				    <th>Rating</th>
				    <th>Accept</th>
				</tr>
				  <?php showtrips($dbc);?>	
			</table>
			<input type ="submit" name = "acceptrequest" value = "Accept">
		</form>	
	<h1> Accepted Rides </h1>
		<form action = 'chat.php' method = 'post'>
			<table>
					<tr>
					    <th>From</th>
					    <th>To</th>
					    <th>Date</th>
					    <th>Seats Left</th>
					    <th>Price $</th>
					    <th>Client Name</th>
					    <th>Rating</th>
					    <th>Message Client</th>
					</tr>
					  <?php showacceptedtrips($dbc);
					  ?>	
			</table>
			<input type = 'submit' name = 'messagefromdriver' value = 'Message Client'>
		</form>				
	</body>	
</html>